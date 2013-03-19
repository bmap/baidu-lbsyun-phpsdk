<?php
/**
 * �洢API�ӿ��е�ö��ֵ
 * @package lbs
 * @author kuangzhijie
 * @date 2012/12/24 11:49:02 
 * @version 1.0
 */

// geotype ��ö��ֵ
define( 'GEOTYPE_POINT_POI', 1 );
define( 'GEOTYPE_LINE_POI', 2 );
define( 'GEOTYPE_FLAT_POI', 3 );

// scope ��ö��ֵ
define( 'SCOPE_BASIC', 1 );
define( 'SCOPE_DETAIL', 2 );

// property_type ��ö��ֵ
define( 'PROPERTY_INT32', 1 );
define( 'PROPERTY_INT64', 2 );
define( 'PROPERTY_FLOAT', 3 );
define( 'PROPERTY_DOUBLE', 4 );
define( 'PROPERTY_STRING', 10 );

// original_coord_type ��ö��ֵ
// ����ּ�������
define( 'NATION_ENCRYPT', 1 );
// �ٶȼ�������
define( 'BAIDU_ENCRYPT', 2 );
// δ��������
define( 'NO_ENCRYPT', 3 );


define( 'CLIENT_ERROR', -1 );
define( 'SUCCESS', 0 );
define( 'SERVER_INNER_ERROR', 1 );
define( 'REQUEST_PARAM_ERROR', 2 );
define( 'PERMISSION_CHECK_FAILED', 3 );
define( 'QUOTA_CHECK_FAILED', 4 );
define( 'ACCESS_KEY_ERROR', 5 );
define( 'UPLOAD_FAILED', 6 );
define( 'UPLOAD_DUPLICATED', 7 );
define( 'SERVICE_DISABLED', 101 );
define( 'WRITE_LIST_OR_SECRET_KEY_ERROR', 102 );
define( 'PERMISSION_DENIED', 204 );
define( 'QUOTA_ALLOC_ERROR', 300 );


?>
