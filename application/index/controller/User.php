<?php 
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Loader;
use app\index\model\User as UserModel;

class User extends Controller
{
	public function login()
	{
		$this->view->assign('title','登录页面');
        $this->view->assign('keywords','登录');
        $this->view->assign('desc','登录');
		return $this->view->fetch();
	}

	public function checkLogin(Request $request)
	{
		$data = $request->param();
		
        $validate = Loader::validate('User');

        $status = 0;
        $msg = '验证码错误';

		if($validate->scene('login')->check($data)){

			$res = [
				'name' => $data['name'],
				'password' => md5($data['password'])
			];

			$info = UserModel::get($res);

			if (null == $info) {
				$msg = '用户名或密码错误，请检查';
			} else{
				Session::set('user_info', $info->toArray());
				$status = 1;
				$msg = '登录成功';
			}
		}
		
		return ['status'=>$status,'message'=>$msg];
	}

	public function logout()
	{
		Session::delete('user_info');
		$this->redirect(url('user/login'));
	}

	public function adminList()
	{
		$this->view->assign('title','管理员列表');
        $this->view->assign('keywords','管理员列表');
        $this->view->assign('desc','管理员列表');

		$count = UserModel::count();
		$this->assign('count', $count);

		$info = Session::get('user_info');

		if ('超级管理员' == $info['role']) {
			$list = UserModel::all();
		} else{
			$list = UserModel::all($info['id']);
		}

		$this->assign('list', $list);
		return $this->view->fetch();
	}

	public function adminAdd()
	{
		$this->view->assign('title','添加管理员');
        $this->view->assign('keywords','添加管理员');
        $this->view->assign('desc','添加管理员');
		return $this->view->fetch();
	}

	public function addUser(Request $request)
	{
		$data = $request->param();
		
		$validate = Loader::validate('User');
		if (!$validate->scene('adduser')->check($data)) {
			$this->error($validate->getError());
		}

		$res = UserModel::create($data, true);
		if (null == $res) {
			$this->error('添加失败，请重试');
		} else{
			$this->success('添加成功', url('adminList'));
		}
	}

	public function setStatus(Request $request)
	{
		$id = $request->param('id');
		$data = UserModel::get($id);
		$status = $data->getData('status');

		if (1 == $status) {
			UserModel::update(['status'=>0], ['id'=>$id]);
		} else{
			UserModel::update(['status'=>1], ['id'=>$id]);
		}
		$this->success('操作成功', 'adminlist');
	}

	public function delUser(Request $request)
	{
		$id = $request->param('id');
		UserModel::update(['is_delete'=>1], ['id'=>$id]);
		UserModel::destroy($id);
	}

	public function adminRenew()
	{

		$count = UserModel::onlyTrashed()->count();
		$this->assign('count', $count);

		$list = USerModel::onlyTrashed()->select();
		$this->assign('list', $list);
		return $this->view->fetch();	
	}

	public function unDelete(Request $request)
	{
		$id = $request->param('id');
		UserModel::update(['delete_time'=>NULL], ['id'=>$id]);
	}

	public function adminEdit(Request $request)
	{
		$id = $request->param('id');
		$info = UserModel::get($id);
  		$this->assign('info', $info);
  		$this->view->assign('title','编辑管理员');
        $this->view->assign('keywords','编辑管理员');
        $this->view->assign('desc','编辑管理员');
		return $this->view->fetch();
	}

	public function editUser(Request $request)
	{
		$data = $request->param();

		$validate = Loader::validate('User');
		if (!$validate->scene('edituser')->check($data)) {
			$this->error($validate->getError());
		}

		$userModel = new UserModel;
		$res = $userModel->allowField(true)->save($data, ['id'=>$data['id']]);

		if (0 == $res) {
			$this->error('失败，请重试');
		} else{
			$this->success('修改成功', url('adminlist'));
		}
	}
}
