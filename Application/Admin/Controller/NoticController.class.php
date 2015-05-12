<?php
//
namespace Admin\Controller;
use Think\Controller;
class NoticController extends Controller {
    public function add(){
    	$this->assign('m6','class="active"');
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
					
		    	$notic = M('Notic');
		    	$notic->create();
		    	$notic->title = I('post.title');
		    	$notic->content = I('post.content');
		    	$notic->creatTime = time_format(NOW_TIME);
		    	$notic->addUser = (int)session("user_id");
		    	$notic->important= I('post.important');
		    	$notic->isDeleted= 0;
					if($notic->add()>0)
					{
						$this->success("新增成功",U('Notic/Manage'));
					}else
					{
						$this->error("新增失败");	
					}
				}
				$this->display();
				return;
			}
			
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function manage(){
    	$this->assign('m7','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
			{
				$map['isDeleted'] = array('eq',0);	
				$notics=M('Notic');
				$n = M('Notic');
				$pagenumber=8;
				
				$maxpage = ceil($n->where($map)->count()/$pagenumber)+1;
				
				$page=$_GET['page'];
				
				if(!isset($page))
				{$page=1;}
				
				$data = $notics->page($page,$pagenumber)->field(true)->where($map)->order('important desc,creatTime desc')->select();
				$this->assign('data',$data);
				
				$this->assign('page',$_GET['page']);
				$this->assign('maxpage',$maxpage);
				//dump($data);
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function delete(){
    	
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
			{
				$map['id'] = I('get.id');	
				$notics=M('Notic');
				$notics->isDeleted = 1;
				
				$notics->where($map)->save();
				$this->success("删除成功",U('Notic/Manage'));
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function edit(){
    	
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
			{
				$map['id'] = I('get.id');
				//dump($map);
				if(IS_POST)
				{
					
		    	$notic = M('Notic');
		    	$notic->title = I('post.title');
		    	$notic->content = I('post.content');
		    	$notic->creatTime = time_format(NOW_TIME);
		    	$notic->addUser = (int)session("user_id");
		    	$notic->important= I('post.important');
		    	$notic->isDeleted= 0;
					if($notic->where($map)->save())
					{
						$this->success("修改成功",U('Notic/Manage'));
					}else
					{
						$this->error("修改失败");	
					}
				}
				$data = M('Notic')->where($map)->select();
				$this->assign("data",$data[0]);
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
}