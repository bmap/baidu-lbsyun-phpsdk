<?php
/***************************************************************************
 * 
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file lib/BasicHttpClient.class.php
 * @author kuangzhijie(com@baidu.com)
 * @date 2013/01/11 16:04:55
 * @brief 包含HTTP Client接口与一个可实例化的默认Client类
 *  
 **/

/**
 * Http Client 的抽象类
 */
abstract class BasicHttpClient {
    public $useragent;
    public $timeout;
    public $connectimeout;
    public $http_code;
    public $http_info = array();
    /**
     * Http get request
     */
    abstract public function get( $url, $headers );
    /**
     * Http post request
     */
    abstract public function post( $url, $postfields, $headers );
}


/**
 * 能简单实现的Http Client类，用CURL实现
 */
class SimpleHttpClient extends BasicHttpClient {
    /**
     * curl options
     * @ignore
     */
    public $arr_curl_opt = array();
    public function __construct() {

    }

    /**
     * 默认的初始化
     */
    public function defaultInit( &$curl_handle ) {
        curl_setopt( $curl_handle, CURLOPT_USERAGENT, $this->useragent );
		curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYHOST, true );
		curl_setopt( $curl_handle, CURLOPT_HEADER, false );
		curl_setopt( $curl_handle, CURLOPT_MAXREDIRS, 5);
		curl_setopt( $curl_handle, CURLOPT_FRESH_CONNECT, false );
		curl_setopt( $curl_handle, CURLOPT_FILETIME, true );
		curl_setopt( $curl_handle, CURLOPT_TIMEOUT, $this->timeout );
		curl_setopt( $curl_handle, CURLOPT_CONNECTTIMEOUT, $this->connectimeout );
		curl_setopt( $curl_handle, CURLOPT_NOSIGNAL, true );
    }

    /**
     * Http get request
     * @param string $url
     * @param array $headers
     */
    public function get( $url, $headers ) {
        $curl_handle = curl_init();
        $this->defaultInit( $curl_handle );
        if( !empty( $this->arr_curl_opt ) ) {
            foreach( $this->arr_curl_opt as $k => $v ) {
                curl_setopt( $curl_handle, $k, $v );
            }
        }
        $new_headers = array();
        foreach( $headers as $k => $v ) {
            $new_headers[] = $k . ':' . $v;
        } 
        curl_setopt( $curl_handle, CURLOPT_URL, $url );
        curl_setopt( $curl_handle, CURLOPT_HTTPHEADER, $new_headers );
        curl_setopt( $curl_handle, CURLINFO_HEADER_OUT, true );

        $response = curl_exec( $curl_handle );
        $this->http_code = curl_getinfo( $curl_handle, CURLINFO_HTTP_CODE );
        $this->http_info = array_merge( $this->http_info, curl_getinfo( $curl_handle ) );
        curl_close( $curl_handle );
        return $response;
    }

    /**
     * Http post request
     * @param string $url
     * @param string $postfields
     * @param array $headers
     */
    public function post( $url, $postfields, $headers ) {
        $curl_handle = curl_init();
        $this->defaultInit( $curl_handle );
        if( !empty( $this->arr_curl_opt ) ) {
            foreach( $this->arr_curl_opt as $k => $v ) {
                curl_setopt( $curl_handle, $k, $v );
            }
        }
        $new_headers = array();
        foreach( $headers as $k => $v ) {
            $new_headers[] = $k . ':' . $v;
        } 
        curl_setopt( $curl_handle, CURLOPT_POST, true );
        curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $postfields );
        curl_setopt( $curl_handle, CURLOPT_URL, $url );
        curl_setopt( $curl_handle, CURLOPT_HTTPHEADER, $new_headers );
        curl_setopt( $curl_handle, CURLINFO_HEADER_OUT, true );

        $response = curl_exec( $curl_handle );
        $this->http_code = curl_getinfo( $curl_handle, CURLINFO_HTTP_CODE );
        $this->http_info = array_merge( $this->http_info, curl_getinfo( $curl_handle ) );
        curl_close( $curl_handle );
        return $response;
    }
}



/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
?>
