<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Student as StudentModel;

class Student extends Controller
{
	public function studentList()
	{
		$count = StudentModel::count();
		$this->assign('count', $count);

		$data = StudentModel::where(1)->paginate(10);
		$page = $data->render();
		$this->assign('page', $page);

		foreach ($data as $v) {
			$list = [
				'id'=>$v->id,
				'name'=>$v->name,
				'sex'=>$v->sex,
				'age'=>$v->age,
				'mobile'=>$v->mobile,
				'email'=>$v->email,
				'grade'=>isset($v->grade->name)?$v->grade->name:'<span style="color:red">待定</span>',
				'start_time'=>$v->start_time,
				'status'=>$v->status,
			];

			$studentList[] = $list;
		}
			$this->assign('studentList', $studentList);
			return $this->view->fetch();
	}

	public function studentAdd()
	{
		$gradeList = \app\index\model\Grade::all();
		$this->assign('gradeList', $gradeList);
		return $this->view->fetch();
	}

	public function addStudent(Request $request)
	{
		$data = $request->param();
		$res = StudentModel::create($data, true);
		$this->success('添加成功', 'studentList');
	}

	public function setStatus(Request $request)
	{
		$id = $request->param('id');
		$data = StudentModel::get($id);
		$status = $data->getData('status');

		if (1 == $status) {
			StudentModel::update(['status'=>0], ['id'=>$id]);
		} else{
			StudentModel::update(['status'=>1], ['id'=>$id]);
		}
		$this->success('操作成功', 'studentList');
	}

	public function delStudent(Request $request)
	{
		$id = $request->param('id');
		StudentModel::update(['is_delete'=>1], ['id'=>$id]);
		StudentModel::destroy($id);
		$this->success('成功删除', 'studentList');
	}

	public function unDelete()
	{
		StudentModel::update(['delete_time'=>NULL], ['is_delete'=>1]);
		$this->redirect('studentList');
	}

	public function studentEdit(Request $request)
	{
		$id = $request->param('id');
		$info = StudentModel::get($id);
		$this->assign('info', $info);

		$gradeList = \app\index\model\Grade::all();
		$this->assign('gradeList', $gradeList);

		return $this->view->fetch();
	}

	public function editStudent(Request $request)
	{
		$data = $request->param();

		$studentModel = new StudentModel;
		$res = $studentModel->allowField(true)->save($data, ['id'=>$data['id']]);

		if (0 == $res) {
			$this->error('失败，请重试');
		}
		$this->success('完成', 'studentList');
	}
}