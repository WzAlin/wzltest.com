<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Student extends Model
{
	use SoftDelete;

	protected $autoWriteTimestamp = true;

	protected $dateFormat = 'Y/m/d';

	protected $type = [
		'start_time' => 'timestamp',
	];

	protected function grade()
	{
		return $this->belongsTo('Grade');
	}

	protected function getSexAttr($value)
	{
		$sex = [
			1 => '男',
			0 => '女',
		];
		return $sex[$value];
	}
}
