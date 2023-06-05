<?php

class AdminController extends SBaseController
{
    public function actionIndex(){
        $this->render('index');
    }

    public function actionUser(){
        $this->render('user');
    }

    public function actionSetting(){
        $this->render('setting');
    }

    public function actionGuest(){
        $this->render('guest');
    }

    public function actionApiGet(){
        echo CJSON::encode(array('status'=>1,'info'=>'success'));
    }

    public function actionApiUpdate(){
        echo CJSON::encode(array('status'=>1,'info'=>'success'));
    }

    public function actionApiDelete(){
        echo CJSON::encode(array('status'=>1,'info'=>'success'));
    }

    public function actionApiModify(){
        echo CJSON::encode(array('status'=>1,'info'=>'success'));
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}