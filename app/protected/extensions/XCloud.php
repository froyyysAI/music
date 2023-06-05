<?php
/**
 * 七牛云 文件，流媒体处理
 *
 */

class XCloud {

    /*
     *音视频切片
     */
    static public function hlsMediaSlicing() {
        Yii::import( 'application.vendors.*' );
        require_once("Cloud/QiniuSDK.class.php");
        $key = "target.wav";
        $Instance = QiniuSDK::getInstance('QiniuRSTransfer','cdnimg');
        $params = array(
            'Format'=>'mp3',
        );
        $baseurl = $Instance->MakeBaseUrl($key);
        $Instance = QiniuSDK::getInstance('QiniuAudioVisual');
        echo $Instance->MakeRequest($baseurl,'avthumb',$params);
    }

    /*
     *音视频切片
     */
    static public function getMediaInfo() {
        Yii::import( 'application.vendors.*' );
        require_once("Cloud/QiniuSDK.class.php");
        $Instance = QiniuSDK::getInstance('QiniuRS');
        list($ret, $err) = $Instance->Stat('cdnimg','yzh.jpg');
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }


    /*
     *获取上传秘钥
     */
    static public function getUploadSecretKey() {
        Yii::import( 'application.vendors.*' );
        require_once("qiniu/rs.php");
        $bucket = 'yangzhihua';
        $accessKey = '7ASYeHt7yUslxLMsfFJf2U5SFrR1cHohQCR0QuDc';
        $secretKey = 'kdGHD3_HH-IrMP4CDpnhrPGC8UAKJvYlJy0yxlH0';

        Qiniu_SetKeys($accessKey, $secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
        $upToken = $putPolicy->Token(null);

        return $upToken;
    }

    

}
