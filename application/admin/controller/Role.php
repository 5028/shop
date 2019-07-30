<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;

class Role extends Common
{
	public function role()
    {
        return $this->fetch();
    }
    public function role_add()
    {
        return $this->fetch();
    }
    public function role_show()
    {
       return $this->fetch();
    }
    
    public function show()
    {
    	$rbac= new Rbac();
		$ass=Db::table('role')->select();
        $json=['code'=>'1','status'=>'ok','data'=>$ass];
        return json($json);
    }
    
    public function add()
    {
    	$arr=Db::query("select p.id,p.name as p_name,p.description,p.path,p_c.name as p_c_name,p_c.id as pc_id from permission as p  LEFT JOIN permission_category as p_c on p.category_id=p_c.id");
    	$ass=[];
    	foreach ($arr as $value) {
    		$ass[$value['p_c_name']][]=[$value['p_name'],$value['id']];
    	}
    	$json=['data'=>$ass];
    	echo json_encode($json);
    }
    

    public function delete()
    {
        $id=Request::post('id');
        $rbacObj = new Rbac();
		$rbacObj->delRole($id);
        $arr=['code'=>'1','status'=>'ok','data'=>'删除成功'];
        echo json_encode($arr);
    }
    public function addaction()
    {
    	$name=Request::post('name');
    	$description=Request::post('description');
    	$id=Request::post('id');
    	$a=substr($id, 1);
    	$rbac=new Rbac();
        $arr=Db::table('role')->where('name','=',$name)->select();
        if (empty($arr)){
            $rbac->createRole([
                'name' => $name,
                'description' => $description,
                'status' => 1
            ], $a);
            $json = ['code'=>'1','status' => 'ok', 'data' => '添加成功'];
            echo json_encode($json);
		    }else{
		        $json = ['code'=>'0','status' => 'error', 'data' =>'角色已经存在'];
		        echo json_encode($json);
		   	}
    	}
    public function role_update1()
    {	
    	$id=Request::post('id');
    	$rbac=new Rbac();
        $name=Db::table('role')->where('id','=',$id)->select();
        $rbac=new Rbac();
        $role=Db::table('role_permission')->where('role_id','=',$id)->select();
    	$arr=Db::query("select p.id,p.name as p_name,p.description,p.path,p_c.name as p_c_name,p_c.id as pc_id from permission as p  LEFT JOIN permission_category as p_c on p.category_id=p_c.id");
    	$ass=[];
    	foreach ($arr as $value) {
    		$ass[$value['p_c_name']][]=[$value['p_name'],$value['id']];
    	}
    	$json=['data'=>$ass,'name'=>$name,'data1'=>$role];
    	echo json_encode($json);
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