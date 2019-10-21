<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Member;
use Input;
use Session;
use Excel;

class MemberController extends Controller
{
    //列表展示
    public function index(){
        //查询数据
        $data = Member::orderBy('id','desc')->get();
        //展示视图
        return view('admin.member.index',compact('data'));
    }

    //添加会员
    public function add(){
        //判断请求类型
        if(Input::method() == 'POST'){
            $data = Input::only(['username','password','gender','email']);
            $data['created_at'] = date('Y-m-d H:i:s',time());
            $data['password'] = bcrypt($data['password']);
            $data['status'] = '1';
            // $data['avatar'] = "/images/th.jpg";
            $result = Member::insert($data);
            return $result ? '1':'0';
        }else{
            //展示视图
            return view('admin.member.add');
        }
    }
    //批量添加会员
    public function adds(Request $request){

        //判断请求类型
        if(Input::method() == 'POST'){
             //设置文件后缀白名单
            $allowExt   = ["csv", "xls", "xlsx"];
            //获取文件
            $file = $request->file('file');
            // $realPath = $file->getRealPath();
            $entension =  $file -> getClientOriginalExtension(); //上传文件的后缀.
            $tabl_name = date('YmdHis').mt_rand(100,999);
            $newName = $tabl_name.'.'.'xls';//$entension;
            $path = $file->move(public_path().'/uploads',$newName);
            $cretae_path = public_path().'/uploads/'.$newName;
            Excel::load($file, function($reader) {
                $data = $reader->all();
                dd($data);
            });
            //校验文件
            if(isset($file) && $file->isValid()){
                $ext = $file->getClientOriginalExtension(); //上传文件的后缀
                //判断是否是Excel
                if(empty($ext) or in_array(strtolower($ext),$allowExt) === false){
                    return $this->fail(400, '不允许的文件类型');
                }
            }
            dd($request->all());
            $data = Input::only(['username','password','gender','email']);
            $data['created_at'] = date('Y-m-d H:i:s',time());
            $data['password'] = bcrypt($data['password']);
            $data['status'] = '1';
            // $data['avatar'] = "/images/th.jpg";
            $result = Member::insert($data);
            return $result ? '1':'0';
        }else{
            //展示视图
            return view('admin.member.adds');
        }
    }

    //四修改会员信息
    public function modify($id){
        $data = Member::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $post = Input::only(['password','gender','status','email']);
            $data->password = bcrypt($post['password']);
            $data->gender = $post['gender'];
            $data->status = $post['status'];
            $data->email = $post['email'];
            return $data->save() ? '1':'0';
        }else{
            return view('admin.member.modify',compact('data'));
        }
    }

    //删除会员
    public function delete(){
        $id = Input::only('id')['id'];
        $data = Member::where('id',$id)->get()->first();
        $result = $data ->delete();
        // $data->save();
        return $result?'1':'0';
    }

    //批量删除会员
    public function deletes(){
        $ids = Input::only('ids');
        foreach($ids as $key=>$val){
            $data = Member::where('id',$val)->get()->first();
            $result = $data ->delete();
        }
        // $data->save();
        return $result?'1':'0';
    }

}
