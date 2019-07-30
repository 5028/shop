<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;

class Managers extends Common
{
	public function managers()
    {
        return $this->fetch();
    }
    public function managers_add()
    {
        return $this->fetch();
    }
    public function show()
    {
    	$rbac= new Rbac();
    	$arr=Db::query("select `user`.id as u_id,`user`.user_name as u_name,`user`.mobile as u_mobile,role.id as r_id,role.`name` as r_name FROM `user` JOIN user_role ON `user`.id=user_role.user_id JOIN role ON user_role.role_id=role.id");
        $json=['code'=>'1','status'=>'ok','data'=>$arr];
        return json($json);
    }
    public function show1()
    {
    	$rbac= new Rbac();
		$ass=Db::query("select * from role");
        $json=['code'=>'1','status'=>'ok','data'=>$ass];
        return json($json);
    }
    public function add()
    {
    	$name=Request::post('name');
    	$password=Request::post('password');
    	$mobile=Request::post('mobile');
    	$cate=Request::post('cate');
    	$rbac=new Rbac();
        $arr=Db::table('user')->where('user_name','=',$name)->select();
        if (empty($arr)){
            $add = ['user_name' => $name,'password' => $password,'mobile' => $mobile ];
			$acc=Db::name('user')->insert($add);

			$ass=Db::table('user')->where('user_name',$name)->select();
			
			$add = ['user_id' => $ass[0]['id'],'role_id' => $cate];
			$acc=Db::name('user_role')->insert($add);

            $json = ['code'=>'1','status' => 'ok', 'data' => '添加成功'];
            echo json_encode($json);
		}else{
	        $json = ['code'=>'0','status' => 'error', 'data' =>'用户已经存在'];
	        echo json_encode($json);
		}
   	}
   	public function delete()
   	{
   		$id=Request::post('id');
   		$ass=Db::table('user')->where('id',$id)->delete();
   		$ass=Db::table('user_role')->where('user_id',$id)->delete();
        $arr=['code'=>'1','status'=>'ok','data'=>'删除成功'];
        echo json_encode($arr);
   	}
   	public function myupdate()
    {
        $id=Request::post('id');
        $ass=Db::query("select `user`.id as u_id,`user`.user_name as u_name,`user`.mobile as u_mobile,user_role.user_id as ur_u_id,user_role.role_id as ur_r_id,role.id as r_id,role.`name` as r_name FROM `user` JOIN user_role ON `user`.id=user_role.user_id JOIN role ON user_role.role_id=role.id where `user`.id=$id");
        $arr=['code'=>'1','status'=>'ok','data'=>$ass];
        echo json_encode($arr);
    }
    //修改
    public function updateaction(){
        $data=Request::post();
       	$rbac= new Rbac();
        $ass=Db::table('user')->where('user_name',$data['name'])->select();
        if (!empty($ass)) {
           $arr=['code'=>'0','status'=>'error','data'=>'修改失败角色已经存在'];
            echo json_encode($arr);
            die;
        }else{
			Db::table('user')->where('id',$data['id'])->update(['user_name' => $data['name']],['mobile' => $data['mobile']]);
			Db::table('user_role')->where('user_id',$data['id'])->update(['role_id' => $data['role_id']]);
            $arr=['code'=>'1','status'=>'ok','data'=>'修改成功'];
            echo json_encode($arr);
        } 
    }

}