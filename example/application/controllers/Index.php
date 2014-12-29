<?php
use Yace\ViewEntity;

class IndexController extends Yaf_Controller_Abstract
{
    public function init()
    {
        // The following codes dosen`t must required for Yace.
        // The only purpose of them is to make url more readable
        // with long action name, like actions in this controller.
        /** start **/
        $request = $this->getRequest();
        $action  = $request->getActionName();
        // Set legal action name
        $request->setActionName(lcfirst(str_replace(' ', '', ucwords(strtr($action, '-', ' ')))));
        // Use raw action name as part of view path
        $this->getView()->getViewEntity()->setViewName('index/' . $action);
        /** end **/

        // Set baseUri as a global view variable
        $this->getView()->baseUri = $this->getRequest()->getBaseUri();
    }

    public function indexAction()
    {
        $this->getView()->getLayout()->assign('title', 'Yaf Layout Capable View');
    }

    public function showLayoutViewExampleAction()
    {
        $this->getView()->getLayout()->assign('title', 'Layout View Example');
    }

    public function showNestedViewStructureExampleAction()
    {
        $fooView    = new ViewEntity('index/foo-view');
        $barView    = new ViewEntity('index/bar-view');
        $subBarView = new ViewEntity('index/sub-bar-view');

        $barView->addChild('subBar', $subBarView);

        $viewEngine = $this->getView();
        $viewEngine->getLayout()->assign('title', 'Nested View Structure Example');
        $viewEngine->getViewEntity()
                   ->addChild('foo', $fooView)
                   ->addChild('bar', $barView);
    }

    public function showSwitchLayoutExampleAction()
    {
        $layout = $this->getView()->getLayout();
        $layout->setViewName('layout/other-layout')
               ->assign('title', 'Switch Layout Example');
    }

    public function showDisabledLayoutExampleAction()
    {
        $this->getView()->disableLayout();
    }
}
