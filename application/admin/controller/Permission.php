<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;

class Permission extends Common
{
     public function userPermission($userID,$timeOut = 3600)
    {
        if (empty($userId)) {
            throw new Exception("参数错误");
        }
        $permission = Session::get($this->_permisssionCachePrefix . $userId);
        if (!empty($permissioncate)) {
            return $permissioncate;
        }
        $permissioncate = $this->getPermissioncateByUserId($userId);
        if (empty($permissioncate)) {
            throw new Exception("未查询到该用户的任何权限");
            
        }
        $newPermission = [];
        if (!empty($permissioncate)) {
            foreach ($permissioncate as $k => $v) {
                $newPermission[$v['path']] = $v;
            }
        }
    }
    public function permission()
    {
        return $this->fetch();
    }
    //下拉框展示
    public function cate()
    {
        $rbac= new Rbac();
        $ass=Db::query("select * from permission_category");
        $json=['code'=>'1','status'=>'ok','data'=>$ass];
        return json($json);
    }
    //展示
    public function show()
    {
        $rbac= new Rbac();
        $ass=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p_c.id as pc_id from permission as p  LEFT JOIN permission_category as p_c on p.category_id=p_c.id");
        $json=['code'=>'1','status'=>'ok','data'=>$ass];
        return json($json);
        echo "123";
        die;
    }
    //添加  
    public function addaction(){
        $data=Request::post();
        $rbac= new Rbac();
        $ass=Db::table('Permission')->where('name',$data['name'])->select();
        $ass1=Db::table('Permission')->where('path',$data['path'])->select();
        if (!empty($ass)||!empty($ass1)) {
           $arr=['code'=>'0','status'=>'error','data'=>'角色或路径已经存在'];
            echo json_encode($arr);
            die;
        }else{
            $add = ['name' => $data['name'], 'category_id' => $data['category_id'],'path' => $data['path'],'description' => $data['description']];
            $acc=Db::name('permission')->insert($add);
            $arr=['code'=>'1','status'=>'ok','data'=>'添加成功'];
            echo json_encode($arr);
        }
    }
    //删除
    public function delete()
    {
        $id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('Permission')->where('id',$id)->delete();
         if (!empty($ass)) {
            $arr=['code'=>'1','status'=>'ok','data'=>'删除成功'];
            echo json_encode($arr);
         }else{
            $arr=['code'=>'0','status'=>'error','data'=>'删除失败'];
            echo json_encode($arr);
            die;     
        }
    }
    //修改查询
    public function myupdate()
    {
        $id=Request::post('id');
        $ass=Db::query("select p.id,p.name,p.description,p.category_id,p.path,p_c.name as p_c_name,p_c.id as pc_id from permission as p  LEFT JOIN permission_category as p_c on p.category_id=p_c.id where p.id=$id");
        $arr=['code'=>'1','status'=>'ok','data'=>$ass];
        echo json_encode($arr);
    }
    //修改
    public function updateaction(){

        $data=Request::post();
        $rbac= new Rbac();
        $ass=Db::table('Permission')->where('name',$data['name'])->select();
        $ass1=Db::table('Permission')->where('path',$data['path'])->select();
        if (!empty($ass)||!empty($ass1)) {
           $arr=['code'=>'0','status'=>'error','data'=>'修改失败角色或路径已经存在'];
            echo json_encode($arr);
            die;
        }else{
            $ass1=Db::table('Permission')->where('id',$data['id'])->update(['name' => $data['name']],['description' => $data['description']],['category_id' => $data['category_id']],['path' => $data['path']]);
            $arr=['code'=>'1','status'=>'ok','data'=>'添加成功'];
            echo json_encode($arr);
        } 
    }
}