<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyList extends Model
{
    //
    protected $fillable = ['property_id','listname','thumbnail'];

    public function propertyitems()
    {
        return $this->hasMany('App\PropertyItems','property_list_id');
    }
    public function images()
    {
        return $this->hasMany('App\PropertyListImages','property_list_id');
    }

}
