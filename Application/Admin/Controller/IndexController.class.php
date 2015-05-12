<?php
//
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
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

				if(I('get.cmbArea') != "")
				{
					$strWhere = I('get.cmbProvince')."-".I('get.cmbCity')."-".I('get.cmbArea');
					//dump($strWhere);

					$map['where']= array('eq',$strWhere);	
					
					if( I('get.subWhere') =="滨海新区" )
					{
				
						if(I('get.subWhere') != "")
						{
							//dump(I('get.subWhere'));
							$map['subWhere']= array('eq',I('get.subWhere'));	
						}
					}

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
						$row["pdfUrl"] = M("file")->field()->where('type=14 and companyId='.$row['id'])->find();
						//$row["pdfUrl2"] = M("file")->field("pdfUrl")->where('type=16 and companyId='.$row['id'])->find();
						$row["assignUser"] = M('user')->field('username')->where('id='.$row['assignUid'])->find();
						$row["user"]=M('user')->field('username')->where('id='.$row['id'])->find();
						$row["password"]=M('user')->field('password')->where('id='.$row['id'])->find();
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
    function saveassign($id,$uid)
    {
    	if(is_AdminLogin())
			{
	    	$company = M('company');
	    	$company->assignUid	= $uid;
	    	
	    	$company->where('id='.$id)->save();
	    	$history = M('history');
	    	$history->create();
	    	$history->uId = (int)$id;
	    	$history->createTime = time_format(NOW_TIME);
	    	$history->operUser = session("user_id");
	    	$history->operIP = get_client_ip(0);
	    	$history->type="分配用户";
	    	$un=M('user')->where('id='.$uid)->find();
	    	
	    	$history->message= "[".session("user_name")."]将项目分配给[".$un['username']."]";
				$history->add();
				//dump($id);
				$this->ajaxReturn(array('username'=>$un['username']));
			}else
			{
				$this->error('没有权限', U('User/Login'));
				}
    }
    function pass($id)
    {
    	if(is_AdminLogin() ||is_OperLogin())
		{

			$projectNum = M('company')->Max("projectNum");
				$c = M('company')->where('id='.$id)->find();
				$cstatus=$c["status"];
				$company = M('company');
				if($cstatus>=5)
				{
					$company->status = 7;
					if($company->projectNum <=0 ){	$company->projectNum = $projectNum + 1 ;}
					
					$msg="项目资料审核通过";

					

				}else
	    		{
	    			$company->status = 4;
	    			$msg="公司资料审核通过";
		    	}
				$company->where('id='.$id)->save();
				
				$history = M('history');
				$history->create();
				$history->uId = (int)$id;
				$history->createTime = time_format(NOW_TIME);
				$history->operUser = session("user_id");
				$history->operIP = get_client_ip(0);
				$history->type=$msg;
				
				$history->message= "[".session("user_name")."]将[".$company->id."]".$msg."状态由[".companyStatus($cstatus)."]变为[".companyStatus($cstatus+1)."]";
					$history->add();
                
                if(C("SMS_COMPANY_NOTIFY")){
       
                    $smsmsg = '恭喜您，您的'.$msg.'详情请登录申报系统查看【科创天使】';					
                    $mobile = $c["mobile"];				
                    $msgtype = 0;
                    sendSMS($smsmsg,$mobile,$msgtype);
                }

				//dump($id);
				$this->ajaxReturn(array('status'=>$msg));
			}else
			{
				$this->error('没有权限', U('Home/User/Login'));
			}
    }
    function refuse($id,$title,$content)
    {
    	if(is_AdminLogin() ||is_OperLogin())
		{
				
            $c = M('company')->where('id='.$id)->find();
            $cstatus=$c["status"];
            $company = M('company');
            if($cstatus>5)
            {
                $company->status = 5;
                $msg="项目审核不通过";
            }else
            {
            
                $company->status = 1;
                $msg="资质审核不通过";
            }
	    	$company = M('company');
	    	
	    	$company->refuseReason = urldecode($title).":".urldecode($content);
	    	$company->refuseTime = time_format(NOW_TIME);
	    	$company->where('id='.$id)->save();
	    	$history = M('history');
	    	$history->create();
	    	$history->uId = (int)$id;
	    	$history->createTime = time_format(NOW_TIME);
	    	$history->operUser = session("user_id");
	    	$history->operIP = get_client_ip(0);
	    	$history->type=$msg;
	    	
	    	$history->message= "[".session("user_name")."]将[".$company->id."]进行审核不通过:".urldecode($title).":".urldecode($content);
				$history->add();
				//dump($id);

            if(C("SMS_COMPANY_NOTIFY")){
                $smsmsg = '很遗憾，您的'.$msg.'具体原因请登录申报系统查看【科创天使】';					
                $mobile = $c["mobile"];			
                $msgtype = 0;
                sendSMS($smsmsg,$mobile,$msgtype);
            }

				$this->ajaxReturn(array('status'=>$msg));
        }else
        {
            $this->error('没有权限', U('Home/User/Login'));
        }
    }

	function pdfdo($id)
    {
    	if(is_AdminLogin() ||is_OperLogin() )
		{
			$c = M('company')->where('id='.$id)->find();
			$cstatus=$c["status"];
			if($cstatus==7)
			{
				$msg="生成项目pdf文档";
			}else
			{
				$msg="项目未通过最后审核,不能生成项目pdf文档";
			}
			
			$pdfAccount="a";
			$pdfPwd = "a";
			//$projectNum="000004";
			$projectNum = $c["projectNum"];
			if($projectNum < 10 ) 
			{
				$pdfNumber="受理编号：201400".$projectNum;
			}
			else
			{
				 if($projectNum <= 100)
				 {
					$pdfNumber="受理编号：20140".$projectNum;
				 }
				 else
				 {
					$pdfNumber="受理编号：2014".$projectNum;
				 }

			}

			$fileUrl="";
			$f = M("file")->where('type=14 and uploadUser='.$id)->find();
			if(isset($f))
			{
				$fileUrl=$f["url"];
				$pdfUrl="http://pdfdo.com/tjac/topdf1.aspx?action=send&account=".$pdfAccount."&password=".$pdfPwd."&number=".$pdfNumber."&filename=http://app.tjacco.com/Public/".$fileUrl;
				add_log('pdfdo', '$pdfUrl:'.$pdfUrl,null );
				$pdfLocalUrl=str_replace("Uploads","DownloadsPDF",$fileUrl);
				
				$pdfLocalPath = $_SERVER['DOCUMENT_ROOT']."/Public/".str_replace($f["uid"],"",$pdfLocalUrl);
				//if(!is_dir($pdfLocalPath)){
					mkdir($pdfLocalPath, 0700, true);
				//}

				$pdfLocalUrl= str_replace(".".$f["mime"],".pdf",$pdfLocalUrl);

				$rs = request_by_curl($pdfUrl);
				$r = substr($rs,0,strpos($rs,'.pdf'));
				$msg=$r;
				if(isset($r))
				{
					download_pdf($_SERVER['DOCUMENT_ROOT']."/Public/".$pdfLocalUrl,$f["mime"],$r.".pdf");
					$msg="生成商业计划书(PDF格式)成功。".$pdfLocalUrl;
			
				}
				$f["pdfUrl"] = $pdfLocalUrl;
				M("file")->where('type=14 and uploadUser='.$id)->save($f);
				$history = M('history');

				$tmpHistory[uId] = (int)$id;
				$tmpHistory[createTime] = time_format(NOW_TIME);
				$tmpHistory[operUser] = session("user_id");
				$tmpHistory[operIP] = get_client_ip(0);
				$tmpHistory[type] ="生成PDF";
				$tmpHistory[message] = "[".session("user_name")."]生成PDF".$f["pdfUrl"];

				$history->create($tmpHistory);
				$history->add();
			//dump($id);
			}else
			{
				add_log('pdfdo', '文件不存在:type=14 and uploadUser='.$id,null );
				$msg="未上传商业计划书";
			}
			$this->ajaxReturn( $msg);
		}else
		{
			$this->error('没有权限', U('Home/User/Login'));
		}
		
    }
	function pdfdo2($id)
    {
    	if(is_AdminLogin() ||is_OperLogin() )
		{
			$c = M('company')->where('id='.$id)->find();
			$cstatus=$c["status"];
			if($cstatus==7)
			{
				$msg="生成项目pdf2文档";
			}else
			{
				$msg="项目未通过最后审核,不能生成项目pdf文档";
			}
			
			$pdfAccount="a";
			$pdfPwd = "a";
			//$projectNum="000004";
			$projectNum = $c["projectNum"];
			if($projectNum < 10 ) 
			{
				$pdfNumber="受理编号：201400".$projectNum;
			}
			else
			{
				 if($projectNum <= 100)
				 {
					$pdfNumber="受理编号：20140".$projectNum;
				 }
				 else
				 {
					$pdfNumber="受理编号：2014".$projectNum;
				 }

			}

			$fileUrl="";
			$f = M("file")->where('type=14 and uploadUser='.$id)->find();
			if(isset($f))
			{
				$fileUrl=$f["url"];
				$xmluri="&xml="."http://app.tjacco.com:8080"."/index.php?m=Admin%26c=Index%26a=getXML%26companyId=".$id;
				$pdfUrl="http://pdfdo.com/tjac/topdf1.aspx?action=send&account=".$pdfAccount."&password=".$pdfPwd."&number=".$pdfNumber."&filename=http://app.tjacco.com/Public/".$fileUrl.$xmluri;
				add_log('pdfdo', '$pdfUrl:'.$pdfUrl,null );
				$pdfLocalUrl=str_replace("Uploads","DownloadsPDF",$fileUrl);
				
				$pdfLocalPath = $_SERVER['DOCUMENT_ROOT']."/Public/".str_replace($f["uid"],"",$pdfLocalUrl);
				//if(!is_dir($pdfLocalPath)){
					mkdir($pdfLocalPath, 0700, true);
				//}

				$pdfLocalUrl= str_replace(".".$f["mime"],"_xml.pdf",$pdfLocalUrl);

				$rs = request_by_curl($pdfUrl);
				$r = substr($rs,0,strpos($rs,'.pdf'));
				$msg=$r;
				if(isset($r))
				{
					download_pdf($_SERVER['DOCUMENT_ROOT']."/Public/".$pdfLocalUrl,$f["mime"],$r.".pdf");
					$msg="生成商业计划书(PDF格式)成功。".$pdfLocalUrl;
			
				}
				
				
				$f["pdfUrlxml"] = $pdfLocalUrl;
				M("file")->where('type=14 and uploadUser='.$id)->save($f);

				$history = M('history');

				$tmpHistory[uId] = (int)$id;
				$tmpHistory[createTime] = time_format(NOW_TIME);
				$tmpHistory[operUser] = session("user_id");
				$tmpHistory[operIP] = get_client_ip(0);
				$tmpHistory[type] ="生成PDF";
				$tmpHistory[message] = "[".session("user_name")."]生成PDF_xml".$f["pdfUrl"];

				$history->create($tmpHistory);
				$history->add();
			//dump($id);
			}else
			{
				add_log('pdfdo', '文件不存在:type=14 and uploadUser='.$id,null );
				$msg="未上传商业计划书";
			}
			$this->ajaxReturn( $msg);
		}else
		{
			$this->error('没有权限', U('Home/User/Login'));
		}
		
    }

    function project()
    {
    	//Company/info1 tab1
    	$user_id=I("get.uid");
    	$i =M("info");
			$vo = $i->where("id=".$user_id)->find();
			$c = M("company")->where("id=".$user_id)->find();
			if(!isset($vo))
			{
				$vo["qiyemingcheng"] = $c["company"];
				$vo["yingyezhizhaohaoma"] = $c["bLicence"];
				$vo["zuzhijigoudaima"] = $c["orgID"];
				$vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
				$vo["zuceziben"] = $c["regCapital"];
				$vo["shishouziben"] = $c["paidUpCapital"];
			}
			$this->assign("info",$vo);
			$infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".$user_id)->select();		
			//var_dump($infoGudong);
			$this->assign("infoGudong",$infoGudong);
			
			//Company/Info2 tab2
			$i2 =M("Zhishichanquan");
			$vo2 = $i2->where("id=".$user_id)->find();
			$zl = M("zhuanli")->where("isDeleted=0 and uid=".$user_id)->select();
			$zzq = M("zhuzuoquan")->where("isDeleted=0 and uid=".$user_id)->select();
			//dump($vo);
			$vos = array();
			if(!isset($vo))
			{
				$vos["zhuanli"] = $zl;
				$vos["zhuzuoquan"] = $zzq;
				$vos["zscq"] =$vo2;
			}else
			{
				$vos["zhuanli"] = $zl;
				$vos["zhuzuoquan"] = $zzq;	
				$vos["zscq"] =$vo2;
			}
			//dump($vo);
			$this->assign("vo",$vos);
  		
  		
  		//Project/Info1 tab3
  		$i3 =M("Project");
			$vo3 = $i3->where("id=".$user_id)->find();
			$others = M("Projectother")->where("isDeleted=0 and uid=".$user_id)->select();
			$this->assign("project",$vo3);
			$this->assign("others",$others);

			//dump($vo3);
			//dump($others);
  		
  		
  		//Project/Info4 tab4
  		
			$this->display();	
    }
    function Company()
    {
    	$mapComp['id']= array('eq',I("get.id"));
    	$company = M('Company');
    	$companys = $company->field(true)->where($mapComp)->select();
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
        $this->assign('vo',$cs[0]);
        //日志
        $dailog = M('Dailog');
        $mapdialogs['companyId'] = array('eq',I("get.id"));
        $mapdialogs['isDeleted'] = array('neq',1);
        $dailogs = $dailog->field(true)->where($mapdialogs)->select();
        $ds = array();
        foreach($dailogs as $d)
        {
            $d["user"]=M('user')->field('username')->where('id='.$d['addUser'])->find();
            array_push($ds, $d);
        }
        $this->assign('dailogs',$ds);
        //打分
        $mapScore['companyId'] = array('eq',I("get.id"));
        //$mapScore['isValidate'] = array('neq',1);
        $score = M("reviewsum")->where($mapScore)->find();
        
        $map['companyid']= I("get.id");
        $map['type'] = 1;
        $review1 = M('review')->where($map)->select();
        
        $all1=0;
        foreach($review1 as $r)
        {
            
            $r["reviewweight"] = M('reviewweight')->where('userid = '.$r["userid"])->find();
            $all1 = $all1 + $r["sum"]*$r["reviewweight"]["weight"];
        }
        $all1 = $all1 / 100;
        //var_dump(M("review"));exit;
        $clsp = isset($score["sum1"])?$score["sum1"]:$all1;
        $this->assign('clsp',$clsp);
        
        $map['type'] = 2;
        $review2 = M('review')->where($map)->select();
        $all2=0;
        foreach($review2 as $r)
        {
            $r["reviewweight"] = M('reviewweight')->where('userid = '.$r["userid"])->find();
            $all2 = $all2 + $r["sum"]*$r["reviewweight"]["weight"];
        }
        $all2 = $all2 / 100;
        $cltd = isset($score["sum2"])?$score["sum2"]:$all2;
        $this->assign('cltd',$cltd);

        $map['type'] = 3;
        $review3 = M('review')->where($map)->select();
        $all3=0;
        foreach($review3 as $r)
        {
            $r["reviewweight"] = M('reviewweight')->where('userid = '.$r["userid"])->find();
            $all3 = $all3 + $r["sum"]*$r["reviewweight"]["weight"];
        }
        $all3 = $all3 / 100;
        $dbsp = isset($score["sum3"])?$score["sum3"]:$all3;
        $this->assign('dbsp',$dbsp);

        $map['type'] = 4;
        $review4 = M('review')->where($map)->select();
        $all4=0;
        foreach($review4 as $r)
        {
            $r["reviewweight"] = M('reviewweight')->where('userid = '.$r["userid"])->find();
            $all4 = $all4 + $r["sum"]*$r["reviewweight"]["weight"];
        }
        $all4 = $all4 / 100;
        $dbtd = isset($score["sum4"])?$score["sum4"]:$all4;
        $this->assign('dbtd',$dbtd);

        $map['type'] = 5;
        $review5 = M('review')->where($map)->select();
        $all5=0;
        foreach($review5 as $r)
        {
            $r["reviewweight"] = M('reviewweight')->where('userid = '.$r["userid"])->find();
            $all5 = $all5 + $r["sum"]*$r["reviewweight"]["weight"];
        }
        $all5 = $all5 / 100;
        //var_dump($score);exit;
        $kcsp = isset($score["sum5"])?$score["sum5"]:$all5;
        $this->assign('kcsp',$kcsp);

        $map['type'] = 6;
        $review6 = M('review')->where($map)->select();
        $all6=0;
        foreach($review6 as $r)
        {
            $r["reviewweight"] = M('reviewweight')->where('userid = '.$r["userid"])->find();
            $all6 = $all6 + $r["sum"]*$r["reviewweight"]["weight"];
        }
        $all6 = $all6 / 100;
        $kctd = isset($score["sum6"])?$score["sum6"]:$all6;
        $this->assign('kctd',$kctd);

        $this->assign('sp',($clsp + $dbsp * 3 + $kcsp*6)/10);
        $this->assign('td',($cltd + $dbtd * 3 + $kctd*6)/10);

        //
        $mapCapital['companyId'] = I("get.id");
        //$mapScore['isValidate'] = array('neq',1);
        $capital = M("capital")->where($mapCapital)->find();

        $this->assign('before',isset($capital["before"])?$capital["before"]:0);
        $this->assign('invest',isset($capital["invest"])?$capital["invest"]:0);
        $this->assign('after',isset($capital["after"])?$capital["after"]:0);
    	$this->display();

    }
    function savepingshen($id,$clsp,$cltd,$dbsp,$dbtd,$kcsp,$kctd)
    {
    	if(is_AdminLogin() || is_GuestLogin() ||is_OperLogin())
	{
	    	$mapScore['companyId'] = array('eq',I("get.id"));
		//$mapScore['isValidate'] = array('neq',1);
		$score = M("reviewsum")->where($mapScore)->find();
		
		$scoretemp["companyId"] = $id;
		$scoretemp["sum1"] = $clsp;
		$scoretemp["sum2"] = $cltd;
		$scoretemp["sum3"] = $dbsp;
		$scoretemp["sum4"] = $dbtd;
		$scoretemp["sum5"] = $kcsp;
		$scoretemp["sum6"] = $kctd;
		$scoretemp["isValidate"] = 1;
		if(!isset($score))
		{
			M("reviewsum")->create($scoretemp);
			M("reviewsum")->add();
		}else
		{
			$scoretemp["id"] = $score["id"];
			M("reviewsum")->save($scoretemp);
		}
		$this->ajaxReturn( "保存成功");
	}else
	{
		$this->error('没有权限', U('Home/User/Login'));
	}
    }
    function saveCapital($id,$before,$invest,$after)
    {
	if(is_AdminLogin() || is_GuestLogin() ||is_OperLogin())
	{
		$mapCapital['companyId'] = $id;
		//$mapScore['isValidate'] = array('neq',1);
		$capital = M("capital")->where($mapCapital)->find();
		
		$capitaltemp["companyId"] = $id;
		$capitaltemp["before"] = $before;
		$capitaltemp["invest"] = $invest;
		$capitaltemp["after"] = $after;
		$capitaltemp["saveUser"] = session("user_id");;
		$capitaltemp["saveTime"] = time_format(NOW_TIME);
		if(!isset($capital))
		{
			M("capital")->create($capitaltemp);
			M("capital")->add();
		}else
		{
			$capitaltemp["id"] = $capital["id"];
			M("capital")->save($capitaltemp);
		}
		$this->ajaxReturn( "更新成功");
	}else
	{
		$this->error('没有权限', U('Home/User/Login'));
	}
    }
    function  getXml($companyId)
	{
		header("Content-Type:text/xml; charset=utf-8");
        //exit(xml_encode($result));
		//Company/info1 tab1
    	$user_id=$companyId;
    	$i =M("info");
			$vo = $i->where("id=".$user_id)->find();
			$c = M("company")->where("id=".$user_id)->find();
			if(!isset($vo))
			{
				$vo["qiyemingcheng"] = $c["company"];
				$vo["yingyezhizhaohaoma"] = $c["bLicence"];
				$vo["zuzhijigoudaima"] = $c["orgID"];
				$vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
				$vo["zuceziben"] = $c["regCapital"];
				$vo["shishouziben"] = $c["paidUpCapital"];
			}
			$this->assign("info",$vo);
			$infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".$user_id)->select();		
			//var_dump($infoGudong);
			$this->assign("infoGudong",$infoGudong);
			
			//Company/Info2 tab2
			$i2 =M("Zhishichanquan");
			$vo2 = $i2->where("id=".$user_id)->find();
			$zl = M("zhuanli")->where("isDeleted=0 and uid=".$user_id)->select();
			$zzq = M("zhuzuoquan")->where("isDeleted=0 and uid=".$user_id)->select();
			//dump($vo);
			$vos = array();
			if(!isset($vo))
			{
				$vos["zhuanli"] = $zl;
				$vos["zhuzuoquan"] = $zzq;
				$vos["zscq"] =$vo2;
			}else
			{
				$vos["zhuanli"] = $zl;
				$vos["zhuzuoquan"] = $zzq;	
				$vos["zscq"] =$vo2;
			}
			//dump($vo);
			$this->assign("vo",$vos);
  		
  		
  		//Project/Info1 tab3
  		$i3 =M("Project");
			$vo3 = $i3->where("id=".$user_id)->find();
			$others = M("Projectother")->where("isDeleted=0 and uid=".$user_id)->select();
			$this->assign("project",$vo3);
			$this->assign("others",$others);

			
			//dump($vo3);
			//dump($others);  		
  		
		
		$this->display('getXML','utf-8','text/xml');
		exit();
	}
}