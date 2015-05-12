<?php
//
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$this->assign('m1','class="active"');
    	if(is_Complogin())
    	{
    		$map['isDeleted'] = array('eq',0);	
				$notics=M('Notic');
				$n = M('Notic');
				$pagenumber=8;
				
				$maxpage = ceil($n->where($map)->count()/$pagenumber)+1;
				
				$page=$_GET['page'];
				
				if(!isset($page))
				{$page=1;}
				$company= M('Company')->where('id='.session("user_id"))->find();
				
				$data = $notics->page($page,$pagenumber)->field(true)->where($map)->order('important desc,creatTime desc')->select();
				$nav1 = "stepno";
				$nav2 = "stepno";
				$nav3 = "stepno";
				$nav4 = "stepno";
				$nav5 = "stepno";
				$nav6 = "stepno";
				$nav7 = "stepno";
				$nav8 = "stepno";
				$nav9 = "stepno";
				$nav10 = "stepno";
				//dump();
				
				if($company['status'] >= -1)
				{
					$nav1 = "stepok";
					$nav2 = "stepon";
					$nav3 =	"stepon";
					$nav4 = "stepon";
				}

				
				
				if($company['status'] == 1) #资质审核未通过
				{
					$nav2 = "stepon";
					$nav3 =	"stepon";
					$nav4 = "stepon";
					$nav5 = "stepsuccess";
				}
				
				if($company['status'] > 1) #提交资质待审核
				{
					$nav2 = "stepok";
					$nav3 =	"stepok";
					$nav4 = "stepok";
					$nav5 = "stepon";
				}
				if($company['status'] ==4) #资质审核通过
				{
					$nav5 = "stepok";
					$nav6 = "stepon";
					$nav7 = "stepon";
					$nav8 = "stepon";
					$nav9 = "stepon";
					$nav10 = "stepno";
				}
				if($company['status'] == 5) #项目审核未通过
				{
					$nav5 = "stepok";
					$nav6 = "stepon";
					$nav7 = "stepon";
					$nav8 = "stepon";
					$nav9 = "stepon";
					$nav10 = "stepsuccess";
				}
				
				if($company['status'] == 6) #尽职调查
				{
					$nav5 = "stepok";
					$nav6 = "stepok";
					$nav7 = "stepok";
					$nav8 = "stepok";
					$nav9 = "stepok";
					$nav10 = "stepon";
				}




				if($company['status'] >6) #项目审核通过
				{
					$nav5 = "stepok";
					$nav6 = "stepok";
					$nav7 = "stepok";
					$nav8 = "stepok";
					$nav9 = "stepok";
					$nav10 = "stepok";
				}
				if($company['status'] >=9)
				{
					$nav10 = "stepok";
				}
				$this->assign('page',$_GET['page']);
				$this->assign('maxpage',$maxpage);
				$this->assign('data',$data);
				$this->assign('nav1',$nav1);
				$this->assign('nav2',$nav2);
				$this->assign('nav3',$nav3);
				$this->assign('nav4',$nav4);
				$this->assign('nav5',$nav5);
				$this->assign('nav6',$nav6);
				$this->assign('nav7',$nav7);
				$this->assign('nav8',$nav8);
				$this->assign('nav9',$nav9);
				$this->assign('nav10',$nav10);
				$this->assign('errormsg','['.$company['refuseTime'].'] : '.$company['refuseReason']);
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
				$this->redirect('Admin/Guest/Index');
				return;
			}
			if(is_OperLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
    }
}