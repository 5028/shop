<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;

class Goods extends Common
{
	public function goods()
    {
      $id=input('get.id');
      $this->assign('id',$id);
      return $this->fetch();
    }
   	public function show()
   	{
     	$rbac= new Rbac();
  		$show=Db::query("select goods.id as g_id,goods.`name` as g_name,goods_product.id from goods join goods_product on goods.id=goods_product.goods_id");
  		$arr=['code'=>'10001','status'=>'ok','data'=>$show];
      echo json_encode($arr);
   	}

   	public function show1()
   	{
      $rbac = new Rbac();
      $show=Db::query("select attribute.id,attribute.`name`,attr_details.id as at_id,attr_details.`name` as at_name from attribute JOIN attr_details on attribute.id=attr_details.attr_id");
      $arr=['code'=>'10001','status'=>'ok','data'=>$show];
      echo json_encode($arr);
   	}
   	public function show2()
   	{
      $id=Request::post('id');
      //echo "123";
   		//$rbac= new Rbac();
		  $show=Db::query("select * from goods_product where goods_id=$id");
      //var_dump($show);

      for ($i=0; $i <count($show) ; $i++) { 
         $abb='';
        $arr=$show[$i]['goods_attr_id'];
        $new_attr=explode('-', $arr);
        for ($j=0; $j <count($new_attr) ; $j++) {  
         
            $new_de=Db::query("select * from attr_details where id='$new_attr[$j]'");
            $abb=$abb.' '.$new_de[0]['name'];
        }
       $show[$i]['key']=$abb;

      }
      //var_dump($show);  
		  $arr1=['code'=>'10001','status'=>'ok','data'=>$show];
      echo json_encode($arr1);
   	}
    public function add()
    {
      $attr_id='';
      $data=Request::post();
      //var_dump($data);
      //echo $data['price'];
      $arr = array($data['select'],$data['select1']);
      $attr_id=implode("-",$arr);
      //echo $attr_id;
      if ($data['price'] && $data['number'] != '') {
          $add = ['goods_id' => $data['id'], 'goods_attr_id' => $attr_id, 'price' => $data['price'], 'sn_number' => $data['number']];
          $acc=Db::name('goods_product')->insert($add);
          $ccc=['code'=>'10001','status'=>'ok','data'=>'添加成功'];
          echo json_encode($ccc);
      }else{
          $arr1=['code'=>'10000','status'=>'error','data'=>'抱歉，删除失败'];
          echo json_encode($arr1);
          die;
      }
    }
    public function deleteaction()
    {
        $id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('goods_product')->where('id',$id)->delete();
         if (!empty($ass)) {
            $arr=['code'=>'1','status'=>'ok','data'=>'删除成功'];
            echo json_encode($arr);
         }else{
            $arr=['code'=>'0','status'=>'error','data'=>'抱歉，删除失败'];
            echo json_encode($arr);
            die;     
        }
    }


}
