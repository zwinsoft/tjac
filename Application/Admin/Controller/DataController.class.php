<?php
//
namespace Admin\Controller;
use Think\Controller;
class DataController extends Controller {
    public function statics(){
    	$this->assign('m4','class="active"');
    	$this->assign('m1','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() || is_GuestLogin() ||is_OperLogin())
			{
				$map['isDeleted']=0;

				if( I('get.st') !="" )
				{
					$map['regTime'] = array('egt',I('get.st')." 00:00:01");	
				}
				if(I('get.et') != "")
				{
					if( I('get.st') !="" )
					{
						$map['regTime'] = array('between',array(I('get.st')." 00:00:01",I('get.et')." 23:59:59"));
					}
					else
					{
						$map['regTime'] = array('elt',I('get.et')." 23:59:59");
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
				if(I('get.where') != "")
				{
					$map['where']= array('eq',I('get.where'));	;
				}

				if(I('get.paidUpCapital') != "")
				{
					if( I('get.paidUpCapitalMax') !="" )
					{
						$map['paidUpCapital'] = array('between',array(I('get.paidUpCapital'),I('get.paidUpCapitalMax')));
					}
					else
					{
						$map['paidUpCapital'] = array('elt',I('get.paidUpCapitalMax'));
					}
				}

				if(I('get.salesIncome') != "")
				{
					if( I('get.salesIncomeMax') !="" )
					{
						$map['salesIncome'] = array('between',array(I('get.salesIncome'),I('get.salesIncomeMax')));
					}
					else
					{
						$map['salesIncome'] = array('elt',I('get.salesIncomeMax'));
					}
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
						$row["pdfUrl"] = M("file")->field("pdfUrl")->where('type=14 and companyId='.$row['id'])->find();
						$row["assignUser"] = M('user')->field('username')->where('id='.$row['assignUid'])->find();
						$row["user"]= M('user')->field('username')->where('id='.$row['id'])->find();
						$row["word"]= M('user')->field('password')->where('id='.$row['id'])->find();
						
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

}