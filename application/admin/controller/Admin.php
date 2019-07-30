<?php
namespace app\admin\controller;
use Db;
use think\facade\Session;
use gmars\rbac\Rbac;
use Redis;

class Admin extends Common
{
    public function index()
    {
        return $this->fetch();
        foreach ($variable as $key => $value) {
        	# code...
        }
    }
    public function loginOut()
    {
        Session::delete('name');
        $this->success('退出成功','login/login');
    }
    public function index1()
    {

        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
 
        // incr() 对指定的key的值加1
        // decr()对指定的key的值减1
        // incrBy() 将第二个参数的值加到key的值上
        // decrBy() 将第二个参数的值加到key的值上
        // incrByFloat() 自增一个浮点类型的值
        
        echo $redis->incr('shenzhen')."<br/>";//1
        echo $redis->incr('shenzhen')."<br/>";//2
        echo $redis->incrBy('shenzhen',6)."<br/>";//8
        echo $redis->decr('shenzhen')."<br/>";//7
        echo $redis->decr('shenzhen')."<br/>";//6
        echo $redis->decrBY('shenzhen',3)."<br>"; //3
        echo $redis->incrByFloat('shenzhen',0.88);//3.88
 
    }
}