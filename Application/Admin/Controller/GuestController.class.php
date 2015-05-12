<?php
//
namespace Admin\Controller;
use Think\Controller;
class GuestController extends Controller {
    public function index(){
    	$this->assign('m1','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if( is_GuestLogin() )
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
				if(session("user_level")==8)
				{
					$map['assignUid'] = session("user_id");
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
						$row["file"] = M('file')->where('companyId='.$row['id'])->order('type')->select();
						$row["assignUser"] = M('user')->field('username')->where('id='.$row['assignUid'])->find();
						$row["user"]=M('user')->field('username')->where('id='.$row['id'])->find();
						//dump($row);
						array_push($cs, $row);
				}
				$this->assign('admin',M('user')->where('isdeleted =0 and level in (8)')->order('username')->select());
				//print_r($cs);
				$this->assign('data',$cs);
				$this->assign('page',$page);
				$this->assign('maxpage',$maxpage);
				$this->assign('pagenumber',$pagenumber);
				$this->assign('all',$all);
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('Home/User/Login'));
    }
 

    function receive($id)
    {
    	if(is_AdminLogin() ||is_OperLogin() ||is_GuestLogin())
			{
				$c = M('company')->where('id='.$id)->find();
				$cstatus=$c["status"];
				$company = M('company');
				if($cstatus>=7)
				{
					$company->statusByGuest = 1;
					
					$msg="受理申报资料";
				}else
	    		{
	    			$company->statusByGuest = 0;
					
	    			$msg="不受理申报资料";
		    	}
	    	$company->where('id='.$id)->save();
	    	
	    	$history = M('history');
	    	$history->create();
	    	$history->uId = (int)$id;
	    	$history->createTime = time_format(NOW_TIME);
	    	$history->operUser = session("user_id");
	    	$history->operIP = get_client_ip(0);
	    	$history->type=$msg;
	    	
	    	$history->message= "[".session("user_name")."]将".$msg;
				$history->add();
				//dump($id);
				$this->ajaxReturn(array('status'=>$msg));
			}else
			{
				$this->error('没有权限', U('Home/User/Login'));
				}
    }

 
}