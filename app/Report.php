<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = ['property_id','owner_id','inspector_id','report_link','inspector_email','property_name'];
}
