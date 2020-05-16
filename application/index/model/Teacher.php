<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Teacher extends Model
{
	use SoftDelete;

	protected $autoWriteTimestamp = true;

	protected $type = [
        'hiredate' => 'timestamp',
    ];

    protected $dateFormat = 'Y-m-d';

	protected function grade()
	{
		return $this->belongsTo('Grade');
	}

	protected function getDegreeAttr($value)
	{
		$degree = [
			0 => '大专',
			1 => '本科',
			2 => '研究生'
		];

		return $degree[$value];
	}
}
