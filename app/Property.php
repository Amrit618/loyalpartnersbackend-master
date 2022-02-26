<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    //
    protected $fillable = ['user_id','property_name','description','thumbnail'];

    public function propertylist()
    {
        return $this->hasMany('App\PropertyList','property_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
