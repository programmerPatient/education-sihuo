<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AllReport extends Model
{
    //
    protected $table = 'allreport';

    protected $fillable = ['report_name','report_id','contentUrl'];

    public $timestamps = false;
}
