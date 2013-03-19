<?php
/**
 * PHP SDK for lbs api
 *
 * @author kuangzhijie
 */
if( !defined( 'SDK_LIB_PATH' ) ) {
	define( 'SDK_LIB_PATH', dirname( __FILE__ ) );
}
require_once( SDK_LIB_PATH . '/BasicHttpClient.class.php' );

/**
 * @ignore
 */
class BasicServiceException extends Exception {
	// nothing
}

/**
 * LBS API PHP SDK 的基类
 *
 * @package lbs
 * @author kuangzhijie
 * @version 1.0
 */
class BasicService {
	/**
	 * @ignore
	 */
	public $access_key;
	/**
	 * @ignore
	 */
	public $secret_key = NULL;
	/**
	 * api root url.
	 *
	 * @ignore
	 */
	public $host = "api.map.baidu.com";
	/**
	 * useragent
	 *
	 * @ignore
	 */
	public $useragent = 'LBS Basic Service v1.0';
	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = false;

	/**
	 * http client object
	 * @ignore
	 */
	public $http_client = NULL;

	/**
	 * http client timeout
	 */
	public $timeout = 30;

	/**
	 * http client connect timeout
	 */
	public $connecttimeout = 5;

	/**
	 * current error message
	 *
	 * @ignore
	 */
	protected $error_message = '';
	/**
	 * contains the last http status code returned.
	 *
	 * @ignore
	 */
	private $_http_code;
	/**
	 * contains the last api call.
	 * 
	 * @ignore
	 */
	private $_url;
	/**
	 * contains the last http headers returned.
	 *
	 * @ignore
	 */
	private $_http_info;
	/**
	 * response format
	 *
	 * @ignore
	 */
	private $_format = 'json';
	/**
	 * decode returned json data
	 *
	 * @ignore
	 */
	private $_decode_json = true;
	/**
	 * boundary of multipart
	 * 
	 * @ignore
	 */
	private $_boundary = '';


	

	/**
	 * set access key
	 * @param string $access_key
	 */
	public function setAccessKey( $access_key ) {
		$this->access_key = $access_key;
	}

	/**
	 * set secret key
	 * @param string $secret_key
	 */
	public function setSecretKey( $secret_key ) {
		$this->secret_key = $secret_key;
	}

	/**
	 * set host
	 * @param string $host
	 */
	public function setHost( $host ) {
		$this->host = $host;
	}

	/**
	 * set http client
	 * @param HttpClient Object $client
	 * @ignore
	 */
	public function setHttpClient( $client ) {
		$this->http_client = $client;
	}
	/**
	 * set format type
	 * @param string $format
	 */
	public function setFormat( $format ) {
		$this->_format = $format;
	}

	/**
	 * get format type
	 * @return string
	 */
	public function getFormat() {
		return $this->_format;
	}

	/**
	 * set weather decode json response data
	 * @param boolean
	 */
	public function setDecodeJson( $enable ) {
		$this->_decode_json = $enable;
	}

	/**
	 * get last http code
	 * @return string
	 */
	public function getLastCode() {
		return $this->_http_code;
	}

	/**
	 * get last http info
	 * @return string
	 */
	public function getLastInfo() {
		return $this->_http_info;
	}

	/**
	 * get last url request
	 * @return string
	 */
	public function getLastURL() {
		return $this->_url;
	}

	/**
	 * get error message
	 * @return string
	 */
	public function getErrorMsg() {
		return $this->error_message;
	}

	/**
	 * reset error message
	 */
	protected function resetErrorMsg() {
		$this->error_message = '';
	}

	/**
	 * create boundary for multipart
	 * 
	 * @ignore
	 * @return boundary string
	 */
	private function _createBoundary() {
		return "---{$this->deleteSpace($this->useragent)}---" . substr(md5(time()), -12);
	}

	/**
	 * delete spaces in a string
	 *
	 * @ignore
	 * @param string $str
	 * @return mix
	 */
	protected function deleteSpace( $str ) {
		if( is_string( $str ) ) {
			$str = str_replace( ' ', '', $str );
		}
		return $str;
	}

	/**
	 * build http query for multipart
	 *
	 * @ignore
	 * @param array $params
	 * @param string $file_key
	 * @param string $content_type
	 * @return string query string
	 */
	protected function buildHttpQueryMulti( $params, $file_key, $content_type = '') {
		if( !$params ) {
			return '';
		}
		$this->_boundary = $this->_createBoundary();
		$MPboundary = '--' . $this->_boundary;
		$endMPboundary = $MPboundary . '--';
		$body = '';

		foreach( $params as $k => $v ) {
			if( $k === $file_key ) {
				$path = $v;
				$content = file_get_contents( $path );
				$filename = basename( $path );

				$body .= $MPboundary . "\r\n";
				$body .= 'Content-Disposition: form-data; name="' . $k . '"; filename="' . $filename . '"' . "\r\n";
				if( $content_type !== '' ) {
					$body .= "Content-Type: {$content_type}\r\n";
				}
				$body .= "\r\n" . $content . "\r\n";
			} else {
				$body .= $MPboundary . "\r\n";
				$body .= 'Content-Disposition: form-data; name="' . $k . '"' . "\r\n\r\n";
				$body .= $v . "\r\n";
			}
		}

		$body .= $endMPboundary;
		return $body;
	}


	/**
	 * send http request
	 * @param string $url
	 * @param string $method 'POST'|'GET'
	 * @param array $params request params
	 * @param boolean $multi weather it support multipart
	 * @param string $file_key uploaded file's key in params array
	 * @param string $content_type file type
	 * @return mix response
	 * @ignore
	 */
	public function http( $url, $params, $method, $multi = false, $file_key = NULL, $content_type = '' ) {
		$this->_http_info = array();
		$headers = array();
		$postfields = '';
		if( !$this->http_client ) {
			$this->http_client = new SimpleHttpClient;
		}
		$this->http_client->useragent = $this->useragent;
		$this->http_client->timeout = $this->timeout;
		$this->http_client->connecttimeout = $this->connecttimeout;
		$response = '';
		switch( $method ) {
		case 'POST':
			if( !empty( $params ) ) {
				if( $multi ) {
					if( $file_key ) {
						$postfields = $this->buildHttpQueryMulti( $params, $file_key, $content_type );
						$headers['Content-Type'] = "multipart/form-data; boundary=" . $this->_boundary;
						// $headers[] = "Content-Type: multipart/form-data; boundary=" . $this->_boundary;
					} else {
						throw new BasicServiceException( 'lack file_key param: break point: ' . __FILE__ . ', ' . __FUNCTION__ . ': ' . __LINE__ );
					}
				} else {
					$headers['Content-Type'] = "application/x-www-form-urlencoded";
					// $headers[] = "Content-Type: application/x-www-form-urlencoded";
					$postfields = http_build_query( $params );
				}
			} else {
				$headers['Content-Type'] = "application/x-www-form-urlencoded";
				// $headers[] = "Content-Type: application/x-www-form-urlencoded";
			}
			$response = $this->http_client->post( $url, $postfields, $headers );
			break;
		case 'GET':
			$url = $url . '?' . http_build_query( $params );
			$response = $this->http_client->get( $url, $headers );
			break;
		}

		if( $this->_format === 'json' && $this->_decode_json ) {
			$response = json_decode( $response, true );
		}
		$this->_http_code = $this->http_client->http_code;
		$this->_http_info = $this->http_client->http_info;
		$this->_url = $url;

		if( $this->debug ) {
			echo "-------------url-------------\r\n";
			var_dump( $url );
			echo "----------postfields---------\r\n";
			var_dump( $postfields);
			echo "-----------headers-----------\r\n";
			print_r( $headers );
			echo "---------request info--------\r\n";
			var_dump( $this->_http_info );
			echo "-----------response----------\r\n";
			var_dump( $response );
		}

		return $response;
		
	}

	public function __construct( $access_key, $secret_key = NULL, $host = NULL ) {
		$this->access_key = $access_key;
		if( $secret_key ) {
			$this->secret_key = $secret_key;
		}
		if( $host ) {
			$this->host = $host;
		}
	}

	/**
	 * 检查字符串长度合法
	 * @param string $str
	 * @param uint32 $min
	 * @param uint32 $max
	 * @return boolean 如果合法返回true，否则返回false
	 */
	protected function checkString( $str, $min, $max ) {
		if( $min > $max ) {
			throw new BasicServiceException( "param error: $min is larger than $max" );
			return false;
		}
		$len = strlen( $str );
		if( $len >= $min && $len <= $max ) {
			return true;
		}
		return false;
	}
	
	/**
	 * 检查必选参数是否存在
	 * @param array $needs
	 * @param array $opts
	 * @return boolean 如果存在返回true，否则返回false
	 */
	protected function checkNeedsExists( $needs, $opts ) {
		foreach( $needs as $key ) {
			if( !isset( $opts[ $key ] ) ) {
				return false;
			}
		}
		return true;
	}
}
?>
