<?php
/**
 * LBS�ƴ洢������
 * @package lbs
 * @author kuangzhijie
 * @version 1.0
 */
if( !defined( 'SDK_ROOT_PATH' ) ) {
	define( 'SDK_ROOT_PATH', dirname( __FILE__ ) );
}
require_once( SDK_ROOT_PATH . '/lib/MyService.class.php' );
require_once( SDK_ROOT_PATH . '/constant.php' );

class MyStorageService extends MyService {
	/**
	 * ����databox
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databox?method=create POST����}
	 * @param string $databox_name
	 * @param int32 $geotype ö��ֵ��GEOTYPE_POINT_POI(default) | GEOTYPE_LINE_POI | GEOTYPE_FLAT_POI
	 * @return ����ɹ���������databox��id�����򷵻�false;
	 */
	public function createDatabox( $databox_name, $geotype = GEOTYPE_POINT_POI ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databox';
		$params = array();
		$params[ 'name' ] = $databox_name;
		$params[ 'ak' ] = $this->access_key;
		$params[ 'geotype' ] = $geotype;
		$params[ 'method' ] = 'create';
		$http_method = 'POST';
		if($this->need_sign) {
			$this->getSign( $url, $params, $http_method, array( 'name' ) );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'id' ];
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		$this->_status = CLIENT_ERROR;
		return false;
	}
	/**
	 * ����databox
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databox/{id}?method=publish
	 * @param string $databox_id
	 * @param boolean $search �Ƿ񷢲���������ֵΪ0��1
	 * @param boolean $bus �Ƿ񷢲���������ֵΪ0��1
	 */
	public function publishDatabox( $databox_id, $search = 0, $bus = 0 ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databox/' . $this->deleteSpace($databox_id);
		$params = array();
		$params[ 'publish_search' ] = $search;
		$params[ 'publish_bus' ] = $bus;
		$params[ 'name' ] = $databox_name;
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'publish';
		$http_method = 'POST';
		if($this->need_sign) {
			$this->getSign( $url, $params, $http_method, array( 'name' ) );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		$this->_status = CLIENT_ERROR;
		return false;
	}


	/**
	 * �޸�databox
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databox/{id}?method=update POST����}
	 * @param string $id
	 * @param string(45) $new_name
	 * @return ��ȷ����true�����򷵻�false��
	 */
	public function updateDatabox($id, $new_name) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databox/' . $this->deleteSpace( $id );
		$params = array();
		$params[ 'name' ] = $new_name;
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'update';
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method, array( 'name' ) );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ɾ��databox��Ϣ
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databox/{id}?method=delete POST����}
	 * @param string $id
	 * @return ��ȷ����true�����򷵻�false��
	 */
	public function deleteDatabox($id) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databox/' . $this->deleteSpace( $id );
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'delete';
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ��ȡ����databox��Ϣ
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databox/{id}? GET����}
	 * @param string $id
	 * @param string $scope ö��ֵ��SCOPE_BASIC(default) | SCOPE_DETAIL
	 * @return �ɹ�������Ϣ�������飬���򷵻�false��
	 */
	public function getDatabox($id, $scope = SCOPE_BASIC) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databox/' . $this->deleteSpace( $id );
		$params = array();
		$params[ 'scope' ] = $scope;
		$params[ 'ak' ] = $this->access_key;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				unset( $ret[ 'status' ] );
				unset( $ret[ 'message' ] );
				return $ret;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}



	/**
	 * ������ѯdatabox
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databox?method=list GET����}
	 * @param string(45) $name
	 * @param uint32 $page_index ��ѯ����ҳ��ţ�Ĭ�ϣ�0
	 * @param uint32 $page_size ��ѯ����ҳ��С��Ĭ�ϣ�10��������1000��
	 * @return �ɹ����ع�������ṹ���£�
	 *	-size uint32 ���ݸ���
	 *	-total uint32 ��������
	 *	-databox array databox�б�
	 *	���򷵻�false��
	 */
	public function listDatabox( $name = NULL, $page_index = 0, $page_size = 10) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databox';
		$params = array();
		if( $name ) {
			$params[ 'name' ] =$name;
		}
		$params[ 'page_index' ] = $this->deleteSpace( $page_index );
		$params[ 'page_size' ] = $this->deleteSpace( $page_size );
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'list';
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ] ;
			if( $ret[ 'status' ] === 0 ) {
				unset( $ret['status'] );
				unset( $ret['message'] );
				return $ret;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * ������ѯdatabox��������ȫ�����
	 * @param string(45) $name
	 * @return �ɹ��򷵻�databox�б����򷵻�false��
	 */
	public function listDataboxAll( $name = NULL ) {
		$this->resetErrorMsg();
		$arr_databox = array();
		$page_size = 1000;
		$page_index = 0;
		while( true ) {
			$ret = $this->listDatabox( $name, $page_index, $page_size );
			if( $ret === false ) {
				return false;
			}
			foreach( $ret['databox'] as $databox ) {
				$arr_databox[] = $databox;
			}
			if( $page_size !== count( $ret ) ) {
				break;
			}
			$page_index++;
		}
		return $arr_databox;
	}


	/**
	 * ����databox_meta
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databoxmeta?method=create POST����}
	 * @param string(45) $property_name
	 * @param string(45) $property_key
	 * @param uint32 $property_type ö��ֵ��PROPERTY_INT32 | PROPERTY_INT64 | PROPERTY_FLOAT | PROPERTY_DOUBLE | PROPERTY_STRING
	 * @param string(50) $databox_id
	 * @param boolean $if_magic_field �Ƿ�Ϊ�����ֶΣ�ֵΪ0��1
	 * @return �ɹ���������databox_meta��id���飬���򷵻�false��
	 */
	public function createDataboxMeta( $property_name, $property_key, $property_type, $databox_id, $if_magic_field = NULL ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databoxmeta';
		$params = array();
		if( is_array( $property_name ) ) {
			$property_name = implode( ',', $property_name );
		}
		$params[ 'property_name' ] = $property_name;
		if( is_array( $property_key ) ) {
			$property_key = implode( ',', $property_key );
		}
		$params[ 'property_key' ] = $property_key;
		if( is_string( $property_type ) ) {
			$params[ 'property_type' ] = $this->deleteSpace( $property_type );
		} else if( is_array( $property_type ) ) {
			$tmp = implode( ',', $property_type );
			$params[ 'property_type' ] = $this->deleteSpace( $tmp );
		}
		if( is_string( $if_magic_field ) ) {
			$params[ 'if_magic_field' ] = $this->deleteSpace( $if_magic_field );
		} else if( is_array( $if_magic_field ) ) {
			$tmp = implode( ',', $if_magic_field );
			$params[ 'if_magic_field' ] = $this->deleteSpace( $tmp );
		}
		$params[ 'databox_id' ] = $databox_id;
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'create';
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ] ;
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'ids' ];
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * �������databox_meta
	 * @param array $property_name_set
	 * @param array $property_key_set
	 * @param array $property_type_set ö��ֵ��PROPERTY_INT32 | PROPERTY_INT64 | PROPERTY_FLOAT | PROPERTY_DOUBLE | PROPERTY_STRING
	 * @param boolean $if_magic_field_set �Ƿ�Ϊ�����ֶΣ�ֵΪ0��1
	 * @param string(50) $databox_id
	 * @return �ɹ���������databox_meta��id(���ܵ�)���飬���򷵻�false��
	 */
	public function createDataboxMetaSet( $property_name_set, $property_key_set, $property_type_set, $databox_id, $if_magic_field_set = array() ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databoxmeta';
		$params = array();
		$property_name = '';
		$property_type = '';
		$property_key = '';
		$if_magic_field = 0;
		$max_meta_num = 100;
		if( is_array( $property_name_set ) ) {
			$property_name = implode( ',', $property_name_set );
		} else {
			throw new BasicServiceException("$property_name_set is not an array, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
			return false;
		}
		if( is_array( $property_key_set ) ) {
			$property_key = implode( ',', $property_key_set );
		} else {
			throw new BasicServiceException("$property_key_set is not an array, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
			return false;
		}
		$params[ 'property_key' ] = $property_key;
		if( is_array( $property_type_set ) ) {
			$tmp = implode( ',', $property_type_set );
			$property_type = $this->deleteSpace( $tmp );
		} else {
			throw new BasicServiceException("$property_type_set is not an array, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
			return false;
		}
		if( !empty( $if_magic_field_set ) ) {
			if( is_array( $if_magic_field_set ) ) {
				$tmp = implode( ',', $if_magic_field_set );
				$if_magic_field = $this->deleteSpace( $tmp );
			} else {
				throw new BasicServiceException("$if_magic_field_set is not an array, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
				return false;
			}
		}
		$name_count = count( $property_name_set );
		$key_count = count( $property_key_set );
		$type_count = count( $property_type_set );
		if( $name_count !== $key_count || $key_count !== $type_count || $name_count !== $type_count ) {
			throw new BasicServiceException("array's counts are not the same, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
			return false;
		}
		if( !empty( $if_magic_field_set ) ) {
			if( $name_count !== count( $if_magic_field_set ) ) {
				throw new BasicServiceException("array's counts are not the same, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
				return false;
			} 
		}
		$meta_count = $name_count;
		$params[ 'databox_id' ] = $databox_id;
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'create';
		$http_method = 'POST';
		$ids = array();
		if( $meta_count <= $max_meta_num ) {
			$params[ 'property_name' ] = $property_name;
			$params[ 'property_key' ] = $property_key;
			$params[ 'property_type' ] = $property_type;
			if( !$if_magic_field ) {
				$params[ 'if_magic_field' ] = $if_magic_field;
			}
			if( $this->need_sign ) {
				$this->getSign( $url, $params, $http_method );
			}
			$ret = $this->http( $url, $params, $http_method);
			if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
				$this->_status = $ret[ 'status' ] ;
				if( $ret[ 'status' ] === 0 ) {
					return $ret[ 'ids' ];
				}
				$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
				return false;
			}
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * �޸�databox_meta
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databoxmeta/{id}?method=update POST����}
	 * @param string $id databox_meta's id
	 * @param string(45) $new_property_name
	 * @param boolean $if_magic_field Ĭ��false;
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function updateDataboxMeta( $id, $new_property_name, $if_magic_field = false ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databoxmeta/' . $this->deleteSpace($id);
		$params = array();
		$params[ 'property_name' ] = $new_property_name;
		$params[ 'if_magic_field' ] = $if_magic_field;
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'update';
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ] ;
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * ɾ��databox_meta
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databoxmeta/{id}?method=delete POST����}
	 * @param string $id databox_meta's id
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function deleteDataboxMeta( $id ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databoxmeta/' . $this->deleteSpace($id);
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'delete';
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ] ;
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ��ȡdatabox_meta��Ϣ
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databoxmeta/{id}? GET����}
	 * @param string $id databox_meta's id
	 * @return �ɹ�����databox_meta����Ϣ�����󷵻�false��
	 */
	public function getDataboxMeta( $id ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databoxmeta/' . $this->deleteSpace($id);
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'databox_meta' ];
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	

	/**
	 * ������ѯdatabox_meta
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/databoxmeta?method=list GET����}
	 * @param string(50) $databox_id
	 * @param string(45) $property_name ��ѡ
	 * @param uint32 $property_key ��ѡ
	 * @return �ɹ�����databox_meta�б����򷵻�false��
	 */
	public function listDataboxMeta( $databox_id, $property_name = NULL, $property_key = NULL ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/databoxmeta';
		$params = array();
		$params[ 'databox_id' ] = $databox_id;
		if( $property_name ) {
			$params[ 'property_name' ] = $property_name;
		}
		if( $property_key ) {
			$params[ 'property_key' ] = $property_key;
		}
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'list';
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method);
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'databox_meta' ];
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * ����POI
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poi?method=create POST����}
	 * @param array $opts ������POI�Ĳ����Ĺ�������
	 *	-name string(100) ��ѡ��poi����
	 *	-original_lat double ��ѡ��γ��
	 *	-original_lon double ��ѡ������
	 *	-original_coord_type ��ѡ��ö��ֵ��NATION_ENCRYPT(����ּ���) | BAIDU_ENCRYPT(�ٶȼ���) | NO_ENCRYPT(ԭʼGPS����)
	 *	-databox_id string(50) ��ѡ
	 *	-address string(200) ��ѡ
	 *	-telephone string(45) ��ѡ
	 *	-zip_code string(16) ��ѡ
	 *	-poi_tag string(1000)/array ��ѡ��poi��ǩ
	 *	-poi_importance uint32 ��ѡ��ȡֵ��1-255�䣬Ĭ��Ϊ127
	 *	-category_id uint32 ��ѡ��poi�����id
	 *	-publish_search boolean ��ѡ��0��1��Ĭ��Ϊ0
	 *	-publish_bus boolean ��ѡ��0��1��Ĭ��Ϊ0
	 *	-geo_sequence string(500k) ��ѡ����Ϊ����poiʱ�����벻Ϊ��
	 *	-icon string(100) ��ѡ��poi��ͼ��
	 *	-baidu_uid uint64 ��ѡ���ٶȵ�poiuid
	 * @return �ɹ���������POI��id�����򷵻�false��
	 */
	public function createPoi( $opts ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poi';
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'create';
		$arrNeeds = array( 'name', 'original_lat', 'original_lon', 'original_coord_type', 'databox_id' );
		if( !$this->checkNeedsExists( $arrNeeds, $opts ) ) {
			$this->error_message = 'client error: params(' . implode( ',', $arrNeeds ) . ') is in need. ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			$this->_status = CLIENT_ERROR;
			return false;
		}
		if( isset( $opts[ 'poi_tag' ] ) && is_array( $opts[ 'poi_tag' ] ) ) {
			$opts[ 'poi_tag' ] = implode( ' ', $poi_tag );
		}
		foreach( $opts as $k => $v ) {
			$params[ $k ] = $v;
		}
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'id' ];
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	

	/**
	 * �޸�POI
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poi/{id}?method=update POST����}
	 * @param string $poi_id
	 * @param array $opts ������POI�Ĳ����Ĺ�������
	 *	-name string(100) ��ѡ��poi����
	 *	-original_lat double ��ѡ��γ�ȣ����ȴ���ʱ��γ��Ҳ�������
	 *	-original_lon double ��ѡ�����ȣ�γ�ȴ���ʱ������Ҳ�������
	 *	-original_coord_type ��ѡ��ö��ֵ��NATION_ENCRYPT(����ּ���) | BAIDU_ENCRYPT(�ٶȼ���) | NO_ENCRYPT(ԭʼGPS����)
	 *	-address string(200) ��ѡ
	 *	-telephone string(45) ��ѡ
	 *	-zip_code string(16) ��ѡ
	 *	-poi_tag string(1000)/array ��ѡ��poi��ǩ
	 *	-poi_importance uint32 ��ѡ��ȡֵ��1-255�䣬Ĭ��Ϊ127
	 *	-category_id uint32 ��ѡ��poi�����id
	 *	-publish_search boolean ��ѡ��0��1��Ĭ��Ϊ0
	 *	-publish_bus boolean ��ѡ��0��1��Ĭ��Ϊ0
	 *	-geo_sequence string(500k) ��ѡ����Ϊ����poiʱ�����벻Ϊ��
	 *	-icon string(100) ��ѡ��poi��ͼ��
	 *	-baidu_uid uint64 ��ѡ���ٶȵ�poiuid
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function updatePoi( $poi_id, $opts = array() ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poi/' . $this->deleteSpace( $poi_id );
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'update';
		if( isset( $opts[ 'original_lat' ] ) && !isset( $opts[ 'original_lon' ] ) ||
			isset( $opts[ 'original_lon' ] ) && !isset( $opts[ 'original_lat' ] ) )
		{
			$this->error_message = 'client error: original_lat and orignal_lon params must exist or not together. ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			$this->_status = CLIENT_ERROR;
			return false;
		}
		if( !$this->checkNeedsExists( $arrNeeds, $opts ) ) {
			$this->error_message = 'client error: params(' . implode( ',', $arrNeeds ) . ') is in need. ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			$this->_status = CLIENT_ERROR;
			return false;
		}
		if( isset( $opts[ 'poi_tag' ] ) && is_array( $opts[ 'poi_tag' ] ) ) {
			$opts[ 'poi_tag' ] = implode( ' ', $poi_tag );
		}
		foreach( $opts as $k => $v ) {
			$params[ $k ] = $v;
		}
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return true;
			}	
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}



	/**
	 * ɾ��һ��POI
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poi/{id}?method=delete POST����}
	 * @param string $poi_id
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function deletePoi( $poi_id ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poi/' . $this->deleteSpace($poi_id);
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'delete';
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ɾ�����POI
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poi?method=delete POST����}
	 * @param array $ids Ҫɾ����poi��id�����ַ���ʱ���ð�Ƕ��Ÿ�����
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function deletePoiSet( $ids ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poi';
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'delete';
		if ( is_array( $ids ) ) {
			$params[ 'ids' ] = implode( ',', $ids );
		} else {
			throw new BasicServiceException("$ids is not an array, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
			return false;
		}
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return true;
			}
			if( $this->_status === 2 ) {
				foreach( $ids as $id ) {
					$params[ 'ids' ] = $id;
					if( $this->need_sign ) {
						$this->getSign( $url, $params, $http_method );
					}
					$ret = $this->http( $url, $params, $http_method );
					$this->status = $ret[ 'status' ];
					if( $this->status !== 0 ) {
						break;
					}
				}
				if( $this->status === 0 ) {
					return true;
				}
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ��ѯ����POI
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poi/{id} GET����}
	 * @param string $poi_id
	 * @param uint32 $scope ö��ֵ��SCOPE_BASIC(default) | SCOPE_DETAIL
	 * @return �ɹ�����poi��Ϣ�������飬���򷵻�false��
	 */
	public function getPoi( $poi_id, $scope = SCOPE_BASIC ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poi/' . $this->deleteSpace( $poi_id );
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'scope' ] = $scope;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'poi' ];
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * ����id�����ѯ���POI
	 * @param array $poi_ids
	 * @param uint32 $scope ö��ֵ��SCOPE_BASIC(default) | SCOPE_DETAIL
	 * @return �ɹ�����poi�������顣
	 */
	public function listPoiByIds( $poi_ids, $scope = SCOPE_BASIC ) {
		$this->resetErrorMsg();
		$error_message = '';
		$arr_poi = array();
		if( !is_array( $poi_ids ) ) {
			throw new BasicServiceException("$poi_ids is not an array, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
			return false;
		}
		foreach( $poi_ids as $poi_id ) {
			$ret = $this->getPoi( $poi_id, $scope );
			if( $ret === false ) {
				$error_message .= 'poi_id: ' . $poi_id . "\t" . 'status: ' . $this->_status . "\n" . $this->error_message . "\n";
			}else{
				$arr_poi[] = $ret;
			}
		}
		if( count( $arr_poi ) !== count( $arr_poi ) ) {
			$this->error_message = $error_message;
		}
		return $arr_poi;
		
	}


	/**
	 * ������ѯPOI
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poi?method=list GET����}
	 * @param string(50) $databox_id
	 * @param array $arr_opts
	 *	-name string poi's name
	 *	-poi_tag string ֻ֧��һ����ǩ
	 *	-poi_importance uint32
	 *	-bounds string(100) ��ʽ��x1,y1;x2,y2�ֱ����������ϽǺ����½�
	 *	-province_id uint32
	 *	-province string(20)
	 *	-city_id uint32
	 *	-city string(20)
	 *	-district string(20)
	 *	-start_date string(10) ��ʽ��YY-MM-DD
	 *	-end_date string(10) ��ʽ��YY-MM-DD
	 *	-page_index uint32 Ĭ�ϣ�0
	 *	-page_size uint32 Ĭ�ϣ�10�����ޣ�1000
	 * @return �ɹ�����poi�б����飬���򷵻�false��
	 */
	public function listPoi( $databox_id, $arr_opts = NULL ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poi';
		$params = array();
		if( $arr_opts ) {
			foreach( $arr_opts as $k => $v ) {
				if( $v !== '' ) {
					if( is_string( $v ) ) {
						$params[ $k ] = $this->deleteSpace( $v );
					}else if( is_integer( $v ) ) {
						if( $v < 0 ) {
							throw new BasicServiceException( "{$k}'s data type should be uint break point: " . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__ );
							return;
						} else {
							$params[ $k ] = $v;
						}
					}
				}
			}
		}
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'list';
		$params[ 'databox_id' ] = $databox_id;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				unset( $ret['status'] );
				unset( $ret['message'] );
				return $ret;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
		
	}

	/**
	 * ������ѯPoi���������н��
	 * @param string(50) $databox_id
	 * @param array $arr_opts
	 *	-name string poi's name
	 *	-poi_tag string ֻ֧��һ����ǩ
	 *	-poi_importance uint32
	 *	-bounds string(100) ��ʽ��x1,y1;x2,y2�ֱ����������ϽǺ����½�
	 *	-province_id uint32
	 *	-province string(20)
	 *	-city_id uint32
	 *	-city string(20)
	 *	-district string(20)
	 *	-start_date string(10) ��ʽ��YY-MM-DD
	 *	-end_date string(10) ��ʽ��YY-MM-DD
	 * @return �ɹ�����poi�б����飬���򷵻�false��
	 */
	public function listPoiAll( $databox_id, $arr_opts = NULL ) {
		$this->resetErrorMsg();
		$page_size = 1000;
		$page_index = 0;
		$arr_poi = array();
		while( true ) {
			$arr_opts[ 'page_size' ] = $page_size;
			$arr_opts[ 'page_index' ] = $page_index;
			$ret = $this->listPoi( $databox_id, $arr_opts );
			if( $ret === false ) {
				return false;
			}
			foreach( $ret['pois'] as $poi ) {
				$arr_poi[] = $poi;
			}
			if( count( $ret ) !== $page_size ) {
				break;
			}	
			$page_index++;
		}
		return $arr_poi;

	}

	/**
	 * ����POI��POIEXT������
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poiext?method=create POST����}
	 * @param uint64 $poi_id
	 * @param array $arr_ext_opts
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function createPoiExt( $poi_id, $arr_ext_opts = NULL ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poiext';
		$params = array();
		if( $arr_ext_opts ) {
			foreach( $arr_ext_opts as $k => $v ) {
				if( $v !== '' ) {
					$params[ $k ] = $v;
				}
			}
		}
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'create';
		$params[ 'poi_id' ] = $poi_id;
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ] ;
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * �޸�POI��POIEXT������
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poiext?method=update POST����}
	 * @param uint64 $poi_id
	 * @param array $arr_ext_opts
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function updatePoiExt( $poi_id, $arr_ext_opts = NULL ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poiext';
		$params = array();
		if( $arr_ext_opts ) {
			foreach( $arr_ext_opts as $k => $v ) {
				if( $v !== '' ) {
					$params[ $k ] = $v;
				}
			}
		}
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'update';
		$params[ 'poi_id' ] = $poi_id;
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret )  && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ɾ��POI��POIEXT������
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poiext?method=delete POST����}
	 * @param uint64 $poi_id
	 * @param array/string(4600) $keys property_keyֵ�����������ð�Ƕ��ŷָ����ַ�������������
	 * @return �ɹ�����true�����򷵻�false��
	 */
	public function deletePoiExt( $poi_id, $keys = NULL ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poiext';
		$params = array();
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'delete';
		$params[ 'poi_id' ] = $poi_id;
		if( $keys ) {
			if( is_string( $keys ) ) {
				$params[ 'keys' ] = $keys;
			} else if( is_array( $keys ) ) {
				$params[ 'keys' ] = implode( ',', $keys );
			} else if( is_integer( $keys ) ) {
				$params[ 'keys' ] = $keys;
			}
		}
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret )  && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $this->_status === 0 ) {
				return true;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * ������ѯPOI��POIEXT������
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poiext?method=list GET����}
	 * @param uint64 $poi_id
	 * @param array $property_key Ϊ��ʱȫ���ؼ��ֵ�ֵ�����г���
	 * @return �ɹ����ز�ѯ��Ϣ��������ṹ���£�
	 *	-poi_id string
	 *	-size uint32 �������
	 *	-poi_ext_info array poiext�ֶ��ֵ�
	 *	���򷵻�false��
	 */
	public function listPoiExt( $poi_id, $property_key = NULL ) {
		$this->resetErrorMsg();
		$url =$this->host . '/' .$this->name . '/poiext';
		$params = array();
		if( $property_key ) {
			$params[ 'property_key' ] = $property_key;
		}
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'list';
		$params[ 'poi_id' ] = $poi_id;
		$http_method = 'GET';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method );
		}
		$ret = $this->http( $url, $params, $http_method );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ];
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'poi_ext_info' ] ;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}

	/**
	 * �����ϴ�POI���ݣ�ֻ֧��CVS
	 * ��ӦAPI��{@link api.map.baidu.com/geodata/poi?method=upload POST����}
	 * @param uint32 $databox_id
	 * @param string $filepath
	 * @return �ɹ����ص���job��id������ʹ�ø�id��ѯ��job�ĵ�����ȣ����򷵻�false��
	 */
	public function uploadPoi( $databox_id, $filepath ) {
		$this->resetErrorMsg();
		$url =$this->host. '/' .$this->name . '/poi';
		$params = array();
		$params[ 'databox_id' ] = $databox_id;
		$params[ 'poi_list' ] = $filepath;
		$params[ 'ak' ] = $this->access_key;
		$params[ 'method' ] = 'upload';
		$http_method = 'POST';
		if( $this->need_sign ) {
			$this->getSign( $url, $params, $http_method, array('databox_id'), array('poi_list'));
		}
		$ret = $this->http( $url, $params, $http_method, true, 'poi_list', 'application/unknown' );
		if( is_array( $ret ) && isset( $ret[ 'status' ] ) ) {
			$this->_status = $ret[ 'status' ] ;
			if( $ret[ 'status' ] === 0 ) {
				return $ret[ 'id' ] ;
			}
			$this->error_message = 'response error: ' . __FILE__ . ',' . __FUNCTION__ . ':' . __LINE__;
			return false;
		}
		$this->_status = CLIENT_ERROR;
		throw new BasicServiceException("wrong response type returned, break point: " . __FILE__ . ',' . __FUNCTION__ . ',' . __LINE__);
		return false;
	}


	/**
	 * construction
	 */
	public function __construct( $access_key, $secret_key = NULL, $host = NULL ) {
		parent::__construct( $access_key, $secret_key, $host );
		$this->useragent = 'My LBS Storage Service';
		$this->name = 'geodata';
	}
}


?>
