<?php

namespace App\Http\Controllers\Adminthree;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Redirect;
use Session;
use DB;
use App\Models\Admin\System;
use App\Models\Admin\RelationReport;

class IndexController extends Controller
{


    //首页
    public function index(){

        if($user = Auth::guard('member')->user()){
                $name = $user->username;
                $tableauIds = explode(',',$user -> tableauIds);
        }else{
            $tableauIds = false;
            $name = Auth::guard('admin')->user()->username;

        }
        $type = Session::get('user_type');
        $system = System::get()->first();
        $curlt = curl_init();

        /*获取用户的信息*/
        curl_setopt_array($curlt, array(
        CURLOPT_URL =>  Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/workbooks/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_COOKIE =>"token=".Session::get('token'),
        CURLOPT_HTTPHEADER => array(
            "X-Tableau-Auth: ".Session::get('token'),
            "Accept: application/json",
          ),
        ));
        $response = curl_exec($curlt);
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          // $response = simplexml_load_string($response);

            $data = json_decode($response)->workbooks->workbook;
            $p = [];
            if(!$data) return view('admin3.error.index');
            // $u = array();
            // if($tableauIds){
            //     foreach($data as $k=>$tab){
            //         if(in_array($tab->id,$tableauIds)){
            //             $u[] = $tab;
            //         }
            //     }
            //     $data = $u;
            // }
            // $rs = $response->toArray();
            foreach($data as $key=>$val){
                $id = $val->project->id;
                $curlt = curl_init();
                curl_setopt_array($curlt, array(
                CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/workbooks/".$val->id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "X-Tableau-Auth:".Session::get('token'),
                    "Accept: application/json",
                  ),
                ));
                $chilresponse = curl_exec($curlt);
                $err = curl_error($curlt);
                curl_close($curlt);
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $viesdata = json_decode($chilresponse)->workbook->views->view;
                    $dat = RelationReport::all();
                    for($i=0 ; $i< count($viesdata);$i++ ){
                        if(json_decode($dat) == null){
                            $viesdata[$i]->filter = "iframeSizedToWindow=true";
                            continue;
                        }
                        foreach($dat as $g=>$r){
                            if($viesdata[$i]->id == $r->report_id){
                                if($r->project_group){
                                    dd(explode('|',$r->project_group));
                                    $viesdata[$i]->filter = implode('&',explode('|',$r->project_group));
                                }else{
                                    $viesdata[$i]->filter = "iframeSizedToWindow=true";
                                }
                            }
                        }
                    }
                }

                if($tableauIds){
                    $project = false;
                    foreach($viesdata as $key => $vaie){
                        if(in_array($vaie->id,$tableauIds)){
                            $project = true;
                        }else{
                            unset($viesdata[$key]);//剔除该元素
                        }
                    }
                }else{
                    $project = true;
                }
                if($project){
                    //判断是否是重复的父类
                    if(!array_key_exists($id,$p)){
                        $p[$id]["name"] = $val->project->name;
                    }
                    $p[$id]["project"][$val->id] = [
                    "webpageUrl" =>$val->webpageUrl,
                    "name" => $val->name,
                    "id" => $val->id,
                    "views" => $viesdata
                    ];
                }
            }
        }
        // FS1Wu4GJRVCaNdtzbAeHlw|j9JPkfLMU0wZtx8c1BB6pkPGuiEim0h
        return view('admin3.index.index',compact('p','system','type','name'));
    }




    //首页，框架页面
    public function welcome(){
        return view('admin3.index.welcome');
    }
}
