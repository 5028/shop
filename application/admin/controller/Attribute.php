<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;

class Attribute extends Common
{
	public function attribute()
    {
        return $this->fetch();
    }
    public function category()
    {
        return $this->fetch();
    }
    public function details()
    {
        return $this->fetch();
    }
    //attr_category（类别）表增删改查！
    //<br>
    //category展示
    public function category_show()
    {
    	$rbac= new Rbac();
		$show=Db::table('attr_category')->select();
		$arr=['code'=>'10001','status'=>'ok','data'=>$show];
        echo json_encode($arr);
    }
    //category添加
    public function category_add()
    {
    	$name=Request::post('name');
    	$rbac= new Rbac();
		$attr_select=Db::table('attr_category')->where('name',$name)->select();
		if (!empty($attr_select)) {
           	$arr=['code'=>'10000','status'=>'error','data'=>'分类已经存在'];
            echo json_encode($arr);
            die;
        }else{
        	$arr = ['name' => $name];
        	Db::name('attr_category')->insert($arr);
        	$arr=['code'=>'10001','status'=>'ok','data'=>'添加成功'];
            echo json_encode($arr);
        }
    }
    //category删除
    public function category_delete()
    {
    	$id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('attr_category')->where('id',$id)->delete();
        $arr=['code'=>'10001','status'=>'ok','data'=>'删除成功'];
        echo json_encode($arr);
    }
    public function category_updateaction()
    {
    	$id=Request::post('id');
    	$name=Request::post('name');
    	$ass=Db::table('attr_category')->where('name',$name)->select();
    	if (!empty($ass)) {
            $arr=['code'=>'10000','status'=>'error','data'=>'分类已经存在'];
        	echo json_encode($arr);
         }else{
            $ass1=Db::table('attr_category')->where('id',$id)->update(['name' => $name]);
    		$arr=['code'=>'10001','status'=>'ok','data'=>'修改成功'];
        	echo json_encode($arr);   
        }
    }
    //attribute表添加,这里需要查询attribute表里根据attrcate_id查找是否有重复name.一个
    public function attribute_add()
    {
    	$id=Request::post('id');
    	$name=Request::post('name');
    	if ($id>0) {
    		$rbac= new Rbac();
			$show=Db::query("select * from attribute where name='$name' and attrcate_id=$id");
			if (empty($show)) {
				$arr = ['name' => $name,'attrcate_id' => $id];
		    	Db::name('attribute')->insert($arr);
		    	$res=['code'=>'10001','status'=>'ok','data'=>'添加成功'];
		        echo json_encode($res);
			}else{
				$res=['code'=>'10000','status'=>'error','data'=>'添加失败,已有属性'];
		        echo json_encode($res);
    	}
    	}else{
    		$res=['code'=>'10000','status'=>'error','data'=>'请选择分类'];
		        echo json_encode($res);
    	} 	
    }
    public function attribute_show()
    {
    	
		$show=Db::query("select attr_c.id,attr_c.`name`,`at`.id as at_id,`at`.`name` as at_name from attr_category as attr_c JOIN attribute as at on attr_c.id=`at`.attrcate_id");
		$arr=['code'=>'10001','status'=>'ok','data'=>$show];
        echo json_encode($arr);
    }
    public function attribute_show_select()
    {
    	$id=Request::post('id');
    	if ($id>0) {
    		$rbac= new Rbac();
			$show=Db::query("select * from attribute where attrcate_id=$id");
			$arr=['code'=>'10001','status'=>'ok','data'=>$show];
	        echo json_encode($arr);
    	}else{
    		$arr=['code'=>'10001','status'=>'error','data'=>'请先选择分类'];
	        echo json_encode($arr);
    	}
    	
    }
    public function attribute_show1()
    {
    	$rbac= new Rbac();
		$show=Db::query("select attribute.id,attribute.`name`,attr_category.name as cate_name,attr_category.id as cate_id from attr_category JOIN attribute on attr_category.id=attribute.attrcate_id");
		$arr=['code'=>'10001','status'=>'ok','data'=>$show];
        echo json_encode($arr);
    }
    public function attribute_deleteaction()
    {
    	$id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('attribute')->where('id',$id)->delete();
        $arr=['code'=>'10001','status'=>'ok','data'=>'删除成功'];
        echo json_encode($arr);
    }
    //details表
    public function details_add()
    {
    	$cate_id=Request::post('id');
    	$id=Request::post('id1');
    	$name=Request::post('name');
    	if ($id<=0 || $cate_id<=0) {
    		$res=['code'=>'10000','status'=>'error','data'=>'请选择分类'];
		        echo json_encode($res);
    	}else{
    		$rbac= new Rbac();
			$show=Db::query("select * from attribute where name='$name' and attrcate_id=$id");
			if (empty($show)) {
				$arr = ['name' => $name,'attr_id' => $id];
		    	Db::name('attr_details')->insert($arr);
		    	$res=['code'=>'10001','status'=>'ok','data'=>'添加成功'];
		        echo json_encode($res);
			}else{
				$res=['code'=>'10000','status'=>'error','data'=>'添加失败,已有属性'];
		        echo json_encode($res);
    		}
    	} 	
    }
    public function details_show()
    {
    	$rbac= new Rbac();
		$show=Db::query("select attr_details.`name`,attr_details.id,attribute.`name` as attr_name,attr_category.`name` as cate_name from attr_category JOIN attribute on attr_category.id=attribute.attrcate_id JOIN attr_details on attribute.id=attr_details.attr_id");
		$arr=['code'=>'10001','status'=>'ok','data'=>$show];
        echo json_encode($arr);
    }
    public function details_deleteaction()
    {
    	$id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('attr_details')->where('id',$id)->delete();
        $arr=['code'=>'10001','status'=>'ok','data'=>'删除成功'];
        echo json_encode($arr);
    }


  //   public function show()
  //   {
  //   	$rbac= new Rbac();
		// $arr=Db::query("select attr_category.id as a_id,attr_category.`name` as a_name,attr_details.`name` as ad_name,attribute.`name` as att_name,attr_details.id as ad_id FROM attr_category JOIN attribute ON attr_category.id=attribute.attrcate_id JOIN attr_details ON attr_details.attr_id=attribute.id");
  //       $json=['code'=>'10010','status'=>'ok','data'=>$arr];
  //       return json($json);
  //   }
  //   public function add()
  //   {
  //   	$data=Request::post();
  //   	$rbac= new Rbac();
  //   	$ass=Db::table('attr_category')->where('name',$data['name'])->select();
  //   	//$arr=Db::table('attribute')->where('name',$data['attr'])->select();
  //   	//$attr=Db::table('attr_details')->where('name',$data['cate'])->select();
  //   	if (!empty($ass)) {
  //          	$arr=['code'=>'0','status'=>'error','data'=>'商品已经存在'];
  //           echo json_encode($arr);
  //           die;
  //       }else{
  //       	$rbac= new Rbac();
  //       	//添加attr_category
  //           $add = ['name' => $data['name']];
  //           Db::name('attr_category')->insert($add);
  //           //查询取name的ID用于存到attribute！
  //           $ass=Db::table('attr_category')->where('name',$data['name'])->select();
  //           //name的id为  $ass[0]['id']
  //           $add1 = ['name' => $data['attr'],'attrcate_id' => $ass[0]['id'],];
  //           //添加attribute表 name attrcate_id
  //           Db::name('attribute')->insert($add1);
  //           //定义变量 这里不可直接写入下面的SQL语句中！
  //           $cc=$ass[0]['id'];
  //           $bb=$data['attr'];
		// 	$arr1=Db::query("select * from attribute where attrcate_id = '$cc' and name = '$bb'");
  //           $attr_id=$arr1[0]['id'];
		// 	//拿到attribut表的id以后添加到attr_details表
  //           $arr2 = ['name' => $data['cate'],'attr_id' => $attr_id];
  //           Db::name('attr_details')->insert($arr2);
  //           $ard=['code'=>'1','status'=>'ok','data'=>'添加成功'];
  //           echo json_encode($ard);
  //       }
  //   }

}
