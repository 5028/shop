<?php
namespace app\admin\controller;
use app\index\model\User;
use think\captcha\Captcha;
use think\facade\Session;
use Db;
use think\Controller;
use gmars\rbac\Rbac;

class Login extends Controller
{
    public function login()
    {
        return $this->fetch();
    }
    public function check_login()
    {
        $name = input('get.name');
        $pwd = input('get.password');
        $code = input('get.code');
        $arr = Db::table('user')->where('user_name', $name)->where('password', $pwd)->find();
        if (empty($arr)) {
            die;
        } else {
            $captcha = new \think\captcha\Captcha();
            if (!$captcha->check($code)) {
                echo "error";
            } else {
                $id = $arr['id'];
                $name = $arr['user_name'];
                $rbac= new Rbac();
                $rbac->cachePermission($id);
                Session::set('id',$id);
                Session::set('name',$name);
                echo "ok"; 
            }
        }
    }
}