<?php
namespace app\admin\controller;
use Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;
use think\Image;
use Redis;

class Product extends Common
{
	public function product()
    {
        return $this->fetch();
    }
    public function product_img()
    {
        $id=input('get.id');
        $this->assign('id',$id);
        return $this->fetch();
    }
    public function show()
    {
    	//$rbac= new Rbac();
		$arr=Db::query("select goods.id,goods.`name`,brand.`name` as b_name,goods_category.`name` as gc_name FROM goods join brand on goods.brand_id=brand.id INNER JOIN goods_category ON goods.goodscate_id=goods_category.id limit 0,30");
        $json=['code'=>'10010','status'=>'ok','data'=>$arr];
        return json($json);
    }
    public function brand_show()
    {
        $rbac= new Rbac();
        $arr=Db::query("select * from brand");
        $json=['code'=>'10010','status'=>'ok','data'=>$arr];
        return json($json);  
    }
    public function cate_show()
    {
        $rbac= new Rbac();
        $arr=Db::query("select * from goods_category");
        $json=['code'=>'10010','status'=>'ok','data'=>$arr];
        return json($json);  
    }
    public function add()
    {
        $data=Request::post();
        $rbac= new Rbac();
        $ass=Db::table('goods')->where('name',$data['name'])->select();
        if (!empty($ass)||!empty($ass1)) {
           $arr=['code'=>'0','status'=>'error','data'=>'商品已经存在'];
            echo json_encode($arr);
            die;
        }else{
            $add = ['name' => $data['name'], 'brand_id' => $data['select'],'goodscate_id' => $data['selectaction']];
            $acc=Db::name('goods')->insert($add);
            $redis = new Redis();
            $redis->connect('127.0.0.1',6379);
            $redis->del('list');
            $redis->del('cishu');
            $arr=['code'=>'1','status'=>'ok','data'=>'添加成功'];
            echo json_encode($arr);
        }
    }
    public function deleteaction()
    {
        

        $id=Request::post('id');
        $rbac= new Rbac();
        $ass=Db::table('goods')->where('id',$id)->delete();
         if (!empty($ass)) {
            $redis = new Redis();
            $redis->connect('127.0.0.1',6379);
            $redis->del('list');
            $redis->del('cishu');
            $arr=['code'=>'1','status'=>'ok','data'=>'删除成功'];
            echo json_encode($arr);
         }else{
            $arr=['code'=>'0','status'=>'error','data'=>'抱歉，删除失败'];
            echo json_encode($arr);
            die;     
        }
    }
    
    public function img_show()
    {
        $id=Request::post('id');
        $rbac= new Rbac();
        $arr=Db::query("select goods.id as g_id, goods.`name`,goods_img.id as gimg_id, goods_img.big_img,goods_img.middle_img,goods_img.small_img FROM goods JOIN goods_img on goods.id=goods_img.goods_id where goods_id=$id");
        $json=['code'=>'10010','status'=>'ok','data'=>$arr];
        return json($json);
    }
    public function img_add(){
        $id=input('post.id');
        $file = request()->file('file');
        foreach ($file as $key => $value) {
            $info = $value->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move("./uploads");
        }  
        if($info){
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            $img=str_replace("\\","/", $info->getSaveName());
            $image = \think\Image::open('./uploads/'.$img);
            $names= $info->getFilename();
            $big="big/big".$names;
            $middle="middle/middle".$names;
            $small="small/small".$names;
            $add = ['big_img' => $big,'middle_img' => $middle,'small_img' => $small,'goods_id' => $id];
            Db::table('goods_img')->insert($add);
            $image->thumb(150,150)->save('./uploads/big/big'.$names);
            $image->thumb(100,100)->save('./uploads/middle/middle'.$names);
            $image->thumb(50,50)->save('./uploads/small/small'.$names);
            }else{
                echo "文件太大";
            }
    }
    public function img_delete()
    {
        $id=Request::post('gimg_id');
        $rbac= new Rbac();
        $ass=Db::table('goods_img')->where('id',$id)->delete();
         if (!empty($ass)) {
            $arr=['code'=>'10010','status'=>'ok','data'=>'删除成功'];
            echo json_encode($arr);
         }else{
            $arr=['code'=>'10000','status'=>'error','data'=>'抱歉，删除失败'];
            echo json_encode($arr);
            die;     
        }
    }
    public function lookup()
    {   
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        $haxi=$redis->ZREVRANGE ('rank:2018',0,4);
        $lookup=Request::post('lookup');
        $arr=Db::query("select goods.id,goods.`name`,brand.`name` as b_name,goods_category.`name` as gc_name FROM goods join brand on goods.brand_id=brand.id INNER JOIN goods_category ON goods.goodscate_id=goods_category.id WHERE goods.`name` like '%".$lookup."%'");
        $arr1=json_encode($arr);
        $redis->Hset('list',$lookup,$arr1);
        $name=$redis->Hget('cishu',$lookup);

        if ($name=='') {
            
            $redis->Hset('cishu',$lookup,1);
            $json=['code'=>'1','status'=>'ok','data'=>$arr,'aa'=>$haxi];
            return json($json);
        }else{
            $new_name=$name+1;
            $redis->ZINCRBY ('rank:2018',$name,$lookup);
            
            $redis->Hset('cishu',$lookup,$new_name);
            if ($name>=5) {
                $ayy=$redis->Hget('list',$lookup);
                $ayy1=json_decode($ayy);
                $json=['code'=>'0','status'=>'ok','pl'=>'redis','data'=>$ayy1,'aa'=>$haxi];
                return json($json);
                die;
            }
            $json=['code'=>'1','status'=>'ok','data'=>$arr,'aa'=>$haxi];
            return json($json);
        }
    }
    function get_Char($num = 10000)  // $num为生成汉字的数量
    {
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));            // 转码
            $b = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));            // 转码
            $c = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));            // 转码
            $a1= iconv('GB2312', 'UTF-8', $a);
            $b1= iconv('GB2312', 'UTF-8', $b);
            $c1= iconv('GB2312', 'UTF-8', $c);
            $d=$a1.$b1.$c1;
            echo $d;
            echo "<br>";
            $add = ['name' => $d, 'brand_id' => '78','goodscate_id' => '1'];
            $acc=Db::name('goods')->insert($add);
            
        }
        
    }
    function new_get_Char($num = 100)  // $num为生成汉字的数量
    {
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));            // 转码
            $b = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));            // 转码
            $c = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));            // 转码
            $a1= iconv('GB2312', 'UTF-8', $a);
            $b1= iconv('GB2312', 'UTF-8', $b);
            $c1= iconv('GB2312', 'UTF-8', $c);
            $d=$a1.$b1.$c1;
            echo $d;
            echo "<br>"; 
        }
        $add = ['name' => $d, 'brand_id' => '78','goodscate_id' => '1'];
        $acc=Db::name('goods')->insert($add);
        
    }

}
