<?php
/**
 * urlsafe_base64_encode
 *
 * @desc URL安全的Base64编码
 * @param string $str
 * @return string
 */
function urlsafe_base64_encode($str){
    $find = array("+","/");
    $replace = array("-", "_");
    return str_replace($find, $replace, base64_encode($str));
}

/**
 * generate_token
 *
 * @desc 根据凭证原始数据生成凭证
 * @param string $access_key
 * @param string $secret_key
 * @param string $data
 * @return string
 */
function generate_token($access_key, $secret_key, $data){

    $digest = hash_hmac('sha1', $data, $secret_key, true);
    return $access_key.':'.urlsafe_base64_encode($digest);
}


function dump( $var, $echo = true, $label = null, $strict = true ) {
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