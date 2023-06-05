<?php
/**
 * 七牛云 文件上传，流媒体处理 实例
 * 注：七牛官网公开凭证算法，并且持久化处理的api都需要 POST，GET 并带上其指定的方式签名才能正常调用（一些列的拼接，加密url,及参数而衍生的算法）
 * 由于七牛SDK代码无注释，代码之间的调用过于绕弯子不适合开发调试  此文件正在集成项目开发的常用接口，推荐对照七牛api文档来调试，无需看SDK代码 本实例文件重点是：为了让你明白七牛文档上的参数的含义及底层算法，代码中不乏有重复的轮子无须纠结这个
 *
 * doc: http://developer.qiniu.com/docs/v6/api/reference/security/access-token.html
 */

class XQiniu {

    static public $accessKey = 'oUzKTvUun-Rfz797UxtJN8e7V0Y3gHGMLNFBvkKe';
    static public $secretKey = 'EZSWogZFQY0mVi6M0ORmYuGZuUWgGdY_xyKglSn3';
    static public $bucket = 'yangzhihua';

    /*
     * 获取上传秘钥
     * doc: http://developer.qiniu.com/docs/v6/api/reference/security/upload-token.html
     */
    static public function getUploadAccessToken() {
        //申明签名需要用到的参数
        $time = time() + 3600;
        $find = array('+', '/');
        $replace = array('-', '_');
        //1.构造上传策略：
        $putPolicy = array(
          'scope'=>self::$bucket,
          'deadline'=>$time
        );
        //2.将上传策略序列化成为JSON格式：
        $putPolicy = json_encode($putPolicy);
        //3.对JSON编码的上传策略进行URL安全的Base64编码，得到待签名字符串：
        $encodedPutPolicy = str_replace($find, $replace, base64_encode($putPolicy));
        //4.使用SecertKey对上一步生成的待签名字符串计算HMAC-SHA1签名：
        $sign = hash_hmac('sha1', $encodedPutPolicy, self::$secretKey, true);
        //5.对签名进行URL安全的Base64编码：
        $encodedSign = str_replace($find, $replace, base64_encode($sign));

        //6.uploadToken = AccessKey + ':' + encodedSign + ':' + encodedPutPolicy
        return self::$accessKey . ':' . $encodedSign . ':' . $encodedPutPolicy;
    }

    /*
     *获取持久化处理的管理凭证 本案例（音频转码）
     *
     * doc: http://developer.qiniu.com/docs/v6/api/reference/security/access-token.html
     * doc: http://developer.qiniu.com/docs/v6/api/reference/fop/av/avthumb.html  （音频处理）
     * @return 构造持久化处理的管理的凭证
     */
    static public function getPfopAccessToken() {
        $bucket = self::$bucket;
        $key = 'target.wav';
        $fops = 'avthumb/mp3';//todo::待验证
        $notify_url = 'http://1.chuchujie.sinaapp.com/qiniu.php';
        //1.1.生成待签名的原始字符串：抽取请求URL中<path>或<path>?<query>的部分与请求内容部分（即HTTP Body），用“\n”连接起来
        $query = sprintf('/pfop/\nbucket=%s&key=%s&fops=%s&notifyURL=%s', $bucket, $key, $fops, $notify_url);
        //2.七牛官网上所谓的 HMAC_SHA1 签名
        $find = array('+', '/');
        $replace = array('-', '_');
        $sign =  hash_hmac('sha1', $query, self::$secretKey, true);
        $encodedSign = str_replace($find, $replace, base64_encode($sign));

        //4.最后，将AccessKey和encodedSign用:连接起来：
        return self::$accessKey . ':' . $encodedSign;
    }

    /*
     * 异步处理pfop (example：图片缩放)
     * notify_url : http://1.chuchujie.sinaapp.com/qiniu.php
     * doc： http://developer.qiniu.com/docs/v6/api/reference/fop/pfop/pfop.html
     * @return 构造异步处理pfop的凭证
     */
    static public function getPfopToken(){
        $find = array('+', '/');
        $replace = array('-', '_');
        $key = 'yzh.jpg';
        $notify_url = 'http://1.chuchujie.sinaapp.com/qiniu.php';
        $query = sprintf('/pfop/\nbucket=%s&key=%s&fops=imageView/2/w/200/h/200&notifyURL=%s', self::$bucket, $key, $notify_url);
        $sign = hash_hmac('sha1', $query, self::$secretKey, true);
        $encodedSign = str_replace($find, $replace, base64_encode($sign));

        return self::$accessKey . ':' . $encodedSign;
    }

    /*
     * 查询持久化处理状态（prefop）
     * doc: http://developer.qiniu.com/docs/v6/api/reference/fop/pfop/prefop.html
     * @return 构造查询持久化处理状态的凭证
     */
    static public function getPrefopToken(){
        $find = array('+', '/');
        $replace = array('-', '_');
        $key = 'yzh.jpg';
        $data = self::$bucket . ':' . $key;
        $data = str_replace($find, $replace, base64_encode($data));
        $sign = hash_hmac('sha1', $data, self::$secretKey, true);
        $encodedSign = str_replace($find, $replace, base64_encode($sign));

        return self::$accessKey . ':' . $encodedSign;
    }

    /*
     * 处理结果另存（saveas）
     * call：　makeUrl("yangzhihua.u.qiniudn.com/yzh.jpg?imageView/2/w/200/h/200","yangzhihua","yzhsaveas.png");
     * doc: developer.qiniu.com/docs/v6/api/reference/fop/saveas.html
     * @return 构造 saves保存的url
     */
    static public function getSaveasUrl($url,$savekey){
        $find = array('+', '/');
        $replace = array('-', '_');
        $bucket = self::$bucket;

        $encode = str_replace($find, $replace, base64_encode("$bucket:$savekey"));
        $url = $url . "|saveas/" . $encode;
        $sha1 = hash_hmac('sha1', $url, self::$secretKey, true);
        $sign = self::$accessKey . ":" . (str_replace($find, $replace, base64_encode($sha1)));

        //saveas/<EncodedEntryURI>/sign/<Sign>
        return $url . "/sign/" . $sign;
    }



    /*
     * 七牛云上传
     * @param string $img_dir 存储的位置
     * @return hybrid
     */
    static public function upload($img_dir){
        Yii::import( 'application.vendors.*' );
        require_once 'qiniu/io.php';
        require_once 'qiniu/rs.php';
        $bucket = "yangzhihua";//镜像名称 例如：yangzhihua.u.qiniudn.com
        $key1 = $img_dir;//文件名全称  可以带文件路径名称为了防止七牛云图片加载不了，加载本地服务器的资源
        $localFile = WEB_ROOT . '/' . $img_dir;//在服务器本地存储完整文件的路径
        $accessKey = '7ASYeHt7yUslxLMsfFJf2U5SFrR1cHohQCR0QuDc';
        $secretKey = 'kdGHD3_HH-IrMP4CDpnhrPGC8UAKJvYlJy0yxlH0';
        Qiniu_SetKeys($accessKey, $secretKey);

        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
        $upToken = $putPolicy->Token(null);


        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;

        list($ret, $err) = Qiniu_PutFile($upToken, $key1, $localFile, $putExtra);
        if ($err !== null) {
            return Util::object_array($err);
        } else {
            return $ret;
        }
    }



    /*
     * 七牛云上传
     * @param string $img_dir 存储的位置
     * @return hybrid
     */
    static public function uploadPfop($save_dir){
        Yii::import( 'application.vendors.*' );
        require_once 'qiniu/io.php';
        require_once 'qiniu/rs.php';
        $bucket = "yangzhihua";//镜像名称 例如：yangzhihua.u.qiniudn.com
        $key1 = $save_dir;//文件名全称  可以带文件路径名称为了防止七牛云图片加载不了，加载本地服务器的资源
        $localFile = WEB_ROOT . '/' . $save_dir;//在服务器本地存储完整文件的路径
        $accessKey = self::$accessKey;
        $secretKey = self::$secretKey;
        Qiniu_SetKeys($accessKey, $secretKey);

        //预处理
        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
        $upToken = $putPolicy->Token(null);
        $putPolicy->Scope = $key1;
        $putPolicy->PersistentOps = 'avthumb/mp3';

        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;

        list($ret, $err) = Qiniu_PutFile($upToken, $key1, $localFile, $putExtra);
        if ($err !== null) {
            return Util::object_array($err);
        } else {
            return $ret;
        }
    }

    /*
     * 此方法就是七牛api文档到处可见的 “HMAC_SHA1” 安全算法 搞得像很高级的样子
     */
    static public function hmac_sha1($data){
        $sign = hash_hmac('sha1', $data, self::$secretKey, true);
        return self::$accessKey . ':' . self::urlsafe_base64_encode($sign);
    }

    /*
     * 此方法就是七牛api文档到处可见的 “URL安全的Base64编码” 算法
     */
    static public function urlsafe_base64_encode($sign){
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($sign));
    }

    /*
     * 七牛云上传
     * @param string
     * @return hybrid
     */
    static public function getUpToken(){
        Yii::import( 'application.vendors.*' );
        require_once 'qiniu/io.php';
        require_once 'qiniu/rs.php';
        $bucket = "yangzhihua";//镜像名称 例如：yangzhihua.u.qiniudn.com
        $accessKey = self::$accessKey;
        $secretKey = self::$secretKey;
        Qiniu_SetKeys($accessKey, $secretKey);

        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
        $saveKey = 'wmatomp3.mp3';
        $savedEntry = Qiniu_Encode("$bucket:$saveKey");
        $putPolicy->PersistentOps = "avthumb/mp3|saveas/$savedEntry";
        $putPolicy->PersistentNotifyUrl = "http://1.chuchujie.sinaapp.com/qiniu.php";
        $upToken = $putPolicy->Token(null);

        return $upToken;
    }




}
