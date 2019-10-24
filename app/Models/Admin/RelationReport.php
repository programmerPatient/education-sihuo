<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
//引入trait
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class RelationReport extends Model implements AuthenticatableContract
{
    protected $table = 'relationreport';
    public $timestamps =false;
    //使用trait，就相当于将trait代码段复制到这个位置
    use Authenticatable;

    protected $fillable =['report_name','report_id','project_group'];
}
