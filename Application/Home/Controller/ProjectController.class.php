<?php
//
namespace Home\Controller;
use Think\Controller;
class ProjectController extends Controller {
    public function info1(){
    	$this->assign('m8','class="active"');
    	if(is_Complogin())
    	{
    		if(IS_POST)
				{
					if(I("post.name") == "")
					{
						$this->error("项目名称必填");
						return; 
					}
					$data = $_POST ;
					$data["updateTime"] = time_format(NOW_TIME);
					$info = M("Project");
								
					
					$info->create($data);
					if($info->where("id=".session("user_id"))->count()>0)
					{
						$info->auto($rules)->save();
					}
					else
					{
						$info->auto($rules)->add();
					}
					if(I('post.isnext')=="1")
					{
						$this->success("保存成功，请上传商业计划书", U('Project/Info2'));	
					}else
					{
						$this->success("保存成功");
					}
					//$this->success("保存成功");
				}else
				{
				$i =M("Project");
				$vo = $i->where("id=".session("user_id"))->find();
				$others = M("Projectother")->where("isDeleted=0 and uid=".session("user_id"))->select();
				$company =M('company')->where('id='.session("user_id"))->find();
				$info =M('info')->where('id='.session("user_id"))->find();
				
				$vo["fzeName"]=$info["xiangmu_xingming"];
				$vo["fzeMobile"]=$info["xiangmu_mobile"];
				$vo["fzeTitle"]=$info["xiangmu_zhicheng"];
				$vo["fzeEmail"]=$info["xiangmu_email"];
				$this->assign("vo",$vo);
				$this->assign("others",$others);
				
				$this->assign('data',$company);
				$this->display();
				}
				return;
			}
			if(is_AdminLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_GuestLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_OperLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function info2(){
    	$this->assign('m9','class="active"');
    	if(is_Complogin())
			{
				$company =M('company')->where('id='.session("user_id"))->find();
				$this->assign('data',$company);
				

				$f = M("file")->where('type=14 and uploadUser='.session("user_id"))->find();
				$this->assign("f",$f);
				$this->display();

				return;
			}
			if(is_AdminLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_GuestLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_OperLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
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
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_GuestLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_OperLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
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
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_GuestLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_OperLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function save()
    {
    	if(!is_Complogin())
    	{
				$this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
			}
  		if(IS_POST)
			{
				$data = $_POST ;
				$data["updateTime"] = time_format(NOW_TIME);
    		$info = M("Projectother");
							
				$rules = array ( 
						array('updateTime', time_format, 3,'function',NOW_TIME),
					);
				$info->create($data);
				if($info->where("id=".I("post.id")." and uid=".session("user_id"))->count()>0)
				{
					$info->auto($rules)->save();
				}
				else
				{
					$this->ajaxReturn(array('status'=>'这不是你的项目'));
				}
			}
			$this->ajaxReturn(array('status'=>'OK'));
    }
    public function del()
    {
    	if(!is_Complogin())
    	{
				$this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
			}
    	if(IS_POST)
			{
				$data = $_POST ;
				$data["updateTime"] = time_format(NOW_TIME);
				$data["isDeleted"] = 1;
    		$info = M("Projectother");
							
				$info->create($data);
				if($info->where("id=".I("post.id")." and uid=".session("user_id"))->count()>0)
				{
					$info->auto($rules)->save();
				}
				else
				{
					$this->ajaxReturn(array('status'=>'这不是你的项目'));
				}
			}
			$this->ajaxReturn(array('status'=>'OK'));
    	}
    public function add()
    {
    	if(!is_Complogin())
    	{
				$this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
			}
	  	if(IS_POST)
			{
				$data = $_POST ;
				$data["updateTime"] = time_format(NOW_TIME);
    		$info = M("Projectother");
				$data["uid"]=session("user_id");
				$data["isDeleted"] = 0;
				$info->create($data);
				$info->auto($rules)->add();
				
			}
			$this->ajaxReturn(array('status'=>'OK'));
  	}
  	public function Submit()
  	{
  		if(!is_Complogin())
    	{
				$erromsg="未登录，或账户不正确";
			}
  		if(IS_POST)
			{
				$erromsg="";
				//check info
				if(M('info')->where('id='.session("user_id").'')->count()<1)
				{
					$erromsg='未填写企业基本信息';
				}
				//check zhishichanquan
				if(M('zhishichanquan')->where('id='.session("user_id").'')->count()<1)
				{
					$erromsg='未填写知识产权资料';
				}
				//check project
				if(M('project')->where('id='.session("user_id").'')->count()<1)
				{
					$erromsg='未填写项目相关资料';
				}
				//check type 14 file
				$filenum = M('file')->where('companyid='.session("user_id").' and type =14')->count();
				if($filenum<1)
				{
					$erromsg='未上传商业计划书';
				}
				//submit
				if($erromsg=="")
				{
					$company = M('company');
					$company->sbTime = time_format(NOW_TIME);
		    	$company->status = 6;//尽职调查
		    	$company->where('id='.session("user_id"))->save();
		    	
		    	$history = M('history');
		    	$history->create();
		    	$history->uId = session("user_id");
		    	$history->createTime = time_format(NOW_TIME);
		    	$history->operUser = session("user_id");
		    	$history->operIP = get_client_ip(0);
		    	$history->type="提交审核项目资料";
		    	$history->message= "[".session("user_name")."]将项目资料提交审核";
					$history->add();
		    	$erromsg="OK";
				}
				
			}
			
			$this->ajaxReturn(array('status'=>$erromsg));
  	}
  	public function sync()
  	{
  		$username = I('post.username');
  		$validationcode = I('post.validationcode');
  		$data = $_POST['data'];
  		$json_data= json_decode($data);
  		
  		$map['username'] = $username;
			$us = M('user');
			$u = $us->where($map)->select();
			if($u)
			{
				//dump($u[0]);
				//$vc_s=md5($username.$u[0]['password']);
				//if(	$vc_s!= $validationcode)
				//{
				//	$this->ajaxReturn(array('status'=>-2,'msg'=>$vc_s));
				//	return;
				//}
				var_dump($json_data);


				foreach($json_data as $row)
				{
						$map2['userid'] = $row->userid;
						$map2['type'] = $row->type;
						$map2['companyid'] = $row->companyid;
						$map2['isDelete'] = 0;
						$us = M('review');
						$us->isDelete = 1;
						$us->where($map2)->save();
						
						$review = M('review');
			    	$review->create();
			    	$review->userid = $row->userid;
			    	$review->type = $row->type;
			    	$review->companyid = $row->companyid;
			    	$review->value1 = $row->value1;
			    	$review->value2 = $row->value2;
			    	$review->value3 = $row->value3;
			    	$review->value4 = $row->value4;
			    	$review->value5 = $row->value5;
			    	$review->value6 = $row->value6;
			    	$review->value7 = $row->value7;
			    	$review->value8 = $row->value8;
			    	$review->value9 = $row->value9;
			    	$review->value10 = $row->value10;
			    	$review->memo1 = $row->memo1;
			    	$review->memo2 = $row->memo2;
			    	$review->reviewtime = $row->reviewtime;
			    	$review->sum = $row->sum;
			    	$review->isDelete= 0;
			    	$review->syncTime =time_format(NOW_TIME);
						$review->add();
				}
				$this->ajaxReturn(array('status'=>0));
				return;
			}
  		$this->ajaxReturn(array('status'=>-1));
  	}
}