<?php

class AdminModule extends CWebModule
{

	public function init()
	{
		$this->setImport(array(
			'admin.components.*',
		));
        $this->layout = 'admin.views.layouts.main';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
