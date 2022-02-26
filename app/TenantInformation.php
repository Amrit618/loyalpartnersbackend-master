<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenantInformation extends Model
{
    //
    protected $fillable = ['property_id','tenant_name','tenant_contact','tenant_email'];
}
