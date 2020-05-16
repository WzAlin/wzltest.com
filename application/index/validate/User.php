<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
	protected $rule = [
		'name|用户名' => 'require|min:3|max:10',
		'password|密码' => 'require|min:4|max:8',
		'repwd|确认密码' =>'require|confirm:password',
		'captcha|验证码' =>'require|captcha',
	];

	protected $scene = [
		'login'   => ['name', 'password', 'captcha'],
		'adduser' => ['name', 'password', 'repwd'],
		'edituser' => ['name', 'password'],
	];
}