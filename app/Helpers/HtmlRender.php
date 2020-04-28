<?php

namespace App\Helpers;
use App\DeliveryBoy;
use DB;


class HtmlRender
{	    
    /**
     * Method to generate status column checkbox
     * @param object $model
     * @param string $route     
     * @return string Html render
     */
    public static function statusColumn($model,$route)
    {   
        $type = TYPE_STATUS_COLUMN;
        return view('admin.layouts.partials._tableaction',compact('model','route','type'));
    }
    public static function popularColumn($model,$route)
    {   
        $type = TYPE_POPULARSTATUS_COLUMN;
        return view('admin.layouts.partials._tableaction',compact('model','route','type'));
    }
    public static function quickbuyColumn($model,$route)
    {   
        $type = TYPE_QUICKBUYSTATUS_COLUMN;
        return view('admin.layouts.partials._tableaction',compact('model','route','type'));
    }

    /**
     * Method to generate approved status column checkbox
     * @param object $model
     * @param string $route     
     * @return string Html render
     */
    public static function approvedStatusColumn($model,$route)
    {   
        $approvedStatus = $model->approvedStatus();
        $type = APPROVED_STATUS_COLUMN;
        return view('admin.layouts.partials._tableaction',compact('model','route','type','approvedStatus'));
    }
    /**
     * Method to generate action columns like edit, view, delete
     * @param object $model
     * @param string $route
     * @param array $params // route params
     * @param string $title
     * @param array $attributes
     * @param boolean $isdelete
     * @return string Html render
     */
    public static function actionColumn($model, $route = 'javascript:', $params = [], $title = '', $attributes = [], $isdelete = false)
    {
        $type = TYPE_ACTION_COLUMN;
        return view('admin.layouts.partials._tableaction',compact('model','route','params','title','attributes','isdelete','type'));
    }
}