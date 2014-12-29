<?php
namespace Yace;

/**
 * Yace view entity
 */
class ViewEntity
{
    /**
     * Relative view path
     * @var string
     */
    protected $viewName;

    /**
     * View script path
     * @var string
     */
    protected $scriptPath;

    /**
     * View variables
     * @var array
     */
    protected $variables = array();

    /**
     * Children view entities
     * @var array
     */
    protected $children = array();

    /**
     * Constructor
     *
     * @param string|null $viewName
     */
    public function __construct($viewName = null)
    {
        if ($viewName) {
            $this->setViewName($viewName);
        }
    }

    /**
     * Set relative view path
     *
     * @param  string $varPath
     * @return self
     */
    public function setViewName($viewName)
    {
        $this->viewName = $viewName;
        return $this;
    }

    /**
     * Get relative view path
     *
     * @return string
     */
    public function getViewName()
    {
        return $this->viewName;
    }

    /**
     * Set view script path
     *
     * @param  string $viewDir
     * @return self
     */
    public function setScriptPath($viewDir)
    {
        $this->scriptPath = $viewDir;
        return $this;
    }

    /**
     * Get view script path
     *
     * @return string
     */
    public function getScriptPath()
    {
        return $this->scriptPath;
    }

    /**
     * Assign view variables
     *
     * @param  string $name
     * @param  mixed $value
     * @return self
     */
    public function assign($name, $value)
    {
        $this->variables[(string) $name] = $value;
        return $this;
    }

    /**
     * Add child view entity
     *
     * @param  string $captureTo Set what the parent entity`s variable to capture this entity
     * @param  ViewEntity $viewEntity
     */
    public function addChild($captureTo, ViewEntity $viewEntity)
    {
        $this->children[$captureTo] = $viewEntity;
        return $this;
    }

    /**
     * Get children view entities
     *
     * @return ViewEntity[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Whether have children view entities
     *
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * Get view variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
