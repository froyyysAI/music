<?php

class UserController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}


    /*
     * 后台用户登录
     */
    public function actionLogin(){
        $model = AdminUser::model();

        // ajax验证
        if(isset($_POST['ajax']) && $_POST['ajax']==='admin_user_form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // form表单提交验证
        if(isset($_POST['AdminUser']))
        {
            $model->attributes=$_POST['AdminUser'];
            if($model->validate() && $model->login())
                $this->redirect(array('default/index'));
        }

        // 传递表单对象到视图渲染
        $this->render('login',array('model'=>$model));
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