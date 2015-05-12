<?php
//
namespace Admin\Controller;
use Think\Controller;
class DialogController extends Controller {

	
public function index(){
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
				$map['id']= array('neq',113);				

				$map['status'] = array('eq',7);	
				
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
						$row["pdfUrl"] = M("file")->field("pdfUrl")->where('type=14 and companyId='.$row['id'])->find();
						$row["assignUser"] = M('user')->field('username')->where('id='.$row['assignUid'])->find();
						$row["user"]= M('user')->field('username')->where('id='.$row['id'])->find();
						$row["word"]= M('user')->field('password')->where('id='.$row['id'])->find();
						// review recode 
						$row["reviewsum"] = M('reviewsum')->where('companyId='.$row['id'])->find();

						
						// dump($row);
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

    

    public function Dialog($companyid=0){
    	
    	if(is_Complogin())
    	{
			$this->redirect('Home/Index/Index');
			$this->display();
			return;
		}
		if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
		{
			
			$taiduitem = M('taiduitem')->select();
			$shuipingitem = M('shuipingitem')->select();
			$this->assign('taiduitem',$taiduitem);
			//dump($taiduitem);
			$this->assign('shuipingitem',$shuipingitem);		

			$demotaiduitem = M('demotaiduitem')->select();
			$this->assign('demotaiduitem',$demotaiduitem);
			//dump($demotaiduitem);

			$demoshuipingitem = M('demoshuipingitem')->select();			
			$this->assign('demoshuipingitem',$demoshuipingitem);	

			$reviewweight = M('reviewweight')->select();
			$this->assign('reviewweight',$reviewweight);	
			//dump($reviewweight);


			$company = M('company')->where('id = '.$companyid)->select();
			$this->assign("company",$company);
			//dump($company);

			$project = M('project')->where('id = '.$companyid)->select();
			$this->assign("project",$project);
			//dump($project);


			$review = M('review');
			
		    /*if(session("user_level")==8)
			{
				$map['userid'] = session("user_id");
			}*/
			
			//echo 'companyid'.$companyid;
            //1
			$map['companyid']= $companyid;
			$map['type'] = 1;
			$review1 = M('review')->where($map)->select();
			$cs = array();
			foreach($review1 as $r)
			{
				$r["reviewweight"] = M('reviewweight')->where('userid = '.$r['userid'])->find();
				array_push($cs, $r);
			}
			//var_dump($cs);
			$this->assign('review1',$cs);
			
			//2
			$map['companyid']= $companyid;
			$map['type'] = 2;
			$review2 = M('review')->where($map)->select();
			$cs = array();
			foreach($review2 as $r)
			{
				$r["reviewweight"] = M('reviewweight')->where('userid = '.$r['userid'])->find();
				array_push($cs, $r);
			}
			$this->assign('review2',$cs);
			//3
			$map['companyid']= $companyid;
			$map['type'] = 3;
			$review3 = M('review')->where($map)->select();
			$cs = array();
			foreach($review3 as $r)
			{
				$r["reviewweight"] = M('reviewweight')->where('userid = '.$r['userid'])->find();
				array_push($cs, $r);
			}
			$this->assign('review3',$cs);
			//4
			$map['companyid']= $companyid;
			$map['type'] = 4;
			$review4 = M('review')->where($map)->select();
			$cs = array();
			foreach($review4 as $r)
			{
				$r["reviewweight"] = M('reviewweight')->where('userid = '.$r['userid'])->find();
				array_push($cs, $r);
			}
			$this->assign('review4',$cs);
/*
			//1-1 			
			$map['type'] = 1;
			$map['userid'] = $reviewweight[0][userid];
			//$map['userid'] = 5;

			$review1 = M('review')->where($map)->select();	
			$this->assign('review1',$review1[0]);
			//dump($review1);

			//1-2
			$map['userid'] = $reviewweight[1][userid];
			//$map['userid'] = 5;
			$review12 = M('review')->where($map)->select();	
			$this->assign('review12',$review12[0]);
			//dump($review12);

			//1-3
			$map['userid'] = $reviewweight[2][userid];
			
			$review13 = M('review')->where($map)->select();	
			$this->assign('review13',$review13[0]);
			//dump($review13);

			//1-4
			$map['userid'] = $reviewweight[3][userid];
			$review1 = M('review')->where($map)->select();	
			$this->assign('review14',$review1[0]);
			//dump($review14);

            //2-1
			$map['type'] = 2;
			$review2 = M('review')->where($map)->select();	
			$this->assign('review2',$review2[0]);

			$map['type'] = 3;
			$review3= M('review')->where($map)->select();	
			$this->assign('review3',$review3[0]);

			$map['type'] = 4;
			$review4 = M('review')->where($map)->select();	
			$this->assign('review4',$review4[0]);
			
			//dump($review1);
			//dump($review2);
			//dump($review3);
			//dump($review4);
*/
			$this->display();
			return;
		}
		$this->error('您还没有登录，请先登录！', U('User/Login'));
    }

    
}