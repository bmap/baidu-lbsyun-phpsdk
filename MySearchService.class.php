<?php
/**
 * LBS�Ƽ������������
 * @author kuangzhijie
 * @version 1.0
 */

if( !defined( 'SDK_ROOT_PATH' ) ) {
	define( 'SDK_ROOT_PATH', dirname( __FILE__ ) );
}	
require_once( SDK_ROOT_PATH . '/lib/BasicService.class.php' );
require_once( SDK_ROOT_PATH . '/constant.php' );

class MySearchService extends MyService {
	/**
	 * �����ؼ���
	 */
	private $_query;
	/**
	 * ɸѡ��ǩ
	 */
	private $_tag;
	/**
	 * �����ֶι�������
	 */
	private $_sort_name = '';
	/**
	 * ����������
	 */
	private $_sort_rule = '';
	/**
	 * ��ֵ�ֶ�ɸѡ����
	 */
	private $_section = array();
	/**
	 * �Ƿ���ʾ��չ��������
	 */
	private $_scope = 1;
	/**
	 * ��ҳ����
	 */
	private $_page_index = 0;
	/**
	 * ��ҳ����
	 */
	private $_page_size = 10;
	/**
	 * �ص�����
	 */
	private $_callback;

	/**
	 * ���ü����ؼ���
	 * @param string(45) $q
	 */
	public function setQuery( $q = '') {
		$this->_query = $q;
	}

	/**
	 * ����ɸѡ��ǩtag
	 * @param string(100) $tag
	 */
	public function setTag( $tag = '' ) {
		$this->_tag = $tag;
	}

	/**
	 * ���������ֶι�������
	 * @param $sort_name
	 */
	public function setSortName( $sort_name = '' ) {
		$this->_sort_name = $sort_name;
	}
	/**
	 * ����������
	 * @param string(50) 0������1������
	 */
	public function setSortRule( $sort_rule = '' ) {
		$this->_sort_rule = $sort_rule;
	}

	/**
	 * ������ֵ�ֶ�ɸѡ����
	 * @param string $name
	 * @param string $lower
	 * @param string $upper
	 */
	public function setSection( $name, $lower, $upper ) {
		if( $name ) {
			$this->_section = array();
		}else{
			if( ( is_string( $lower ) && $lower === '' ) ||
				( is_string( $upper ) && $upper === '' ) )
			{
				$this->_section = array();
			}else{
				$this->_section[ 'name' ] = $name;
				$this->_section[ 'lower' ] = $lower;
				$this->_section[ 'upper' ] = $upper;
			}
		}
	}

	/**
	 * �����Ƿ���ʾ��չ�ֶ�
	 * @param uint32 $scope ö��ֵ��1. ������Ϣ��2. ������Ϣ+��չ��Ϣ
	 */
	public function setScope( $scope = 1 ) {
		$this->_scope = $scope;
	}

	/**
	 * ���÷�ҳ����
	 * @param uint32 $page_index Ĭ�ϣ�0
	 */
	public function setPageIndex( $page_index = 0 ) {
		$this->_page_index = $page_index;
	}

	/**
	 * ���÷�ҳ����
	 * @param uint32 $page_size Ĭ�ϣ�10�����ޣ�50
	 */
	public function setPageSize( $page_size = 10 ) {
		$this->_page_size = $page_size;
	}
	
	/**
	 * ���ûص�����
	 * @param string(20) $callback
	 */
	public function setCallback( $callback = '' ) {
		$this->_callback = $callback;
	}


	/**
	 * �����������Ϊ��ʼ״̬
	 */
	public function reset() {
		$this->_query = '';
		$this->_tag = '';
		$this->_sort_name = '';
		$this->_sort_rule = '';
		$this->_section = array();
		$this->_scope = 1;
		$this->_page_index = 0;
		$this->_page_size = 10;
		$this->_callback = '';
	}


	/**
	 * ʡ�����ڵļ���
	 * ��ӦAPI��{@link api.map.baidu.com/geosearch/poi GET����}
	 * @param string(50) $databox_id
	 * @param string(20) $region ʡ�������ƻ�id���磺����,131
	 * @return �ɹ����ز�ѯ��Ϣ���ṹ���£�
	 *	-type int32 �б����ͣ�1��poi�б�2�������б�
	 *	-size int32 ���η��ؽ����С
	 *	-total int32 �����������
	 *	-content array ����б�
	 *	���򷵻�false��
	 */
	public function searchRegion( $databox_id, $region ) {
		$this->resetErrorMsg();
		$url = $this->host . '/' . $this->name . '/poi';
		$params = $this->_getOpts();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'filter' ] = $this->_formatFilter( $databox_id );
		$params[ 'region' ] = $region;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				unset( $ret[ 'status' ] );
				unset( $ret[ 'message' ] );
				return $ret;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->error_message = 'client error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	

	/**
	 * �ܱ߼���������Բ�ڵļ���
	 * ��ӦAPI��{@link api.map.baidu.com/geosearch/poi GET����}
	 * @param string(50) $databox_id
	 * @param double $lat
	 * @param double $lon
	 * @param uint32 $radius ��λΪ�ף�Ĭ��1000
	 * @return �ɹ����ز�ѯ��Ϣ���ṹ���£�
	 *	-type int32 �б����ͣ�1��poi�б�2�������б�
	 *	-size int32 ���η��ؽ����С
	 *	-total int32 �����������
	 *	-content array ����б�
	 *	���򷵻�false��
	 */
	public function searchNearby( $databox_id, $lat, $lon, $radius = 1000 ) {
		$this->resetErrorMsg();
		$url = $this->host . '/' . $this->name . '/poi';
		$params = $this->_getOpts();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'filter' ] = $this->_formatFilter( $databox_id );
		$params[ 'location' ] = $this->deleteSpace($lat) . ',' . $this->deleteSpace($lon);
		$params[ 'radius' ] = $radius;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				unset( $ret[ 'status' ] );
				unset( $ret[ 'message' ] );
				return $ret;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->error_message = 'client error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ���ο��ڼ���
	 * ��ӦAPI��{@link api.map.baidu.com/geosearch/poi GET����}
	 * @param string(50) $databox_id
	 * @param string $west
	 * @param string $east
	 * @param string $north
	 * @param string $south
	 * @return �ɹ����ز�ѯ��Ϣ���ṹ���£�
	 *	-type int32 �б����ͣ�1��poi�б�2�������б�
	 *	-size int32 ���η��ؽ����С
	 *	-total int32 �����������
	 *	-content array ����б�
	 *	���򷵻�false��
	 */
	public function searchBounds( $databox_id, $west, $east, $north, $south) {
		$this->resetErrorMsg();
		$url = $this->host . '/' . $this->name . '/poi';
		$params = $this->_getOpts();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'filter' ] = $this->_formatFilter( $databox_id );
		$params[ 'bounds' ] = $south . ',' . $west . ';' . $north . ',' . $east;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				unset( $ret[ 'status' ] );
				unset( $ret[ 'message' ] );
				return $ret;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->error_message = 'client error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * ���������������ID�鿴POI��Ϣ
	 * ��ӦAPI��{@link api.map.baidu.com/geosearch/detail GET����}
	 * @param string $poi_id
	 * @param uint32 $scope ö��ֵ��1. ������Ϣ��Ĭ�ϣ���2.������Ϣ+��չ��Ϣ��
	 * @return �ɹ�����poi��Ϣ�������飬���򷵻�false�� 
	 */
	public function searchDetail( $poi_id, $scope = 1 ) {
		$this->resetErrorMsg();
		$url = $this->host . '/' . $this->name . '/detail';
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'id' ] = $poi_id;
		$params[ 'scope' ] = $scope;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return $ret[ 'content' ];
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->error_message = 'client error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * construction
	 */
	public function __construct( $access_key, $secret_key = NULL, $host = NULL ) {
		parent::__construct( $access_key, $secret_key, $host );
		$this->useragent = 'My LBS Search Service';
		$this->name = 'geosearch';
	}



	/**
	 * ��ʽ��filter�ֶ�
	 * @param string(50) $databox_id
	 * @ignore
	 * @return string
	 */
	private function _formatFilter( $databox_id ) {
		$filter = "databox:$databox_id";
		if( $this->_sort_name !== '' ) {
			$filter .= "|sort_name:{$this->_sort_name}";
		}
		if( $this->_sort_rule !== '' ) {
			$filter .= "|sort_rule:{$this->_sort_rule}";
		}
		if( !empty($this->_section) ) {
			$filter .= "|{$this->_section['name']}_section:{$this->_section['lower' ]},{$this->_section['upper']}";
		}
		return $filter;
	}

	/**
	 * ��ȡ��ѡ���������
	 * @return ��ѡ������Ĺ�������
	 */
	private function _getOpts() {
		$opts = array();
		if( $this->_isValid( $this->_query ) ) {
			$opts['q'] = $this->_query;
		}
		if( $this->_isValid( $this->_tag ) ) {
			$opts['tag'] = $this->_tag;
		}
		$opts['scope'] = $this->_scope;
		if( $this->_page_index ) {
			$opts['page_index'] = $this->_page_index;
		}
		if( $this->_page_size ) {
			$opts['page_size'] = $this->_page_size;
		}
		if( $this->_isValid( $this->_callback ) ) {
			$opts['callback'] = $this->_callback;
		}
		return $opts;
	}

	/**
	 * �жϲ����Ƿ���Ч
	 * @return boolean
	 */
	private function _isValid( $str ) {
		if( ( is_string( $str ) && $str !== '' ) ||
			( is_array( $str ) && !$str ) )
		{
			return true;
		}
		return false;
	}
}

?>
