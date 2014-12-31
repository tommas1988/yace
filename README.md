#Wellcome to Yace


##About Yace

Yace stands for **Y**af l**a**yout **c**apable vi**e**w, is a layout capable
view engine that run on Yaf.


##Configuration

There two configuration items could apply to Yace. They
`yace.layout_name` and `yace.layout_path` which represent the layout
name and layout path respectively(see Yace\ViewEntity setction).
Both of these items have their default values: `layout_name =
'layout\layout'` and `layout_path = application.directory 'views/'`.

##Yace classes

###Yace\LayoutCapableView
`Yace\LayoutCapableView` implements `Yaf_View_Interface` and provides some
other functions.

Some important ones are:
* `Yace\LayoutCapableView::getLayout` returns Layout view object
* `Yace\LayoutCapableView::disableLayout` disable layout view
* `Yace\LayoutCapableView::getViewEntity` returns current request view
object
* `Yace\LayoutCapableView::registerViewHelper` registers view helper
* `Yace\LayoutCapableView::__set` sets global view variable
* `Yace\LayoutCapableView::__get` gets global view variable
* `Yace\LayoutCapableView::__call` invoke view helper

>Global view variables are variables that can access in the form of
>`$this->var-name` in all views of a structured view

###Yace\ViewEntity
`Yace\ViewEntity` represents a view that contain a view`s informations

Here are some of its methods:
* `Yace\ViewEntity::setViewName` sets view name
* `Yace\ViewEntity::setScriptPath` sets view script path. The same
purpose as `Yaf_View_Interface::setScriptPath`
* `Yace\ViewEntity::assign` sets view variable
* `Yace\ViewEntity::addChild` adds a sub view

######About View Name and Script Path
Script or layout path is a absolute base path that view scripts live
in. And view or layout name follows by the view script extension could
locate a view script under the script path. A view name index/index and
phtml extension, for example, would point to index/index.phtml under script path.

###Yace\Exceptions
All exceptions that Yace might throw are implements
`Yace\ExceptionInterface` interface.

##Use Cases

Some basic use cases are show in the example.
