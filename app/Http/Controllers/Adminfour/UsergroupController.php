<?php

namespace App\Http\Controllers\Adminfour;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\UserGroup;
use Input;

class UsergroupController extends Controller
{
    //用户组操作
    public function group(){
        //查询数据
        $data = UserGroup::get();
        return view('admin4.usergroup.index',compact('data'));
    }

    //项目组映射
    public function report($id){
        if(Input::method() == 'POST'){
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                //排除空数组
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['project_group'] = $project_group;
            $re = UserGroup::where('id',$id)->get()->first();
            if($re){
                $result = $re->update($data);
            }else{
                return '0';
            }
            return $result ? '1':'0';
        }else{
            $da = UserGroup::where('id',$id)->get()->first();
            if(!$da){
                return view('admin4.usergroup.report');
            }else{
                $project_group = explode('|',$da->project_group);

            }
            return view('admin4.usergroup.report',compact('project_group'));
        }
    }

    public function usergroup($id){
        $has = RelationReport::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $usergroup_id = Input::get('usergroup_id');
            $has->usergroup_id = $usergroup_id;
            $result = $has->save();
            return $result ? '1' : '0';
        }else{
            $data = UserGroup::get();
            return view('admin4.report.usergroup',compact('data','has'));
        }
    }
}
