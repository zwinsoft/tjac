<?php
function is_CompLogin()
{
	$userlvl = session('user_level');
	if(isset($userlvl))
	{
		if($userlvl==0)
		return true;
	}
	return false;
}

function is_AdminLogin()
{
	$userlvl = session('user_level');
	if(isset($userlvl))
	{
		if($userlvl==9)
		return true;
	}
	return false;

}
function is_GuestLogin()
{
	$userlvl = session('user_level');
	if(isset($userlvl))
	{
		if($userlvl==1)
		return true;
	}
	return false;

}
function is_OperLogin()
{
	$userlvl = session('user_level');
	if(isset($userlvl))
	{
		if($userlvl==8)
		return true;
	}
	return false;

}
function check_verify($code, $id = 2){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}
function add_log($method = null, $message = null, $uid = null){
	$log = M('Log');
	$data['function'] = $method;
	$data['uid'] = $uid;
	$data['message'] = $message;
	$data['creatTime'] = time_format(NOW_TIME);
	$log->add($data);
}

/**
 * 
 * @param int $time
 * @return string 
 */
function time_format($time = NULL,$format='Y-m-d H:i:s'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}
function companyStatus($id)
{
	switch ($id)
	{
	case -1:
		return "审核不通过";
		break;
	case 0:
	  return "新注册";
	  break;
	case 1:
	  return "资质审核未通过";
	  break;
	case 2:
	  return "預留";   //預留
	  break;
	case 3:
	  return "提交资质待审核";//"提交资质等待审批";
	  break;
	case 4:
	  return "资质审核通过";
	  break;
	case 5:
	  return "項目审核未通过";
	  break;	  
	case 6:
	  return "提交项目待审核"; //"提交项目等待审批";
	  break;
  case 7:
	  return "项目审核通过";
	  break;
  case 8:
	  return "专家论证";
	  break;
  case 9:
	  return "科委审批";
	  break;
  case 10:
	  return "投资前资助申报";
	  break;
  case 11:
	  return "已投资";
	  break;
  case 12:
	  return "验收资料待审核";
	  break;
  case 13:
	  return "投资后资助申报";
	  break;
	case 14:
	  return "结项报告待审核";
	  break;
  case 15:
	  return "项目结项";
	  break;
	default:
	  return "状态错误";
	}
/*
<option value="">－请选择申报状态－</option>
							<option value="0">新注册</option>
								<option value="1">提交资质待审核</option>
								<option value="3">资质审核不通过</option>
								<option value="4">资质审核通过</option>
								<option value="5">尽职调查</option>
								<option value="6">项目审核不通过</option>
								<option value="7">项目审核通过</option>

	<option value="8">科委审批</option>
	<option value="9">投资前资助申报</option>
	<option value="10">已投资</option>
	<option value="11">验收资料待审核</option>
	<option value="12">投资后资助申报</option>
	<option value="13">结项报告待审核</option>
	<option value="14">项目结项</option>
*/
}

function companyStatusCss($id)
{
	switch ($id)
	{
	case -1:
	  return "label-important";//"审核不通过";
	  break;
	case 0:
	  return "label-important";//"新注册";
	  break;
	case 1:
	  return "label-important";//"资质审核未通过";
	  break;
	case 2:
	  return "label-important";//"审批中";
	  break;
	case 3:
	  return "label-important";//"等待审批";//提交资质待审核
	  break;
	case 4:
	  return "label-success";//"资质审核通过";
	  break;
	case 5:
	  return "label-warning";//"项目审核未通过";
	  break;
	case 6:
	  return "label-warning";//"提交项目待审核";
	  break;
  case 7:
	  return "label-warning";//"项目审核通过";
	  break;
  case 8:
	  return "label-warning";//"专家论证";
	  break;
  case 9:
	  return "label-warning";//"科委审批";
	  break;
  case 10:
	  return "label-warning";//"投资前资助申报";
	  break;
  case 11:
	  return "label-success";//"已投资";
	  break;
  case 12:
	  return "label-info";//"验收资料待审核";
	  break;
  case 13:
	  return "label-info";//"投资后资助申报";
	  break;
	case 14:
	  return "label-inverse";//"结项报告待审核";
	  break;
  case 15:
	  return "label-inverse";//"项目结项";
	  break;
	default:
	  return "";//"状态错误";
	}

}

function fileName($id)
{
	switch ($id)
	{
	
	case 1:
	  return "融资承诺书";
	  break;
	case 2:
	  return "营业执照";
	  break;
	case 3:
	  return "组织结构代码证";
	  break;
	case 4:
	  return "国税登记证";
	  break;
  case 5:
	  return "地税登记证";
	  break;
  case 6:
    return "天津市科技型中小企业认定证书";
    break;
  case 7:
	  return "企业章程";
	  break;
  case 8:
	  return "公司成立验资报告";
	  break;
  
  case 9:
	  return "前一年的审计报告";
	  break;
  case 10:
	  return "最近一个月的资产负债表";
	  break;
  case 11:
	  return "损益表";
	  break;
	case 12:
	  return "其他";
	  break;
  case 13:
	  return "其他";
	  break;
	case 14:
	  return "商业项目计划书";
	  break;
	case 15:
	  return "项目其他附件";
	  break;
	default:
	  return "状态错误";
	}

}
function isDisabled($m)
{
	$data = M('Company')->where('id='.session('user_id'))->find();
	if($data)
	{
		if($data['status'] <=3)
		{
			return "class='disabled' title='请先完成之前步骤'";
		}
	}
	return $m;
}

function getBaseStatus()
{
	$data = M('Company')->where('id='.session('user_id'))->find();
	if($data)
	{
		if($data['status'] == 0)
		{
			return "资质审核";
		}
		else if($data['status'] ==1)
		{
			return 	"资质审核未通过";

		}
		else if($data['status'] == 3)
		{
			return "公司资质审核中";
		}
		else if($data['status'] > 3)
		{
			return 	"资质审核通过";
		}
	}
	return $m;
}

function getStatus()
{
	$data = M('Company')->where('id='.session('user_id'))->find();
	if($data)
	{
		if($data['status'] <= 4)
		{
			return "项目提交审核";
		}else
		{
			if($data['status']==5)
			{
				return "项目审核未通过";
			}
			if($data['status']==6)
			{
				return "提交项目待审核";
			}else
			{
				return 	companyStatus($data['status']);
			}
		}
	}
	return $m;
}

function get_user_id()
{
	return session("user_id")."/".time_format(NOW_TIME,"Y");
}
function request_by_curl($remote_server )
{
	$ch = curl_init();
	$str = $remote_server;//'http://127.0.0.1/form.php?id=10';
	curl_setopt($ch, CURLOPT_URL, $str);
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	$output = curl_exec($ch);
	//var_dump( $output );
	return $output;
}
function request_by_curl_fireFox($remote_server )
{
	$ch = curl_init();
	$str = $remote_server;//'http://127.0.0.1/form.php?id=10';
	curl_setopt($ch, CURLOPT_URL, $str);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0 );
	curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	//curl_setopt($ch, CURLOPT_MAXREDIRS,10);
	//curl_setopt($ch, CURLOPT_HEADER,1);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$output = curl_exec($ch);
	//var_dump( $output );
	return $output;
}
function download_pdf($pdfLocalPath,$mime,$url)
{
	$return_content = request_by_curl($url);
    $fp= @fopen($pdfLocalPath,"w"); //将文件绑定到流  
    @fwrite($fp,$return_content); //写入文件 
	@fclose($fp);
}
function Counti($str)
{
	$ch_amont = 0;
	$en_amont = 0;
	$str = preg_replace("/(　| ){1,}/", " ", $str);
	for($i=0;$i<strlen($str);$i++)
	{
		$ord = ord($str{$i});    
		if($ord > 128)
			$ch_amont++;
		else
			$en_amont++;
	}
	return ($ch_amont/3) + $en_amont;
}

function sendSMS($msg,$mobile,$type,$isCheck='0',$uid=0,$smsid=0)
{
	$rtn=0;
	$sms=M("sms");
	$data["type"]=$type;
	$data["createTime"]= time_format(NOW_TIME);
	$data["msg"]=$msg;
	$data["mobile"]=$mobile;
	$data["status"]=0;
	$data["isdeleted"]=0;
	$data["uid"]=$uid;
	$data["smsid"]=$smsid;
	$data["num"]=Counti($msg);
	$data["type"]=$type;
	$r = $sms->data($data)->add();
    
    
	$pAccount="&userid=".C("SMS_UID")."&account=".C("SMS_ACCOUNT")."&password=".C("SMS_PWD");
	//urlencode
	$pOther="&mobile=".$mobile."&content=".urlencode($msg)."&sendTime=&checkcontent=".$isCheck;
	$url = C("SMS_URL").'/sms.aspx?action=send'.$pAccount.''.$pOther;
	add_log("sendSms",$url,0);
	$xml = request_by_curl($url);
	add_log("sendSms",$xml,0);
	if($xml) {
		$parser = simplexml_load_string($xml);
		//var_dump($parser);
		//echo $parser->returnstatus;
		//echo $parser->message;
		if($parser->returnstatus=="Success")
		{
			$updt["status"]="2";
			$updt["returnMsg"]="";
			$updt["sendTime"]=time_format(NOW_TIME);
			$updt["count"] = 1*$parser->successCounts;
			$updt["taskID"]= "Tid.".$parser->taskID;
			M("sms")->where("id=".$r)->save($updt);
			$rtn=2;
		}elseif($parser->returnstatus=="Faild")
		{
			$updt["status"]="1";
			$updt["returnMsg"]=$parser->message;
			$updt["sendTime"]=time_format(NOW_TIME);
			$updt["taskID"]=$parser->taskID;
			M("sms")->where("id=".$r)->save($updt);
			$rtn=1;
		}else
		{
			$updt["status"]="0";
			$updt["returnMsg"]=$xml;
			$updt["sendTime"]=time_format(NOW_TIME);
			M("sms")->where("id=".$r)->save($updt);
			$rtn=0;
		}
	}else
	{
		$updt["status"]="1";
		$updt["returnMsg"]="短信接口.调用错误";
		$updt["sendTime"]=time_format(NOW_TIME);
		$updt["taskID"]=$parser->taskID;
		M("sms")->where("id=".$r)->save($updt);
		$rtn=1;
	}
	return $rtn;
}
?>
