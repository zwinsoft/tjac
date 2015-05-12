<?php
//
namespace Admin\Controller;
use Think\Controller;
class DataController extends Controller {
    public function Statics(){
    	$this->assign('m4','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/index');
		$this->display();
		return;
	}
	if(is_AdminLogin())
	{
		$this->display();
		return;
	}
	if(is_GuestLogin())
	{
		$this->display();
		return;
	}
	if(is_OperLogin())
	{
		$this->display();
		return;
	}
	
	$this->error('您还没有登录，请先登录！', U('User/login'));
    }
    public function Export(){
    	$this->assign('m5','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/index');
		$this->display();
		return;
	}
	if(is_AdminLogin())
	{
		$this->display();
		return;
	}
	if(is_GuestLogin())
	{
		$this->display();
		return;
	}
	if(is_OperLogin())
	{
		$this->display();
		return;
	}
	
	$this->error('您还没有登录，请先登录！', U('User/login'));
    }
}