<?php
class FormatRouteResultPlugin extends Yaf_Plugin_Abstract
{
    public function preDispatch(
        Yaf_Request_Abstract $request, Yaf_Response_Abstract $response
    ) {
        $module     = $request->getModuleName();
        $controller = $request->getControllerName();
        $action     = $request->getActionName();

        $module = $this->getCanonicalString($module);
        $request->setModuleName($module);

        $controller = $this->getCanonicalString($controller);
        $request->setControllerName($controller);

        $action = lcfirst($this->getCanonicalString($action));
        $request->setActionName($action);
    }

    protected function getCanonicalString($string)
    {
        if (false === strpos($string, '-')) {
            return $string;
        }

        return str_replace(' ', '', ucwords(strtr($string, '-', ' ')));
    }
}
