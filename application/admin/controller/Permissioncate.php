<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;
use app\admin\model\Permission_category;

class Permissioncate extends Common
{
    public function permissioncate()
    {
        return $this->fetch();
    }
    public function permission_add()
    {
        return $this->fetch();
    }
    public function addaction(){
    
        $data=Request::post();
        $validate= new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr=['code'=>'0','status'=>'error','data'=>$validate->getError()];
            echo json_encode($arr);
            die;
        }
        $rbac= new Rbac();
        $ass=Db::table('Permission_category')->where('name',$data['name'])->select();
        if (empty($ass)) {
            $rbac->savePermissionCategory([
                'name' => $data['name'],
                'description' => $data['description'],
                'status' => 1
            ]);
            $arr=['code'=>'1','status'=>'ok','data'=>'添加成功'];
            echo json_encode($arr);
        }else{
            $arr=['code'=>'0','status'=>'error','data'=>'分类已经存在'];
            echo json_encode($arr);
            die;
        }
    }

    public function show()
    {
        $Permission_category = new Permission_category;
        $obj=$Permission_category->select();
        $arr=json_decode($obj,true);
        $res=['code'=>'1','status'=>'ok','data'=>$arr];
        echo $json=json_encode($res);
    }
    public function delete()
    {
        $id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('Permission_category')->where('id',$id)->delete();
         if (!empty($ass)) {
            $arr=['code'=>'1','status'=>'ok','data'=>'删除成功'];
            echo json_encode($arr);
         }else{
            $arr=['code'=>'0','status'=>'error','data'=>'删除失败'];
            echo json_encode($arr);
            die;     
        }
    }
    public function myupdate()
    {
        $id=Request::post('id');
        $ass=Db::table('Permission_category')->where('id',$id)->select();
        $arr=['code'=>'1','status'=>'ok','data'=>$ass];
        echo json_encode($arr);
    }
    public function updateaction(){
        $id=Request::post('id');
        $name=Request::post('name');
        $description=Request::post('description');

        $ass=Db::table('Permission_category')->where('name',$name)->select();
        if (empty($ass)) {
            $ass1=Db::table('Permission_category')->where('id',$id)->update(['name' => $name],['description' => $description]);
            $arr=['code'=>'1','status'=>'ok','data'=>'修改成功'];
            echo json_encode($arr);
        }else{
            $arr=['code'=>'0','status'=>'error','data'=>'修改失败'];
            echo json_encode($arr);
            die;
        }    
    }

}