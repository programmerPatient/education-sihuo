<?php

namespace App\Http\Controllers\Adminfour;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use DB;
use Auth;
use Session;
use App\Models\Admin\System;
use App\Models\Admin\Collection;
use App\Models\Admin\RelationReport;

class ReportController extends Controller
{

    public function index(){
        /*拿到所有报表的数据*/
        $curlt = curl_init();

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
        if(!$response) {
                return view('admin4.error.index');
        }
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          // $response = simplexml_load_string($response);
            $data = json_decode($response)->workbooks->workbook;
            $p = [];
            $pageUrlIds=[];
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
                if(!$chilresponse) {
                    return view('admin4.error.index');
                }
                $err = curl_error($curlt);
                curl_close($curlt);
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $viesdata = json_decode($chilresponse)->workbook->views->view;
                    $wok = json_decode($chilresponse)->workbook;
                }
                for($i=0 ; $i< count($viesdata);$i++ ){
                    $vies['view'] = $viesdata[$i];
                    $vies['project'] = $wok->project->name;
                    $vies['workBook'] = $wok->name;
                    $p[] = $vies;
                }
            }
        }
        $data = RelationReport::orderBy('id','desc')->get();
        foreach($data as $o => $vl){
            $k = false;
            foreach($p as $pk=>$valu){
                if($vl->report_id == $valu['view']->id){
                    $k = true;
                    break;
                }
            }
            if(!$k){
                $val->delete();
            }
        }
        // foreach($p as $u => $l){
        //     $h = false;
        //     foreach($data as $g=>->$r){
        //         if($l['view']->id == $r->report_id){
        //             $h = true;
        //         }
        //     }
        //     if(!$h){
        //         $reportData['report_name'] = $l['view']->name;
        //         $reportData['project_name'] = $l['project'];
        //         $reportData['workBook_name'] = $l['workBook'];
        //         $reportData['report_id'] = $l['view']->id;
        //         $reportData['created_at'] = date('Y-m-d H:i:s',time());
        //         RelationReport::insert($reportData);
        //     }
        // }
        $date = RelationReport::all();
        foreach($date as $key=>$value){
            $date[$key]['username'] = $date[$key]->member->username;
        }
        //查询用户组名
        foreach($date as $key=>$value){
            if($value->usergroup_id){
                $date[$key]['user_group_name'] = $value->usergroup->group_name;
            }else{
                $date[$key]['user_group_name'] = '';
            }
        }
        return view('admin4.report.reportindex',compact('date'));
    }

   public function relation($id){
        if(Input::method() == 'POST'){
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['project_group'] = $project_group;
            $re = RelationReport::where('id',$id)->get()->first();
            if($re){
                $result = $re->update($data);
            }else{
                return '0';
            }
            return $result ? '1':'0';
        }else{

            $da = RelationReport::where('id',$id)->get()->first();
            if(!$da){
                return view('admin4.report.index');
            }else{
                $project_group = explode('|',$da->project_group);

            }
            return view('admin4.report.relation',compact('project_group'));
        }
   }
   public function relations($id){
        if(Input::method() == 'POST'){
            $ids = explode(',',$id);
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['project_group'] = $project_group;
            $result = true;
            foreach($ids as $k=>$id){
                $re = RelationReport::where('id',$id)->get()->first();
                if($re){
                    $result = $re->update($data);
                }else{
                    return '0';
                }
            }
            return $result ? '1':'0';
        }else{
            return view('admin4.report.index');
        }
   }


   //报表位置的查询
   public function select(){

        if($user = Auth::guard('member')->user()){
                $name = $user->username;
                $tableauIds = explode(',',$user -> tableauIds);
        }else{
            $tableauIds = false;
            $name = Auth::guard('admin')->user()->username;

        }
        /*拿到所有报表的数据*/
        $curlt = curl_init();

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
        if(!$response) {
                return view('admin4.error.index');
        }
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          // $response = simplexml_load_string($response);
            $data = json_decode($response)->workbooks->workbook;
            $p = [];
            $pageUrlIds=[];
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
                if(!$chilresponse) {
                    return view('admin4.error.index');
                }
                $err = curl_error($curlt);
                curl_close($curlt);
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $viesdata = json_decode($chilresponse)->workbook->views->view;
                    $wok = json_decode($chilresponse)->workbook;
                }

                if($tableauIds){
                    foreach($viesdata as $key => $vaie){
                        if(in_array($vaie->id,$tableauIds)){
                            $project = true;
                        }else{
                            unset($viesdata[$key]);//剔除该元素
                        }
                    }
                }
                for($i=0 ; $i< count($viesdata);$i++ ){
                    if($user){
                        $report = RelationReport::where('report_id',$viesdata[$i]->id)->where('member_id',$user->id)->get()->first();
                        if(!$report) {
                            $vies['filter'] = '';
                        }else{
                            $project = explode('|',$report->project_group);
                            $vies['filter'] = implode('@',$project);
                        }
                    }else{
                        $vies['filter'] = "iframeSizedToWindow=true";
                    }
                    $coll = Collection::where('report_id',$viesdata[$i]->id)->get()->first();
                    if($coll){
                        $vies['collection'] = '1';//如果为1表示被收藏
                    }else{
                        $vies['collection'] = '0';//如果为0表示未收藏
                    }
                    $vies['view'] = $viesdata[$i];
                    $vies['project'] = $wok->project->name;
                    $vies['workBook'] = $wok->name;
                    $p[] = $vies;
                }
            }
        }
        return view('admin4.report.select',compact('p'));
   }

   //报表收藏
   public function collection(Request $request){
        if($user = Auth::guard('member')->user()){
            $id = $user->id;
            $type = '2';
        }
        if($manager = Auth::guard('admin')->user()){
            $id = $manager->id;
            $type = '1';
        }
        $co = $request->co;
        if($co == 'true'){
            $rep = $request->report_id;
            $re = RelationReport::where('member_id',$id)->where('report_id',$rep)->get()->first();
            dd($re);
            $insert['project_name'] = $re->project_name;
            $insert['workBook_name'] = $re->workBook_name;
            $insert['report_name'] = $re->report_name;
            $insert['report_id'] = $rep;
            $insert['user_id'] = $id;
            $insert['type'] = $type;
            $insert['filter'] = $request->filter;
            $insert['contentUrl'] = $request->contentUrl;
            $result = Collection::insert($insert);
        }else{
            $rep = $request->report_id;
            $result = Collection::where('report_id',$rep)->where('user_id',$id)->delete();
        }

        return $result ? '1' : '0';
   }
   //报表收藏
   public function collectindex(){
        if($user = Auth::guard('member')->user()){
            $id = $user->id;
            $type = '2';
        }
        if($manager = Auth::guard('admin')->user()){
            $id = $manager->id;
            $type = '1';
        }
        $data = Collection::where('user_id',$id)->where('type',$type)->get();

        return view('admin4.collection.index',compact('data'));
   }
}
