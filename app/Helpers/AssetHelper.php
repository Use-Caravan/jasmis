<?php

namespace App\Helpers;

use App;
use App\Language;

/**
 * Class AssetHelper
 * @package App\Helpers
 *
 * @author N Manojkumar<manojkumar.n@technoduce.com>
 */

class AssetHelper
{
    /**
     *  Base path 
     */
    public static $adminBasePath = ADMIN_END_BASE_PATH;

    public static $frontendBasePath = FRONT_END_BASE_PATH;

	/**
     * @var array
     */
    public static $assetFile = [
    	['url' => 'css/AdminLTE.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/bootstrap.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/bootstrap-select.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/font-awesome.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/jquery-ui.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/datetimepicker.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/iziToast.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/style.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/my-style.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/custome-style.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/jstree.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'js/jquery.min.js', 'type' => 'text/js', 'header' => 1],
        
        ['url' => 'jsvalidation/js/jsvalidation.js', 'type' => 'text/js', 'header' => 0],
        ['url' => 'js/lodash.min.js', 'type' => 'text/js', 'header' => 0],
        ['url' => 'js/my-script.js', 'type' => 'text/js', 'header' => 0],
        ['url' => 'js/adminlte.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/bootstrap.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/bootstrap-select.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/jquery-ui.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/jquery.slimscroll.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/jquery.dataTables.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/dataTables.bootstrap.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/moment.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/datetimepicker.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/iziToast.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/jstree.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/custom-script.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/jscolor.js', 'type' => 'type/js', 'header' => 0],
    ];

    /**
     * @var array
     */
    public static $assetFileFrontend = [
        ['url' => 'css/bootstrap.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/datepicker.min.css', 'type' => 'text/css', 'header' => 1],
    	['url' => 'css/animate.min.css', 'type' => 'text/css', 'header' => 1],        
        
        ['url' => 'css/font-awesome.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/simple-line-icons.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/owl.carousel.min.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/iziToast.min.css', 'type' => 'text/css', 'header' => 1],                        
        ['url' => 'css/style.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/responsive.css', 'type' => 'text/css', 'header' => 1],
        ['url' => 'css/custom.css', 'type' => 'text/css', 'header' => 1],        
        ['url' => 'js/jquery.min.js', 'type' => 'text/js', 'header' => 1],
        
        
        ['url' => 'jsvalidation/js/jsvalidation.js', 'type' => 'text/js', 'header' => 0],
        ['url' => 'jsvalidation/js/jsvalidation.js', 'type' => 'text/js', 'header' => 0],
        ['url' => 'js/popper.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/bootstrap.min.js', 'type' => 'text/js', 'header' => 0],        
        ['url' => 'js/owl.carousel.min.js', 'type' => 'type/js', 'header' => 0],        
        ['url' => 'js/wow.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/jquery.sticky-kit.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/datepicker.min.js', 'type' => 'type/js', 'header' => 0],                                
        ['url' => 'js/i18n/datepicker.en.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/iziToast.min.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/custom.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/development.js', 'type' => 'type/js', 'header' => 0],
        ['url' => 'js/typeahead.bundle.js', 'type' => 'type/js', 'header' => 0],
        
    ];

    public static function loadAdminAsset($header = 0)
    {
    	$assetFiles = '';

    	for( $i=0; $i<count(self::$assetFile); $i++ ) {

    		$link = asset(self::$adminBasePath.self::$assetFile[$i]['url']);
            $type = self::$assetFile[$i]['type'];
            if ( (int)$header === (int)self::$assetFile[$i]['header'] ) {
                if ( (string)$type === 'text/css' ) {
                    $assetFiles .= '<link rel="stylesheet" type="'.$type.'" href="'.$link.'">';
                } else {
                    $assetFiles .= '<script src="'.$link.'"></script>';
                }
            }
        }
    	return $assetFiles;
    }

    public static function loadFrontendAsset($header = 0)
    {
        $language = Language::where('language_code',App::getLocale())->first();

        if($language !== null && $language->is_rtl == 1) {
            array_push(self::$assetFileFrontend,['url' => 'css/style-rtl.css', 'type' => 'text/css', 'header' => 1]);
        }
        
        /*  */
    	$assetFiles = '';
    	for( $i=0; $i<count(self::$assetFileFrontend); $i++ ) {

    		$link = asset(self::$frontendBasePath.self::$assetFileFrontend[$i]['url']);
            $type = self::$assetFileFrontend[$i]['type'];
            if ( (int)$header === (int)self::$assetFileFrontend[$i]['header'] ) {
                if ( (string)$type === 'text/css' ) {
                    $assetFiles .= '<link rel="stylesheet" type="'.$type.'" href="'.$link.'">';
                } else {
                    $assetFiles .= '<script src="'.$link.'"></script>';
                }
            }
        }
    	return $assetFiles;
    }
}
?>