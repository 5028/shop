<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;
use app\admin\model\Brand as Brand_Model;

class Brand extends Common
{
    public function brand()
    {
        return $this->fetch();
    }
    public function add()
    {
    	$name=Request::post('name');
        $describe=Request::post('describe');

        $rbac= new Rbac();
        $ass=Db::table('brand')->where('name',$name)->select();
        if (empty($ass)) {
            $add = ['name' => $name,'describe' => $describe];
            $acc=Db::name('brand')->insert($add);
            $res=['code'=>'10010','status'=>'ok','data'=>'添加成功'];
            echo $json=json_encode($res);
        }else{
            $res=['code'=>'10000','status'=>'error','data'=>'添加失败'];
            echo $json=json_encode($res);
        }
    }
    public function upload(){
        $id=Request::post('id');
        echo $id;
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move("./uploads");
        if($info){
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            $img=str_replace("\\","/", $info->getSaveName());
             
            $add = ['logo' => $img];
            $acc=Db::table('brand')->where('id',$id)->update($add);
            if (!empty($acc)) {
                $res=['code'=>'10010','status'=>'ok','data'=>'添加成功'];
                //echo $json=json_encode($res);
                $this->success('新增成功', 'Brand/brand');
            }else{
                $res=['code'=>'10000','status'=>'error','data'=>'添加失败'];
                $this->success('新增失败', 'Brand/brand');
            }
        }
    }
    public function show()
    {
    	$brand = new Brand_Model;
    	$obj=$brand->select();
    	$arr=json_decode($obj,true);
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo $json=json_encode($res);
    }
    //删除
    public function deleteaction()
    {
        $id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('brand')->where('id',$id)->delete();
         if (!empty($ass)) {
            $arr=['code'=>'10010','status'=>'ok','data'=>'删除成功'];
            echo json_encode($arr);
         }else{
            $arr=['code'=>'10000','status'=>'error','data'=>'删除失败'];
            echo json_encode($arr);
            die;     
        }
    }
    public function update()
    {
        $id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('brand')->where('id',$id)->select();
        if (empty($ass)) {
            $res=['code'=>'10000','status'=>'error','data'=>'空'];
            echo $json=json_encode($res);
        }else{
            $res=['code'=>'10010','status'=>'ok','data'=>$ass];
            echo $json=json_encode($res);
        }
    }
    public function updateaction(){
        $data=Request::post();
        $rbac= new Rbac();
        $ass=Db::table('brand')->where('name',$data['name'])->select();
        if (!empty($ass)) {
           $arr=['code'=>'10000','status'=>'error','data'=>'修改失败角色或路径已经存在'];
            echo json_encode($arr);
            die;
        }else{
            $ass1=Db::table('brand')->where('id',$data['id'])->update(['name' => $data['name']],['logo' => $data['logo']],['describe' => $data['describe']]);
            $arr=['code'=>'10010','status'=>'ok','data'=>'修改成功'];
            echo json_encode($arr);
        } 
    }
}