<?php
namespace app\index\controller;
use Db;
class Login
{
    public function login()
    {
        echo "前台登陆页面";
    }
    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}