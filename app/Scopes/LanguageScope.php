<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
use App;

class LanguageScope implements Scope
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
        $builder->leftJoin($model::tableName(true),function($query){
            $query->on($model::tableName().".".$model->getKey(), '=', 'CL.cuisine_id')
            ->where('CL.language_code',App::getLocale());
        });
        //$builder->where('language_code', '=', App::getLocale());
    }
}