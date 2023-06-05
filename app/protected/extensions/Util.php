<?php
	/**
	* 封装实用类，字符串utf8截取等
	*/
class Util
{



        const CONNECTTIMEOUT = 60;
        const TIMEOUT = 60;

    /**
     * 获取客户端IP
     *
     * @return string
     */
    public static function getClientIp()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function getRandom($len=6,$format='ALL')
    {
        switch($format) {
        case 'ALL':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; break;
        case 'CHAR':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; break;
        case 'NUMBER':
            $chars='0123456789'; break;
        default :
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
        }
        mt_srand((double)microtime()*1000000*getmypid());
        $password="";
        while(strlen($password)<$len)
            $password.=substr($chars,(mt_rand()%strlen($chars)),1);
        return $password;
    }


    public static function weightedRandom($choice_map)
    {
        $count = count($choice_map);
        $values = array();
        $weights = array();
        foreach($choice_map as $value=>$weight)
        {
            if(intval($weight) == 0)
                continue;
            $values []= $value;
            $weights []= intval($weight);
        }

        if(count($values)==0)
            return false;

        $i = 0;
        $n = 0;
        $num = mt_rand(1, array_sum($weights));
        while($i < $count)
        {
            $n += $weights[$i];
            if($n >= $num)
            {
                break;
            }
            $i++;
        }
        return $values[$i];
    }

    /**
     * PHP获取字符串中英文混合长度
     * @param $str string 字符串
     * @param $$charset string 编码
     * @return 返回长度，1中文=1位，2英文=1位
     */
    public static function strLength($str,$charset='utf-8'){
        if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
        $num = strlen($str);
        $cn_num = 0;
        for($i=0;$i<$num;$i++){
            if(ord(substr($str,$i+1,1))>127){
                $cn_num++;
                $i++;
            }
        }
        $en_num = $num-($cn_num*2);
        $number = ($en_num/2)+$cn_num;
        return ceil($number);
    }

    /**
     * 时间戳转成字符串
     *
     */
    public static function timeToStr($timestamp=null)
    {
        if($timestamp === null)
            $timestamp = time();
        return strftime("%Y-%m-%d %T", $timestamp);
    }

	/*
	* 计算星座的函数 string getConstellation(string $date)
	* 输入：年月日
	* 输出：星座名称或者错误信息
	*/
	public static function getConstellation($date)
	{
		$month = substr($date,5,2); //取出月份
		$day = substr($date,8,2); //取出日期
		// 检查参数有效性
		if ($month < 1 || $month > 12 || $day < 1 || $day > 31)
		    return (false);
		// 星座名称以及开始日期
		$signs = array(
		    array( "20" => "水瓶座"),
		    array( "19" => "双鱼座"),
		    array( "21" => "白羊座"),
		    array( "20" => "金牛座"),
		    array( "21" => "双子座"),
		    array( "22" => "巨蟹座"),
		    array( "23" => "狮子座"),
		    array( "23" => "处女座"),
		    array( "23" => "天秤座"),
		    array( "24" => "天蝎座"),
		    array( "22" => "射手座"),
		    array( "22" => "摩羯座")
		    );
	   	list($signStart, $signName) = each($signs[(int)$month-1]);
		if ($day < $signStart)
		list($signStart, $signName) = each($signs[($month -2 < 0) ? $month = 11: $month -= 2]);
		    return $signName;
    }

    public static function getExtName($file)
    {
        $pos = strrpos($file, ".");
        if($pos === false)
            return false;
        return strtolower(substr($file,$pos+1));
    }

    public static function getDataFromUrl($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CONNECTTIMEOUT);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
        $data =  curl_exec($ch);
        curl_close($ch);
        return $data;

    }
    
    public static function timeToMinute($time)
    {
        
     	
        $minute = ceil(((time()-strtotime($time))/60));
        if($minute<60)
        {
            if($minute==0)
                $minute=1;
            return $minute.'分钟之前';
 
        }
        else
        {
            return  $time;
        }
        	
        
        
    }

    /**
     * 友好显示var_dump
     */
    static public function dump( $var, $echo = true, $label = null, $strict = true ) {
        $label = ( $label === null ) ? '' : rtrim( $label ) . ' ';
        if ( ! $strict ) {
            if ( ini_get( 'html_errors' ) ) {
                $output = print_r( $var, true );
                $output = "<pre>" . $label . htmlspecialchars( $output, ENT_QUOTES ) . "</pre>";
            } else {
                $output = $label . print_r( $var, true );
            }
        } else {
            ob_start();
            var_dump( $var );
            $output = ob_get_clean();
            if ( ! extension_loaded( 'xdebug' ) ) {
                $output = preg_replace( "/\]\=\>\n(\s+)/m", "] => ", $output );
                $output = '<pre>' . $label . htmlspecialchars( $output, ENT_QUOTES ) . '</pre>';
            }
        }
        if ( $echo ) {
            echo $output;
            return null;
        } else
            return $output;
    }

    /*
     * 统一规范：返回给前端成功状态的json数据
     */
    static public function throwSuccessJson($info='', $data=array()){
        $jsonData  = array();
        $jsonData['status'] = 1;
        $jsonData['_data'] = $data;
        $jsonData['info'] = $info;
        return CJSON::encode($jsonData);
    }

    /*
     * 统一规范：返回给前端失败状态的json数据
     */
    static public function throwErrorJson($info=''){
        $jsonData  = array();
        $jsonData['status'] = 0;
        $jsonData['info'] = $info;
        return CJSON::encode($jsonData);
    }

    static public function object_array($array) {
        if (is_object($array)) {
            $array = (array) $array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::object_array($value);
            }
        }
        return $array;
    }

    /*
     * curl POST函数 （需服务器开启curl扩展）
     * @param string $url  要发起POST请求的URL
     * @$postdata array 要发送的数组
     * @return hybrid
     */
    static public function curl_post($url, $postdata = array()){
        if(empty($url)){
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!empty($postdata)){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11");
        $tmpcontent = curl_exec($ch);
        curl_close($ch);
        if ($tmpcontent === "false") {
            return false;
        } else {
            return $tmpcontent;
        }
    }


}

