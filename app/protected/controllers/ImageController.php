<?php

class ImageController extends Controller
{
	public function actionIndex()
	{
        XCloud::hlsMediaSlicing();die;
        //Util::dump(XQiniu::setPfop());
        //Util::dump(XQiniu::getSaveasUrl('yangzhihua.u.qiniudn.com/target.wav?avthumb/mp3', 'saveas.mp3'));//图片转码

        $token = XQiniu::getPfopAccessToken();
        Util::dump($token);

        /**
         * 测试代码
         */
        $access_key = XQiniu::$accessKey;
        $secret_key = XQiniu::$secretKey;
        $bucket = XQiniu::$bucket;
        $key = 'target.wav';
        $fops = 'avthumb/mp3';
        $notify_url = 'http://1.chuchujie.sinaapp.com/qiniu.php';

        // 组建个管理凭证的原始数据，是个带参数的URL
        $url = 'http://api.qiniu.com/pfop/';
        $query = sprintf('/pfop/\nbucket=%s&key=%s&fops=%s&notifyURL=%s', $bucket, $key, $fops, $notify_url);
        $url .= "?" . $query;

        // 生成管理凭证
        $token = generate_token($access_key, $secret_key, $url);
        Util::dump($token);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.qiniu.com/pfop/');
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $header[] = "Authorization: QBox $token";
        $header[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_exec($ch);
        //print_r($data);//输出结果

		$this->render('index');
	}

    public function actionApi(){
        $request_type = Yii::app()->request->getParam('type');

        switch ($request_type){
            case 'getUploadSecretKey':
                 Util::dump(XQiniu::getUploadSecretKey());
        }
    }

    public function actionAvthumb(){
        $this->render('avthumb', array(
            'token'=>XQiniu::getUploadAccessToken(),
        ));
    }

    public function actionForm(){

        $this->render('form',array(
            'token'=>XQiniu::getUpToken(),
        ));
    }


    public function actionUpload()
    {
        $model = new Images();
        if(Yii::app()->request->isPostRequest){
            $uploadParams = array(
                'thumb'=>true,//开启缩略图
                'thumbSize'=>array(100, 100),//缩略图宽高
                'allowExts'=>array('jpg','gif', 'png', 'jpeg'),//设置允许上传文件类型
                'saveRule'=>'date',//文件存储路径规则  按天存储
                'is_water'=>true, //是否加水印
            );
            $file = XUpload::upload($_FILES['save_url'], $uploadParams);
            if (is_array($file)) {
                //上传到七牛云
                $msg = XQiniu::upload($file['pathname']);
                Util::dump($msg);DIE;
                $model = new Images();
                $model->save_path = $file['pathname'];
                $model->qiniu_url = 'http://yangzhihua.u.qiniudn.com/' . $model->save_path;
                $model->beforeSave();
                if ($model->save()) {
                    $this->redirect(array('Image/upload'));
                } else {
                    @unlink($file['pathname']);
                    @unlink($file['paththumbname']);
                    Util::dump($model->errors);
                    exit(CJSON::encode(array ('error' => 1 , 'message' => '上传错误' )));
                }

            } else {
                exit(CJSON::encode(array ('error' => 1 , 'message' => '上传错误' )));
            }
        }

        $this->render('upload', array(
            'model', $model
        ));
    }

    public function actionUploadAudio()
    {
        if(Yii::app()->request->isPostRequest){
            $uploadParams = array(
                'allowExts'=>array('mp3','wma', 'wav'),//设置允许上传文件类型
                'saveRule'=>'date',//文件存储路径规则  按天存储
                ''
            );
            $file = XUpload::upload($_FILES['audio_name'], $uploadParams);

            if (is_array($file)) {
                //上传到七牛云
                $msg = XQiniu::uploadPfop($file['pathname']);
                Util::dump($msg);DIE;

            } else {
                Util::dump($file);DIE;
                exit(CJSON::encode(array ('error' => 0 , 'message' => '上传错误' )));
            }
        }

        Util::dump(XQiniu::getUploadAccessToken());
        $this->render('audio');
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