<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyItems extends Model
{
    //
    protected $fillable = ['property_list_id','name','clean','unclean','work_needed','description'];
}
