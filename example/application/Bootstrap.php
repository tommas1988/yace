<?php
use Yace\LayoutCapableView;

class Bootstrap extends Yaf_Bootstrap_Abstract
{
    public function _initYace(Yaf_Dispatcher $dispatcher)
    {
        $config = Yaf_Application::app()->getConfig();
        $yace   = new LayoutCapableView($config);

        $dispatcher->setView($yace);
    }
}
