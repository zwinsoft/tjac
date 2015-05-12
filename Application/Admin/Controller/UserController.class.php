<?php
//
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller {
    public function profile(){
    	$this->assign('m8','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
			{
				if(IS_POST)
    		{
    			if(I('post.newpwd')==I('post.newpwd2'))
    			{
    				$map['id'] = session("user_id");
    				$us = M('user');
    				$u = $us->where($map)->select();
    				if($u)
    				{
    					//dump($u[0]);
    					if($u[0]['password']	== I('post.password'))
    					{
    							$us->password = I('post.newpwd');
    							$us->where($map)->save();
    							$this->success('修改密码成功');
    					}else
    					{
    						$this->error("修改密码失败:老密码错误");	
    					}
    				}else
    				{
    					$this->error("修改密码失败:无此用户".session("user_id"));	
    				}
    					
    			}
    			
    		}
				$this->display();
				return;
			}
			
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function manageguest(){
    	$this->assign('m9','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
			{
				
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function manageall(){
    	$this->assign('m10','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
			{
				
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
}