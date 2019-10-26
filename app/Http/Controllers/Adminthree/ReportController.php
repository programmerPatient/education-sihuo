<?php

namespace App\Http\Controllers\Adminthree;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use DB;
use Session;
use App\Models\Admin\System;
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
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          // $response = simplexml_load_string($response);
            $data = json_decode($response)->workbooks->workbook;
            dd($data);
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
                $err = curl_error($curlt);
                curl_close($curlt);
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $viesdata = json_decode($chilresponse)->workbook->views->view;
                    dd($viesdata);
                }
                for($i=0 ; $i< count($viesdata);$i++ ){
                    $p[] = $viesdata[$i];
                }
            }
        }
        $data = RelationReport::all();
        foreach($data as $o => $vl){
            $k = false;
            foreach($p as $pk=>$valu){
                if($vl->report_id == $valu->id){
                    $k = true;
                    break;
                }
            }
            if(!$k){
                $val->delete();
            }
        }
        foreach($p as $u => $l){
            $h = false;
            foreach($data as $p=>$r){
                if($l->id == $r->report_id){
                    $h = true;
                }
            }
            if(!$h){

                $reportData['report_name'] = $l->name;
                $reportData['report_id'] = $l->id;
                RelationReport::insert($reportData);
            }
        }
        $date = RelationReport::all();
        return view('admin3.report.reportindex',compact('date'));
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
            $re = RelationReport::where('report_id',$id)->get()->first();
            if($re){
                $result = $re->update($data);
            }else{
                return '0';
            }
            return $result ? '1':'0';
        }else{

            $da = RelationReport::where('report_id',$id)->get()->first();
            if(!$da){
                return view('admin3.relation.index');
            }else{
                $project_group = explode('|',$da->project_group);

            }
            return view('admin3.report.relation',compact('project_group'));
        }
   }
}
