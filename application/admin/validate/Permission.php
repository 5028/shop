<?php

namespace app\admin\validate;

use think\Validate;

class Permission extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name' => 'require|max:50|min:1',
        'description' => 'require|max:200|min:1',
    ];
         
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '名称必须',
        'name.max' => '最多50个',
        'name.min' => '最少1个',
        'description.require' => '名称必须',
        'description.max' => '最多200个',
        'description.min' => '最少1个',
    ];
}
