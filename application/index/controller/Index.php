<?php
namespace app\index\controller;

use think\Controller;
use think\Session;

class Index extends Controller
{
    public function index()
    {
    	$this->isLog();
    	$this->view->assign('title','首页');
        $this->view->assign('keywords','首页');
        $this->view->assign('desc','首页');
        return $this->view->fetch();
    }

    public function isLog()
    {
    	if (null === Session::get('user_info')) {
    		$this->redirect('User/login');
    	}
    }
}
