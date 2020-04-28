<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VendorScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {                
        switch(APP_GUARD) {
            case GUARD_VENDOR:            
                if(Auth::guard(APP_GUARD)->check()) {
                    $result = $builder->where($model->getTable().'.vendor_id',Auth::guard(APP_GUARD)->user()->vendor_id);
                    
                }
                break;
            case GUARD_OUTLET:
                if(Auth::guard(APP_GUARD)->check()) {
                    $builder->where($model->getTable().'.vendor_id',Auth::guard(APP_GUARD)->user()->vendor_id);
                }                        
                break;
        }
    }
}