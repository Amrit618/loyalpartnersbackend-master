<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    //
    protected $fillable = ['manager_id','property_id','inspection_date','inspection_time','message','status'];

    public function inspection()
    {
        return $this->belongsTo('App\Property','property_id');
    }
}
