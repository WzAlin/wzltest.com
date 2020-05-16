<?php 
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Grade as GradeModel;

class Grade extends Controller
{
	public function gradeList()
	{
		$this->view->assign('title','班级列表');
        $this->view->assign('keywords','班级列表');
        $this->view->assign('desc','班级列表');

		$count = GradeModel::count();
		$this->assign('count', $count);

		$list = GradeModel::all();
		foreach ($list as $v) {
			$data = [
				'id' => $v->id,
				'name' => $v->name,
				'length' => $v->length,
				'price' => $v->price,
				'teacher' => isset($v->teacher->name)?$v->teacher->name:'<span style="color:red">未分配</span>',
				'status' => $v->status,
			];
			$glist[] = $data;
		}
		
		$this->assign('list', $glist);
		return $this->view->fetch();


	}

	public function gradeAdd()
	{
		$this->view->assign('title','添加班级');
        $this->view->assign('keywords','添加班级');
        $this->view->assign('desc','添加班级');
		return $this->view->fetch();
	}

	public function addGrade(Request $request)
	{
		$data = $request->param();
		
		$res = GradeModel::create($data, true);
		$this->success('添加成功', 'gradelist');
	}

	public function setStatus(Request $request)
	{
		$id = $request->param('id');
		$data = GradeModel::get($id);
		$status = $data->getData('status');

		if (1 == $status) {
			GradeModel::update(['status'=>0], ['id'=>$id]);
		} else{
			GradeModel::update(['status'=>1], ['id'=>$id]);
		}
		$this->success('操作成功', 'gradelist');
	}

	public function delGrade(Request $request)
	{
		$id = $request->param('id');
		GradeModel::update(['is_delete'=>1], ['id'=>$id]);
		GradeModel::destroy($id);
		$this->success('成功删除', 'gradelist');
	}

	public function unDelete()
	{
		GradeModel::update(['delete_time'=>NULL], ['is_delete'=>1]);
		$this->redirect('gradelist');
	}

	public function gradeEdit(Request $request)
	{
  		$this->view->assign('title','编辑班级');
        $this->view->assign('keywords','编辑班级');
        $this->view->assign('desc','编辑班级');

		$id = $request->param('id');

		$info = GradeModel::get($id);
		$info->teacher = isset($info->teacher->name)?$info->teacher->name:'未分配';
  		$this->assign('info', $info);

		return $this->view->fetch();
	}

	public function editGrade(Request $request)
	{
		$data = $request->param();

		$GradeModel = new GradeModel;
		$res = $GradeModel->allowField(true)->save($data, ['id'=>$data['id']]);

		$this->success('修改成功', url('gradelist'));
	}
}
