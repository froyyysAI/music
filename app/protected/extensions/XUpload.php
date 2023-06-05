<?php
/**
 * 文件上传
 *
 */

class XUpload {

    /*
     * 生成thumb 保存的文件夹
     */
    protected static function _makeThumbDir($path){
        if(!is_dir($path)) {
            mkdir($path,0777,true);
        }
    }

    private static function _savePath( $params = array( 'rule'=>'default', 'format'=>'Ymd' ) ) {
        $path = '';
        switch ( $params['rule'] ) {
            case 'custom':
                $path .= $params['string'] . '/';
                break;
            case 'user':
                isset( $params['userPath'] ) && $path .= $params['userPath'] . '/';
                isset( $params['userId'] ) && $path .= $params['userId'] . '/';
                isset( $params['format'] ) && $path .= date( $params['format'] ) . '/';
                break;
            default:
                $paths = isset( $params['format'] ) ? date( $params['format'] ) . '/' : date( 'Ym' ) . '/';
                $path .= $paths;
                break;
            }
        return  'uploads/' . $path;
    }

    private static function _setFileName( $params = array( 'saveRule'=>'default') ) {
        $saveRule = isset($params['saveRule']) ? $params['saveRule'] : 'default';
        switch ( $saveRule ) {
            case 'md5':
                $fileName = md5(uniqid());
                break;
            case 'date':
                $fileName = date("Ymd") . time() . mt_rand(1000, 9999);
                break;
            default:
                $fileName = md5(time().mt_rand(5,15));
                break;
        }
        return $fileName;
    }

   /**
     * 单个文件上传
     * @param array $fileFields $_FILES['filename']
     * @param array  $input 上传类参数
     * @return hybrid success array   fail string
     */
    static public function upload( $fileFields, $input = false) {
        $default =  array( 'thumb'=>false, 'thumbSize' => array( 400, 400 ), 'allowExts' => array('jpg', 'gif', 'png', 'jpeg'), 'maxSize' => 1024*1024*1024, 'is_water'=>false );
        $params = is_array($input) ? array_merge($default, $input) : $default ;
        Yii::import( 'application.vendors.*' );
        require_once 'Tp/UploadFile.class.php';
        $upload = new UploadFile();
        // 设置上传文件大小
        $upload->maxSize = $params['maxSize'];
        // 设置上传文件类型
        $upload->allowExts = $params['allowExts'];
        // 设置附件上传目录
        $upload->savePath = self::_savePath();
        if($params['thumb'] === true){
            $thumbSavePath = $upload->savePath . 'thumb/';
            // 设置需要生成缩略图，仅对图像文件有效
            $upload->thumb = true;
            //判断有没有缩略图目录，没：就生产
            self::_makeThumbDir($thumbSavePath);
            //设置缩略图文件名前缀为空
            $upload->thumbPrefix = '';
            // 设置需要生成缩略图的文件后缀
            $upload->thumbPath = $thumbSavePath; // 生产2张缩略图
            // 设置缩略图最大宽度
            $upload->thumbMaxWidth = $params['thumbSize'][0];
            // 设置缩略图最大高度
            $upload->thumbMaxHeight = $params['thumbSize'][1];
        }
        // 设置上传文件的保存规则
        $upload->saveRule = self::_setFileName($params);
        // 生成缩略图后是否删除原图
        $upload->thumbRemoveOrigin = false;
        $file = $upload->uploadOne( $fileFields );

        if ( ! is_array( $file ) ) {
            return $upload->getErrorMsg();
        } else {
            // 重新整理返回数据
            $fileget['name'] = $file[0]['name'];
            $fileget['type'] = $file[0]['type'];
            $fileget['size'] = $file[0]['size'];
            $fileget['extension'] = $file[0]['extension'];
            $fileget['savepath'] = $file[0]['savepath'];
            $fileget['savename'] = $file[0]['savename'];
            $fileget['hash'] = $file[0]['hash'];
            $fileget['pathname'] = $upload->savePath . $file[0]['savename'];
            // 缩略图返回
            if ( true == $upload->thumb ) {
                $fileget['thumb'] = $thumbSavePath . $file[0]['savename'];
                $fileget['paththumbname'] = $thumbSavePath . $file[0]['savename'];
            }
            if ($params['is_water'] === true) {
                require_once 'Tp/Image.class.php';
                Image::water( $fileget['pathname'], './static/water.png', null, 80);
            }
            return $fileget;
        }

    }

    /**
     * 多文件上传
     *
     * @param boolean $thumb [description]
     * @return [type]         [description]
     */
    static public function uploads( $input = false) {
        $default =  array( 'thumb'=>false, 'thumbSize' => array( 400, 400 ), 'allowExts' => array('jpg', 'gif', 'png', 'jpeg'), 'maxSize' => 3292200 );
        $params = is_array($input) ? array_merge($default, $input) : $default ;
        Yii::import( 'application.vendors.*' );
        require_once 'Tp/UploadFile.class.php';
        $upload = new UploadFile();
        // 设置上传文件大小
        $upload->maxSize = $params['maxSize'];
        // 设置上传文件类型
        $upload->allowExts = $params['allowExts'];
        // 设置附件上传目录
        $upload->savePath = self::_savePath();
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = $params['thumb'];
        // 设置需要生成缩略图的文件后缀
        $upload->thumbPrefix = 'thumb_'; // 生产2张缩略图
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = $params['thumbSize'][0];
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = $params['thumbSize'][1];
        // 设置上传文件规则  默认规则：uniqid
        //$upload->saveRule = uniqid;
        // 删除原图
        $upload->thumbRemoveOrigin = false;

        if ( ! $upload->upload() ) {
            return $upload->getErrorMsg();
        } else {
            $fileinfo = $upload->getUploadFileInfo();
            foreach ( $fileinfo as $key => $row ) {
                if ( true == $upload->thumb )
                    $fileinfo[$key]['thumb'] = $upload->thumbPrefix . $fileinfo[$key]['savename'];
                $fileinfo[$key]['pathname'] = $upload->savePath . $fileinfo[$key]['savename'];
                $fileinfo[$key]['paththumbname'] = $upload->savePath . $upload->thumbPrefix . $fileinfo[$key]['savename'];

            }
            return $fileinfo;
        }
    }
}
