<?php
namespace Yace;

use Yace\Exception\DomainException;
use Yace\Exception\InvalidCallbackException;
use Yace\Exception\InvalidViewScriptException;
use Yace\Exception\RuntimeException;

/**
 * Yaf layout capable view
 */
class LayoutCapableView implements \Yaf_View_Interface
{
    /**
     * Application configuration
     * @var \Yaf_Config_Abstract
     */
    protected $__config;

    /**
     * The layout view entity
     * @var ViewEntity
     */
    protected $__layout;

    /**
     * The current request view entity
     * @var ViewEntity
     */
    protected $__viewEntity;

    /**
     * Default script path
     * @var string
     */
    protected $__defaultScriptPath;

    /**
     * View script suffix
     * @var string
     */
    protected $__viewSuffix = 'phtml';

    /**
     * Whether inject layout
     * @var bool
     */
    protected $__injectLayout = true;

    /**
     * Global view variables
     * @var array
     */
    protected $__variables = array();

    /**
     * View helpers
     * @var array
     */
    protected $__viewHelpers = array();

    /**
     * Construtor
     *
     * @param \Yaf_Config_Abstract $config
     */
    public function __construct(\Yaf_Config_Abstract $config)
    {
        $this->__defaultScriptPath = $config->get('application')->get('directory') . 'views/';

        $viewConfig = $config->get('application')->get('view');
        if ($viewConfig && ($suffix = $viewConfig->get('ext'))) {
            $this->__viewSuffix = $suffix;
        }

        $this->__config = $config;
    }

    /**
     * Get Layout view entity
     *
     * @return ViewEntity
     */
    public function getLayout()
    {
        if ($this->__layout) {
            return $this->__layout;
        }

        $this->__layout = new ViewEntity();

        $layoutName = 'layout/layout';
        $layoutPath = $this->__defaultScriptPath;

        $yaceConfig = $this->__config->get('yace');
        if ($yaceConfig) {
            // Check layout_name configuration
            $test = $yaceConfig->get('layout_name');
            if (!$test) {
                $layoutName = $test;
            }

            // Check layout path configuration
            $test = $yaceConfig->get('layout_path');
            if (!$test) {
                $layoutPath = $test;
            }
        }

        $this->__layout->setViewName($layoutName)
                       ->setScriptPath($layoutPath);

        return $this->__layout;
    }

    /**
     * Disable layout function
     *
     * @return self
     */
    public function disableLayout()
    {
        $this->__injectLayout = false;
        return $this;
    }

    /**
     * Register view helper
     *
     * @param  string $name Helper name
     * @param  callback $callback
     * @param  bool $override Whether override existance callback
     * @return self
     * @throws DomainException If try to override existance helper while the override flag is false
     * @throws InvalidCallbackException If callback is not callable
     */
    public function registerViewHelper($name, $callback, $override = false)
    {
        if (false === $override && isset($this->__viewHelpers[$name])) {
            throw new DomainException(sprintf(
                'View helper: %s already exists', $name));
        }
        if (!is_callable($callback)) {
            throw new InvalidCallbackException(sprintf(
                'Invalid callback: %s', var_export($callback, 1)));
        }

        $this->__viewHelpers[$name] = $callback;
        return $this;
    }

    /**
     * Set global view variable
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->__variables[(string) $name] = $value;
    }

    /**
     * Get global view variable
     *
     * @param string
     * @return mixed
     * @throws DomainException If request variable name dose not exist
     */
    public function __get($name)
    {
        if (!isset($this->__variables[$name])) {
            throw new DomainException(sprintf(
                'Request global view variable: %s dose not exist', $name));
        }

        return $this->__variables[$name];
    }

    /**
     * Invoke a view helper
     *
     * @param  string $name
     * @param  array $arguments
     * @return mixed
     * @throws DomainException If request helper dose not exist
     */
    public function __call($name, $arguments)
    {
        if (!isset($this->__viewHelpers[$name])) {
            throw new DomainException(sprint(
                'Request helper: %s dose not exist', $name));
        }

        return call_user_func_array($this->__viewHelpers[$name], $arguments);
    }

    /**
     * Get current request view entity
     *
     * @return ViewEntity
     */
    public function getViewEntity()
    {
        if (!$this->__viewEntity) {
            $this->__viewEntity = new ViewEntity();
            $this->__viewEntity->setScriptPath($this->__defaultScriptPath);
        }

        return $this->__viewEntity;
    }

    /**
     * Implement \Yaf_View_Interface::setScriptPath.
     * Set script path to the current request view entity
     *
     * @param  string $viewDir
     * @return self
     */
    public function setScriptPath($viewDir)
    {
        $this->getViewEntity()->setScriptPath($viewDir);
        return $this;
    }

    /**
     * Implement \Yaf_View_Interface::getScriptPath
     * Get script path of the current request view entity
     *
     * @return string
     */
    public function getScriptPath()
    {
        return $this->getViewEntity()->getScriptPath();
    }

    /**
     * Implement \Yaf_View_Interface::assign
     * Assign varaible to the current request view entity
     *
     * @param  string $name
     * @param  mixed $value
     * @return self
     */
    public function assign($name, $value = null)
    {
        $this->getViewEntity()->assign($name, $value);
        return $this;
    }

    /**
     * Implement \Yaf_View_Interface::render
     *
     * @param  string $viewName
     * @param  array|null $viewVars
     * @return string
     */
    public function render($viewName, $viewVars = null)
    {
        $viewEntity = $this->getViewEntity();

        // Set view path of the current request view entity if not set
        if (!$viewEntity->getViewName()) {
            $viewName = substr($viewName, 0, strrpos($viewName, '.'));
            $viewEntity->setViewName($viewName);
        }

        if ($viewVars) {
            foreach ($viewVars as $name => $value) {
                $viewEntity->assign($name, $value);
            }
        }

        if ($this->__injectLayout) {
            $layout = $this->getLayout();
            $layout->addChild('content', $viewEntity);

            $viewEntity = $layout;
        }

        return $this->doRender($viewEntity);
    }

    /**
     * Implement \Yaf_View_Interface::display
     *
     * @param  string $viewName
     * @param  array|null $viewVars
     */
    public function display($viewName, $viewVars = null)
    {
        echo $this->render($viewName, $viewVars);
    }

    /**
     * Render view entity
     *
     * @param  ViewEntity $viewEntity
     * @return string
     * @throws InvalidViewScriptException If view script dose exist
     * @throws DomainException If view variable names contain system preserved names
     * @throws RuntimeException If there are any throwed exceptions in view script
     */
    protected function doRender(ViewEntity $viewEntity)
    {
        $scriptPath = $viewEntity->getScriptPath();

        if ($viewEntity->hasChildren()) {
            foreach ($viewEntity->getChildren() as $name => $childEntity) {
                // If child view entity`s script path is not set, set it to the parent`s
                if (!$childEntity->getScriptPath()) {
                    $childEntity->setScriptPath($scriptPath);
                }

                $viewEntity->assign($name, $this->doRender($childEntity));
            }
        }
        unset($name, $childEntity);

        $__viewScript = $scriptPath . $viewEntity->getViewName() . '.' . $this->__viewSuffix;
        if (!file_exists($__viewScript)) {
            throw new InvalidViewScriptException(sprintf(
                'Can not find view script: %s', $__viewScript));
        }
        unset($scriptPath);

        $__vars = $viewEntity->getVariables();
        unset($viewEntity);

        if (isset($__vars['this'])
            || isset($__vars['__viewScript'])
            || isset($__var['__vars'])
        ) {
            throw new DomainException(
                'View variables can not contain this, __viewScript and __vars');
        }

        extract($__vars);
        try {
            ob_start();
            include $__viewScript;
            $content = ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            throw new RuntimeException(sprintf(
                'Can not render view script, error infos: %s', $e));
        }

        return $content;
    }
}
