<?php

namespace App\Http\Controllers\Adminfour;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use DB;
use Session;
use App\Models\Admin\System;

class SystemController extends Controller
{
    public function update(Request $request){
        if(Input::method() == 'POST'){
            //系统设置的修改
            $default = System::get()->first();
            if($request->same_tableau == '1'){
                $default->tableau_username = $request->tableau_username;
                $default->tableau_password = $request->tableau_password;
            }else{
                $default->tableau_username = null;
                $default->tableau_password = null;
            }
            $default->same_tableau =  $request->same_tableau;
            $tableau_domain = Input::only("tableau_domain")["tableau_domain"];
            $web_title = Input::only('web_title')['web_title'];
            $company = Input::only('company')['company'];
            $toolbar = Input::only('toolbar')['toolbar'];
            $model = Input::only('model')['model'];
            $file = $request->file('logo_img');
            if($file){

                $allowed_extensions = ["png", "jpg", "gif","PNG",'jpeg'];
                if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                    return ['error' => 'You may only upload png, jpg , PNG , jpeg or gif.'];
                }
                $destinationPath = 'images/'; //public 文件夹下面建 imges 文件夹

                $extension = $file->getClientOriginalExtension();
                $fileName = str_random(10).'.'.$extension;
                $file->move($destinationPath, $fileName);
                $filePath = asset($destinationPath.$fileName);
                $post['logo_url'] = $destinationPath.$fileName;
                $post['system_domain'] = $tableau_domain;
                $default->logo_url = '/'.$destinationPath.$fileName;
            }
            // $post['type'] = '1';

            // $default -> type = '0';
            // $default->save();
            $default->system_domain = $tableau_domain;
            $default->web_title = $web_title;
            $default->company = $company;
            $default->toolbar = $toolbar;
            $default->model = $model;
            return $default->save() ? '1':'0';
            // //修改config配置
            // $data =  System::get()->first();
            // $data->system_domain = $tableau_domain;
            // $data -> save();
            // return 1;
        }else{
            $default = System::get()->first();
                        // dd($tableau_domain);
            return view('admin4.system.index',compact('default'));
        }
    }
}
