<?php 
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Teacher as TeacherModel;
use app\index\model\Grade as GradeModel;

class Teacher extends Controller
{
	public function teacherList()
	{
		$this->view->assign('title','教师列表');
        $this->view->assign('keywords','教师列表');
        $this->view->assign('desc','教师列表');

		$count = TeacherModel::count();
		$this->assign('count', $count);

		$data = TeacherModel::all();

		foreach ($data as $v) {
			$list = [
				'id'  => $v->id,
				'name'  => $v->name,
				'degree'  => $v->degree,
				'mobile'  => $v->mobile,
				'school'  => $v->school,
				'hiredate'  => $v->hiredate,
				'grade'  => isset($v->grade->name)?$v->grade->name:'<span style="color:red">未分配</span>',
				'status' => $v->status,
			];
			$teacherList[] = $list;
		}

		$this->assign('teacherList', $teacherList);
		return $this->view->fetch();
	}

	public function teacherAdd()
	{
		$this->view->assign('title','教师添加');
        $this->view->assign('keywords','教师添加');
        $this->view->assign('desc','教师添加');

        $gradeList = GradeModel::all();
        $this->assign('gradeList', $gradeList);
        return $this->view->fetch();
	}

	public function addTeacher(Request $request)
	{
		$data = $request->param();
		$res = TeacherModel::create($data,true);
		$this->success('添加成功', 'teacherlist');
	}

	public function setStatus(Request $request)
	{
		$id = $request->param('id');
		$data = TeacherModel::get($id);
		$status = $data->getData('status');

		if (1 == $status) {
			TeacherModel::update(['status'=>0], ['id'=>$id]);
		} else{
			TeacherModel::update(['status'=>1], ['id'=>$id]);
		}
		$this->success('操作成功', 'teacherlist');
	}

	public function delTeacher(Request $request)
	{
		$id = $request->param('id');
		TeacherModel::update(['is_delete'=>1], ['id'=>$id]);
		TeacherModel::destroy($id);
		$this->success('成功删除', 'teacherlist');
	}

	public function unDelete()
	{
		TeacherModel::update(['delete_time'=>NULL], ['is_delete'=>1]);
		$this->redirect('teacherlist');
	}

	public function teacherEdit(Request $request)
	{
  		$this->view->assign('title','编辑');
        $this->view->assign('keywords','编辑');
        $this->view->assign('desc','编辑');

		$id = $request->param('id');

		$teacherInfo = TeacherModel::get($id);

		$gradeList = GradeModel::all();
		$this->assign('gradeList', $gradeList);
  		$this->assign('teacherInfo', $teacherInfo);

		return $this->view->fetch();
	}

	public function editTeacher(Request $request)
	{
		$data = $request->param();

		$TeacherModel = new TeacherModel;
		$res = $TeacherModel->allowField(true)->save($data, ['id'=>$data['id']]);

		$this->success('修改成功', url('teacherlist'));
	}
}
