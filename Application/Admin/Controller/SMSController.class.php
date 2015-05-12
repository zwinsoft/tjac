<?php
//
namespace Admin\Controller;
use Think\Controller;
class SMSController extends Controller {
    
    public function index(){
    	$this->assign('m12','class="active"');
    	if(is_Complogin())
    	{
			$this->redirect('Home/Index/Index');
			$this->display();
			return;
		}
		if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
		{
			$map['isDeleted']=0;
			$Sms = M('sms');
			$pagenumber=8;//每页条数
			
			$all=$Sms->where($map)->count();
			$maxpage = ceil($all/$pagenumber)+1;
			
			$page=$_GET['page'];
			
			if(!isset($page))
			{$page=1;}
			$Smss = $Sms->page($page,$pagenumber)->field(true)->where($map)->order('id desc')->select();
			$cs = array();
			foreach($Smss as $row)
			{
					$row["User"] = M('Company')->where('mobile = '.$row['mobile'])->select();
					array_push($cs, $row);
			}
			//print_r($cs);
			$this->assign('data',$cs);
			$this->assign('page',$page);
			$this->assign('maxpage',$maxpage);
			$this->assign('pagenumber',$pagenumber);
			$this->assign('all',$all);
			$param="&userid=".C("SMS_UID")."&account=".C("SMS_ACCOUNT")."&password=".C("SMS_PWD");
			$url = C("SMS_URL").'/sms.aspx?action=overage'.$param;
			//echo $url;
			$xml = request_by_curl($url);
			if($xml) {
				$parser = simplexml_load_string($xml);
				//var_dump($parser);
				$this->assign('sentSmsCount',$parser->overage);
				$this->assign('totalSmsCount',$parser->sendTotal);
			}
			
			$this->display();
			return;
		}
		$this->error('您还没有登录，请先登录！', U('User/Login'));
    }

	public function send()
	{
		$this->assign('m13','class="active"');
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
				//$param="&userid=".C("SMS_UID")."&account=".C("SMS_ACCOUNT")."&password=".C("SMS_PWD");
				//$url = C("SMS_URL").'/sms.aspx?action=overage'.$param;
				//echo $url;
				//$xml = '< ?xml version="1.0" encoding="utf-8" ? ><returnsms>
// <returnstatus>Success</returnstatus>
 //<message>ok</message>
 //<remainpoint>6193</remainpoint>
 //<taskID>2805467</taskID>
 //<successCounts>1</successCounts></returnsms>';//request_by_curl($url);
 //echo $xml;
 //echo "<br />";
				//if($xml) {
				//	$parser = simplexml_load_string($xml);
				//	var_dump($parser);
				//	echo $parser->returnstatus;
				//	echo $parser->message;
				//	echo $parser->successCounts;
				//	echo $parser->taskID;
				//}
				
				if(I('post.msg')=="")
				{
					$this->error("短信内容不能为空");
					return;
				}
				if(I('post.mobile')=="" && I('post.right')=="")
				{
					$this->error("短信接收手机号不能为空");
					return;
				}
				$num=0;
				$waitSend1 = explode(",",I('post.mobile'));
				$waitSend2 = I('post.right');
				if(I('post.mobile')!="")
				{
					$num += count($waitSend1) ;
				}
				if(I('post.right')!="")
				{
					$num += count($waitSend2);
				}
				$sendnum=0;
				$failednum=0;
				foreach($waitSend1 as $s1)
				{
					$r = sendSMS(I('post.msg'),$s1,I('post.type'));
					if($r==2)
					{
						$sendnum += 1;
					}else
					{
						$failednum +=1;
					}
				}
				foreach($waitSend2 as $s2)
				{
					$r = sendSMS(I('post.msg'),$s2,I('post.type'));
					if($r==2)
					{
						$sendnum += 1;
					}else
					{
						$failednum +=1;
					}
				}
				//var_dump(I('post.right'));
				//$r = sendSMS(I('post.msg'),I('post.mobile'),I('post.type'));
				if($failednum==0)
				{
					$this->success("短信发送成功");
				}else
				{
					$this->error("短信发送失败或部分短信发送失败: 发送失败数：".$failednum.". 发送成功数：".$sendnum);
				}
				
			}
			$contact = M("company")->select();
			$this->assign('contact',$contact);
			$this->display();
			return;
		}
		$this->error('您还没有登录，请先登录！', U('User/Login'));
	
	}
    
}