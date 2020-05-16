<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Grade extends Model
{
	use SoftDelete;

	protected $autoWriteTimestamp = true;

	protected function teacher()
	{
		return $this->hasOne('Teacher');
	}

	protected function student()
	{
		return $this->hasMany('Student');
	}
}
