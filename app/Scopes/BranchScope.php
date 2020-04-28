<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class BranchScope implements Scope
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
        if(APP_GUARD == GUARD_OUTLET) { 
            if(Auth::guard(APP_GUARD)->check()) {       
                $builder->where($model->getTable().'.branch_id',Auth::guard(APP_GUARD)->user()->branch_id);
            }
        }
    }
}