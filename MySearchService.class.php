<?php
/**
 * LBS云检索服务操作类
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
	 * 检索关键字
	 */
	private $_query;
	/**
	 * 筛选标签
	 */
	private $_tag;
	/**
	 * 排序字段过滤条件
	 */
	private $_sort_name = '';
	/**
	 * 升降序设置
	 */
	private $_sort_rule = '';
	/**
	 * 数值字段筛选区间
	 */
	private $_section = array();
	/**
	 * 是否显示扩展数据设置
	 */
	private $_scope = 1;
	/**
	 * 分页索引
	 */
	private $_page_index = 0;
	/**
	 * 分页数量
	 */
	private $_page_size = 10;
	/**
	 * 回调函数
	 */
	private $_callback;

	/**
	 * 设置检索关键字
	 * @param string(45) $q
	 */
	public function setQuery( $q = '') {
		$this->_query = $q;
	}

	/**
	 * 设置筛选标签tag
	 * @param string(100) $tag
	 */
	public function setTag( $tag = '' ) {
		$this->_tag = $tag;
	}

	/**
	 * 设置排序字段过滤条件
	 * @param $sort_name
	 */
	public function setSortName( $sort_name = '' ) {
		$this->_sort_name = $sort_name;
	}
	/**
	 * 设置升降序
	 * @param string(50) 0：降序，1：升序
	 */
	public function setSortRule( $sort_rule = '' ) {
		$this->_sort_rule = $sort_rule;
	}

	/**
	 * 设置数值字段筛选区间
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
	 * 设置是否显示扩展字段
	 * @param uint32 $scope 枚举值：1. 基本信息。2. 基本信息+扩展信息
	 */
	public function setScope( $scope = 1 ) {
		$this->_scope = $scope;
	}

	/**
	 * 设置分页索引
	 * @param uint32 $page_index 默认：0
	 */
	public function setPageIndex( $page_index = 0 ) {
		$this->_page_index = $page_index;
	}

	/**
	 * 设置分页数量
	 * @param uint32 $page_size 默认：10，上限：50
	 */
	public function setPageSize( $page_size = 10 ) {
		$this->_page_size = $page_size;
	}
	
	/**
	 * 设置回调函数
	 * @param string(20) $callback
	 */
	public function setCallback( $callback = '' ) {
		$this->_callback = $callback;
	}


	/**
	 * 重设检索设置为初始状态
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
	 * 省市区内的检索
	 * 对应API：{@link api.map.baidu.com/geosearch/poi GET请求}
	 * @param string(50) $databox_id
	 * @param string(20) $region 省市区名称或id，如：北京,131
	 * @return 成功返回查询信息，结构如下：
	 *	-type int32 列表类型，1：poi列表。2：城市列表
	 *	-size int32 本次返回结果大小
	 *	-total int32 检索结果总数
	 *	-content array 结果列表
	 *	否则返回false。
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
	 * 周边检索，即方圆内的检索
	 * 对应API：{@link api.map.baidu.com/geosearch/poi GET请求}
	 * @param string(50) $databox_id
	 * @param double $lat
	 * @param double $lon
	 * @param uint32 $radius 单位为米，默认1000
	 * @return 成功返回查询信息，结构如下：
	 *	-type int32 列表类型，1：poi列表。2：城市列表
	 *	-size int32 本次返回结果大小
	 *	-total int32 检索结果总数
	 *	-content array 结果列表
	 *	否则返回false。
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
	 * 矩形框内检索
	 * 对应API：{@link api.map.baidu.com/geosearch/poi GET请求}
	 * @param string(50) $databox_id
	 * @param string $west
	 * @param string $east
	 * @param string $north
	 * @param string $south
	 * @return 成功返回查询信息，结构如下：
	 *	-type int32 列表类型，1：poi列表。2：城市列表
	 *	-size int32 本次返回结果大小
	 *	-total int32 检索结果总数
	 *	-content array 结果列表
	 *	否则返回false。
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
	 * 详情检索，即根据ID查看POI信息
	 * 对应API：{@link api.map.baidu.com/geosearch/detail GET请求}
	 * @param string $poi_id
	 * @param uint32 $scope 枚举值：1. 基本信息（默认）。2.基本信息+扩展信息。
	 * @return 成功返回poi信息关联数组，否则返回false。 
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
	 * 格式化filter字段
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
	 * 获取可选项参数数组
	 * @return 可选项参数的关联数组
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
	 * 判断参数是否有效
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
