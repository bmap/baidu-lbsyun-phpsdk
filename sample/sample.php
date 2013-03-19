<?php
/***************************************************************************
 * 
 * Copyright (c) 2012 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file sample.php
 * @author kuangzhijie(com@baidu.com)
 * @date 2012/12/24 11:15:55
 * @brief 
 *  
 **/
require_once( '../MyStorageService.class.php' );
require_once( '../MySearchService.class.php' );
require_once( './config.php' );

function error_output( $str ) {
    echo "\033[1;40;31m" . $str . "\033[0m" . "\n";
}

function right_output( $str ) {
    echo "\033[1;40;32m" . $str . "\033[0m" . "\n";
}

function test_createDatabox( $name, $geotype = 1 ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $service->need_sign = false;
        $ret = $service->createDatabox( $name, $geotype );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_publishDatabox( $databox_id, $search = 0, $bus = 0) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $service->need_sign = false;
        $ret = $service->publishDatabox( $databox_id, $search, $bus );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . $ret );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}


function test_updateDatabox( $id, $new_name ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->updateDatabox( $id, $new_name );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}


function test_getDatabox( $id, $scope = SCOPE_BASIC ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->getDatabox( $id, $scope ); 
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}


function test_listDatabox( $name = NULL, $page_index = 0, $page_size = 10 ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->listDatabox( $name, $page_index, $page_size );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $ret );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_listDataboxAll( $name = NULL ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->listDataboxAll( $name );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . 'ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $ret );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . 'OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_deleteDatabox( $id ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->deleteDatabox( $id );
        if( $ret !== false ) {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . $ret );
        } else {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_createDataboxMeta( $property_name, $property_key, $property_type, $databox_id, $if_magic_field = false ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->createDataboxMeta( $property_name, $property_key, $property_type, $databox_id, $if_magic_field );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r($ret, true) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_createDataboxMetaSet( $property_name, $property_key, $property_type, $databox_id, $if_magic_field = false ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->createDataboxMetaSet( $property_name, $property_key, $property_type, $databox_id, $if_magic_field );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r($ret, true) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_updateDataboxMeta( $id, $new_property_name, $if_magic_field  = false ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->updateDataboxMeta( $id, $new_property_name, $if_magic_field );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_getDataboxMeta( $id ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->getDataboxMeta( $id );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_deleteDataboxMeta( $id ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->deleteDataboxMeta( $id );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . $ret );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_listDataboxMeta( $databox_id, $property_name = NULL, $property_key = NULL ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->listDataboxMeta( $databox_id, $property_name, $property_key );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_createPoi( $name, $lat, $lon, $coord_type, $databox_id ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $opts = array( 'name' => $name, 'original_lat' => $lat, 'original_lon' => $lon, 'original_coord_type' => $coord_type, 'databox_id' => $databox_id );
        $ret = $service->createPoi( $opts );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_updatePoi( $poi_id, $name = NULL) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $opts = array( 'name'=>$name );
        $ret = $service->updatePoi( $poi_id, $opts );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_getPoi( $poi_id, $scope = SCOPE_BASIC ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->getPoi( $poi_id, $scope );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_listPoi( $databox_id, $name = NULL ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        if( $name ) {
            $option[ 'name' ] = $name;
        }
        $ret = $service->listPoi( $databox_id, $option );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_listPoiAll( $databox_id ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->listPoiAll( $databox_id );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_listPoiByIds( $poi_ids, $scope = SCOPE_BASIC ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->listPoiByIds( $poi_ids, $scope );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}


function test_deletePoi( $poi_id ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->deletePoi( $poi_id );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_createPoiExt( $poi_id, $food ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $option[ 'sex' ] = $food;
        $ret = $service->createPoiExt( $poi_id, $option );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_updatePoiExt( $poi, $food ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $option[ 'job' ] = $food;
        $ret = $service->updatePoiExt( $poi, $option );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_deletePoiExt( $poi_id, $keys = NULL ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->deletePoiExt( $poi_id, $keys );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_listPoiExt( $poi_id, $property_key = NULL ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->listPoiExt( $poi_id, $property_key );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_uploadPoi( $databox_id, $filepath ) {
    try {
        $service = new MyStorageService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->uploadPoi( $databox_id, $filepath );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_searchRegion( $databox_id, $region ) {
    try {
        $service = new MySearchService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $service->setScope(SCOPE_DETAIL);
        $ret = $service->searchRegion( $databox_id, $region );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_searchNearby( $databox_id, $lat, $lon, $radius = 1000 ) {
    try {
        $service = new MySearchService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->searchNearby( $databox_id, $lat, $lon, $radius );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_searchBounds( $databox_id, $west, $east, $north, $south ) {
    try {
        $service = new MySearchService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->searchBounds( $databox_id, $west, $east, $north, $south );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

function test_searchDetail( $poi_id, $scope = 1 ) {
    try {
        $service = new MySearchService( ACCESS_KEY, SECRET_KEY, HOST );
        $service->debug = ENABLE_DEBUG;
        $ret = $service->searchDetail( $poi_id, $scope );
        if( $ret === false ) {
            error_output( 'WRONG ' . __FUNCTION__ . ' ERROR!!!' );
            error_output( 'ERROR_NUMBER: ' . $service->status() );
        } else {
            right_output( 'SUCC, ' . __FUNCTION__ . ' OK!!!' );
            right_output( 'result: ' . print_r( $ret, true ) );
        }
    } catch ( Exception $ex ) {
        error_output( 'WRONG ' . __FUNCTION__ . ' CLIENT ERROR!!!' );
        error_output( 'ERROR_MESSAGE: ' . $ex->getMessage() );
    }
}

# test_createDatabox( 'apple' );
# test_updateDatabox( 2126, 'orange' );
# test_getDatabox( 1741, SCOPE_DETAIL );
# test_listDatabox();
# test_deleteDatabox( 2128 );
# test_deleteDatabox( 2129 );
# test_deleteDatabox( 2130 );
# test_deleteDatabox( 2131 );
# test_listDatabox();
# test_listDataboxAll();
# test_createDataboxMeta( 'sportkkfjdddfafdsafsalffkdsaofijdsaeriuqwpfjd,abc', 'sportsi,fji', '10,10', 2126 );
# test_createDataboxMetaSet( array('food', 'sport'), array( 'food', 'sport' ), array(10, 10), 117702 );
# test_updateDataboxMeta( 4024, '食物' );
# test_getDatabox( 1741, SCOPE_DETAIL );
# test_getDataboxMeta( 4024 );
# test_deleteDataboxMeta( 15735 );
# test_listDataboxMeta( 117702 );
# test_createPoi( 'unknown', 40, 116, 2, 2126 );
# test_updatePoi( 2962327, 'bj' );
# test_getPoi( 2962339 );
# test_listPoi( 2126 );
# test_listPoiAll( 2126 );
# test_listPoiByIds( array( 2962339, 2962328 ), SCOPE_DETAIL );
# test_deletePoi( 2962338 );
# test_listPoi( 1741, 'bei' );
# test_createPoiExt( 2962339, 'male' );
# test_updatePoiExt( 2962329, 'orange' );
# test_getPoi( 2962339, 2 );
# test_listPoiExt( 2962339, 'food' );
# test_uploadPoi( 1741, 'poi.csv' );
# test_listPoi( 1741 );

# test_searchRegion( 2853, '131');
test_searchNearby( 1741, 40, 116 );
# test_searchBounds( 2853, 113, 118, 38, 41 );
# test_searchDetail( 2962339, SCOPE_DETAIL );






/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
?>
