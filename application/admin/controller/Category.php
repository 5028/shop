<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;


class Category extends Common
{
    public function category()
    {
 	   return $this->fetch();
    }

    private function getTree($array, $pid =0, $level = 0){
    	echo "<ul>";
		foreach ($array as $key => $value){
		    if ($value['pid'] == $pid){
		        $value['name'] = $value['name'];
		        $m_id=$value['id'];
		        echo "<li value='$m_id' id='id".$m_id."'>".$value['name']."</li>";
		        $this->getTree($array, $value['id'], $level+1);
		    }
		}
    	echo "</ul>";
	}
    public function show()
    {
    	$rbac= new Rbac();
		$arr=Db::table('goods_category')->select();
		$this->getTree($arr);
    }
    function addaction(){
    	$data=Request::post();
    	$rbac= new Rbac();
        $ass=Db::table('goods_category')->where('name',$data['name'])->select();
        if (!empty($ass)) {
           $arr=['code'=>'10000','status'=>'error','data'=>'已经存在'];
            echo json_encode($arr);
            die;
        }else{
    		$add = ['name' => $data['name'], 'pid' => $data['pid']];
			$acc=Db::name('goods_category')->insert($add);
	        $arr=['code'=>'10010','status'=>'ok','data'=>'添加成功'];
	        echo json_encode($arr);
        }
    }
    public function delete()
    {
    	$id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('goods_category')->where('id',$id)->delete();
        $ass2=Db::table('goods_category')->where('pid',$id)->delete();
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
        $ass=Db::table('goods_category')->where('id',$id)->select();
        $arr=['code'=>'1','status'=>'ok','data'=>$ass];
        echo json_encode($arr);
    }
    public function updateaction(){
        $id=Request::post('id');
        $name=Request::post('name');
        $description=Request::post('description');
        $ass=Db::table('goods_category')->where('name',$name)->select();
        if (empty($ass)) {

            $ass1=Db::table('goods_category')->where('id',$id)->update(['name' => $name]);

            $arr=['code'=>'1','status'=>'ok','data'=>'修改成功'];
            echo json_encode($arr);
        }else{
            $arr=['code'=>'0','status'=>'error','data'=>'修改失败'];
            echo json_encode($arr);
            die;
        }    
    }

}
