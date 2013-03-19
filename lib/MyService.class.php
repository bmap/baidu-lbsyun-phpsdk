<?php
/**
 * LBS个性化服务基类
 * @author kuangzhijie
 * @version 1.0
 */
if( !defined( 'SDK_LIB_PATH' ) ) {
	define( 'SDK_LIB_PATH', dirname( __FILE__ ) );
}
require_once( SDK_LIB_PATH . '/BasicService.class.php' );

class MyService extends BasicService {
	/**
	 * product name
	 *
	 * @ignore
	 */
	public $name = '';

	/**
	 * need sn in request param
	 * 
	 * @ignore
	 */
	public $need_sign = false;

	/**
	 * the last status returned
	 *
	 * @ignore
	 */
	protected $_status = 0;

	/**
	 * get last status returned
	 *
	 * @return int32
	 */
	public function status() {
		return $this->_status;
	}

	/**
	 * get signature from request params
	 *
	 * @param string $url request url
	 * @param string $http_method http request method
	 * @param array [in/out] &$params
	 * @param array $arrNeed, dafault: array()
	 * @param array $arrExclude, default: array()
	 * @ignore
	 */
	protected function getSign( $url, &$params, $http_method, $arrNeed = array(), $arrExclude = array() ) {
		if( !$params ) {
			throw new BasicServiceException( 'empty params: break point: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__ );
			return false;
		}
		if( !isset( $params[ 'timestamp' ] ) ) {
			$params[ 'timestamp' ] = time();
		}
		$url = str_replace( $this->host, '', $url );
		$arrNeed[] = 'ak';
		$arrContent = array();
		foreach( $arrNeed as $k ) {
			if( isset( $params[ $k ] ) ) {
				$arrContent[ $k ] = urlencode( $params[ $k ] );
			} else {
				throw new BasicServiceException( "lack {$k} param: break point: " . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__ );
			}
		}
		foreach( $params as $k => $v ) {
			if( !in_array( $k, $arrNeed ) && !in_array( $k, $arrExclude ) ) {
				$arrContent[ $k ] = urlencode( $v );
			}
		}
		
		if( $http_method === 'POST' ) {
			ksort( $arrContent );
		}

		$basicString = $url . '?' . http_build_query( $arrContent );
		if( $this->debug ) {
			echo $basicString."\n";
		}
		$basicString .= $this->secret_key;
		$basicString = substr( $basicString, 0, 1000 );
		$sign = md5( urlencode( $basicString ) );
		$params['sn'] = $sign;
	}
}

?>
