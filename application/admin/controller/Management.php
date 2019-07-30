<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;

class Management extends Common
{
	public function management()
    {
        $id=input('get.id');
        $show=Db::query("select attr_cate from goods where id='$id'");
        $new1=$show[0]['attr_cate'];
        //echo $new1;
        // var_dump($show);
        //echo $id;
        $this->assign('id',$id);
        $this->assign('attr_cate',$new1);
        return $this->fetch();
    }
    public function show1()
    {
        // $id=Request::post('id');
        // $attr_cate=Request::post('attr_cate');
        // echo $id;
        // echo $attr_cate;
    	$rbac= new Rbac();
		//$show=Db::query("select goods_attr.id,goods.id as g_id,goods.`name` as g_name from goods_attr JOIN goods on goods_attr.goods_id=goods.id");
		$show=Db::query("select * from attr_category ");
		$arr=['code'=>'10001','status'=>'ok','data'=>$show];
        echo json_encode($arr);
    }
    public function show2()
    {
    	$id=Request::post('id');
    	if ($id>0) {
			$show=Db::query("select `at`.id,`at`.`name`,atde.id as atde_id,atde.name as atde_name FROM attribute as at JOIN attr_details as atde on at.id=atde.attr_id where `at`.attrcate_id=$id");
			$arr1=[];
			foreach ($show as $key => $value) {
				$arr1[$value['name']][]=$value;	
			}
			$arr2=['code'=>'10001','status'=>'ok','data'=>$arr1];
	        echo json_encode($arr2);
    	}else{
    		$arr2=['code'=>'10000','status'=>'error','data'=>'请先选择分类'];
	        echo json_encode($arr2);
    	}
    }
    
   function add(){
      $id=Request::post('id');
      $sel=Request::post('sel');
      $pemid=Request::post('pemid');//把数组接过来
      if (empty($pemid)) {
         $upd=['attr_cate'=>''];
         $mysqli=Db::table('goods')->where('id',$id)->update($upd);
      }else{
         $upd=['attr_cate'=>$sel];
         $mysqli=Db::table('goods')->where('id',$id)->update($upd);
      }
      $mysqli=Db::table('goods')->where('id',$id)->update($upd);
      Db::query("delete from goods_attr where goods_id='$id'");
      $arr=explode(',', $pemid);//接过来第一个是空值和逗号，去除第一个字符串逗号
      array_shift($arr);//去除第一值
      //$perm=implode(',', $arr);//用逗号来拆分数组
      
      //$sql[]='';
      foreach ($arr as $key => $value) {
      $ar=Db::query("select * from attr_details where id='$value'");

      $sql=$ar[0]["attr_id"];
      $where=['goods_id'=>$id,'attr_details_id'=>$value,'attr_id'=>$sql];
      $mysqli=Db::table('goods_attr')->insert($where);   
      }
      var_dump($ar);
      die;
      $arr2=['code'=>'10001','status'=>'ok','data'=>'添加成功'];
      echo json_encode($arr2);
       
   }

   // function add(){
   //    $id=Request::post('id');
   //    $sel=Request::post('sel');
   //    $pemid=Request::post('pemid');//把数组接过来
   //    if (empty($pemid)) {
   //       $upd=['attr_cat'=>''];
   //       $mysqli=Db::table('kjgoods')->where('id',$id)->update($upd);
   //    }else{
   //       $upd=['attr_cat'=>$sel];
   //       $mysqli=Db::table('kjgoods')->where('id',$id)->update($upd);
   //    }
   //    $mysqli=Db::table('kjgoods')->where('id',$id)->update($upd);
   //    Db::query("delete from goods_attr where goods_id='$id'");
   //    $arr=explode(',', $pemid);//接过来第一个是空值和逗号，去除第一个字符串逗号
   //    array_shift($arr);//去除第一值
   //    //$perm=implode(',', $arr);//用逗号来拆分数组
      
   //    //$sql[]='';
   //    foreach ($arr as $key => $value) {
   //    $ar=Db::query("select * from attr_details where id='$value'");
   //    $sql=$ar[0]["attr_id"];
   //    $where=['goods_id'=>$id,'attr_details_id'=>$value,'attr_id'=>$sql];
   //    $mysqli=Db::table('goods_attr')->insert($where);   
   //    }
       
   // }
}
