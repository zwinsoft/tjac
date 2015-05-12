<?php
//
namespace Admin\Controller;
use Think\Controller;
class ProjectController extends Controller {
	 
    public function index(){
    	$this->assign('m2','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() ||is_OperLogin())
			{
				$map['isDeleted']=0;
				$map['id']= array('neq',113);
				if( I('get.st') !="" )
				{
					$map['sbTime'] = array('egt',I('get.st')." 00:00:01");	
				}
				if(I('get.et') != "")
				{
					if( I('get.st') !="" )
					{
						$map['sbTime'] = array('between',array(I('get.st')." 00:00:01",I('get.et')." 23:59:59"));
					}
					else
					{
						$map['sbTime'] = array('elt',I('get.et')." 23:59:59");
					}
				}
				if(I('get.status') != "")
				{
					$map['status'] = array('eq',I('get.status'));	
				}
				if(I('get.comp') != "")
				{
					$map['company']= array('like', '%'.I('get.comp').'%');
				}
				$company = M('Company');
				$pagenumber=8;//每页条数
				
				$all=$company->where($map)->count();
				$maxpage = ceil($all/$pagenumber)+1;
				
				$page=$_GET['page'];
				
				if(!isset($page))
				{$page=1;}
				$companys = $company->page($page,$pagenumber)->field(true)->where($map)->order('id desc')->select();
				$cs = array();
				foreach($companys as $row)
				{
					  
						$row["project"] = M('project')->where('id = '.$row['id'])->find();
						$row["history"] = M('history')->where('uid='.$row['id'])->order('id desc')->select();
						$row["projectother"] = M('projectother')->where('id = '.$row['id'])->select();
						$row["file"] = M('file')->where('companyId='.$row['id'])->select();
						$row["assignUser"] = M('user')->field('username')->where('id='.$row['assignUid'])->find();
						$row["username"]=M('user')->field('username')->where('id='.$row['id'])->find();
						$row["password"]=M('user')->field('password')->where('id='.$row['id'])->find();
						//dump($row);
						array_push($cs, $row);
				}
				//print_r($cs);
				$this->assign('data',$cs);
				$this->assign('admin',M('user')->where('isdeleted =0 and level in (8,9)')->order('username')->select());
				$this->assign('page',$page);
				$this->assign('maxpage',$maxpage);
				$this->assign('pagenumber',$pagenumber);
				$this->assign('all',$all);
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('Home/User/Login'));
    }
    public function assignuser($id)
    {
    	$company = M('company');
    	$company->assignUid	= session("user_id");

    	$company->where('id='.$id)->save();
    	$history = M('history');
    	$history->create();
    	$history->uId = (int)$id;
    	$history->createTime = time_format(NOW_TIME);
    	$history->operUser = session("user_id");
    	$history->operIP = get_client_ip(0);
    	$history->type="分配用户";
    	$history->message= "[".session("user_name")."]将项目领取到自己的任务";
			$history->add();
			//dump($id);
			$this->ajaxReturn(array('username'=>session("user_name")));
    }
    public function search(){
    	$this->assign('m3','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() ||is_OperLogin())
			{
				$map['isDeleted']=0;
				if( I('st') !="" )
				{
					$map['sbTime'] = array('egt',I('get.st'));	
				}
				if(I('get.et') != "")
				{
					$map['sbTime'] = array('elt',I('get.st'));
				}
				if(I('get.status') != "")
				{
					$map['status'] = array('eq',I('get.status'));	
				}
				if(I('get.comp') != "")
				{
					$map['company']= array('like', '%'.I('get.comp').'%');
				}
				$company = M('Company');
				$pagenumber=2;
				
				$maxpage = ceil($company->where($map)->count()/$pagenumber)+1;
				
				$page=$_GET['page'];
				
				if(!isset($page))
				{$page=1;}
				$companys = $company->page($page,$pagenumber)->field(true)->where($map)->order('id desc')->select();
				$cs = array();
				foreach($companys as $row)
				{
					  
						$row["projects"] = M('project')->where('id = '.$row['id'])->select();
						$row["history"] = M('history')->where('uid='.$row['id'])->order('id desc')->select();
						$row["projectother"] = M('projectother')->where('id = '.$row['id'])->select();
						$row["file"] = M('file')->where('companyId='.$row['id'])->select();
						$row["assignUser"] = M('user')->field('username')->where('id='.$row['assignUid'])->find();
						//dump($row);
						array_push($cs, $row);
				}
				//print_r($cs);
				$this->assign('data',$cs);
				$this->assign('page',$_GET['page']);
				$this->assign('maxpage',$maxpage);
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('Home/User/Login'));
    }
}