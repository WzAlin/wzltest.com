<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class User extends Model
{
	use SoftDelete;

	protected $autoWriteTimestamp = true;

	protected function getRoleAttr($value)
	{
		$role = [
			1 => '超级管理员',
			0 => '管理员'
		];
		return $role[$value];
	}

	protected function setPasswordAttr($value)
	{
		return md5($value);
	}
}
