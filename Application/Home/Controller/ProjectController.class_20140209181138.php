<?php
//
namespace Home\Controller;
use Think\Controller;
class ProjectController extends Controller {
    public function info1(){
    	$this->assign('m8','class="active"');
    	if(is_Complogin())
    	{
		$this->display();
		return;
	}
	if(is_AdminLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_GuestLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_OperLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	
	$this->error('您还没有登录，请先登录！', U('User/login'));
    }
    public function info2(){
    	$this->assign('m9','class="active"');
    	if(is_Complogin())
	{
		$this->display();
		return;
	}
	if(is_AdminLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_GuestLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_OperLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	
	$this->error('您还没有登录，请先登录！', U('User/login'));
    }
    public function confirm(){
    	$this->assign('m10','class="active"');
    	if(is_Complogin())
    	{
		$this->display();
		return;
	}
	if(is_AdminLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_GuestLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_OperLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	
	$this->error('您还没有登录，请先登录！', U('User/login'));
    }
    public function finish(){
    	$this->assign('m11','class="active"');
    	if(is_Complogin())
    	{
		$this->display();
		return;
	}
	if(is_AdminLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_GuestLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	if(is_OperLogin())
	{
		$this->redirect('Admin/Index/index');
		return;
	}
	
	$this->error('您还没有登录，请先登录！', U('User/login'));
    }
}