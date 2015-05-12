<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    //public function login(){
		//	$this->display();
    //}
    /* 验证码，用于登录和注册 */
		public function verify(){
			$verify = new \Think\Verify();
			ob_clean();
			$verify->useCurve = false; 
		  $verify->entry(2);
		}
		public function profile(){
    	$this->assign('m4','class="active"');
    	if(is_Complogin())
    	{
    		if(IS_POST)
    		{
    			if(I('post.newpwd')==I('post.newpwd2'))
    			{
    				$map['id'] = session("user_id");
    				$us = M('user');
    				$u = $us->where($map)->select();
    				if($u)
    				{
    					//dump($u[0]);
    					if($u[0]['password']	== I('post.password'))
    					{
    							$us->password = I('post.newpwd');
    							$us->where($map)->save();
    							$this->success('修改密码成功');
    					}else
    					{
    						$this->error("修改密码失败:老密码错误");	
    					}
    				}else
    				{
    					$this->error("修改密码失败:无此用户".session("user_id"));	
    				}
    					
    			}
    			
    		}
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
		public function login($uname = '', $pwd = '', $code = ''){
			if(IS_POST){ //登录验证
				$ckcode = check_verify($code,2);
				//echo "1111".$ckcode."2222";
				if(!$ckcode)
				{
					$this->error('验证码输入错误！');
				}else
				{
					if(D('User')->login($uname,$pwd))
					{
						$this->success('登录成功！',U('Home/Index/Index'));
					}else
					{
						if(D('User')->login($uname))
						{
							$this->error('登录失败：用户名不存在');
						}
						else
						{
							//$this->error('登录失败');
						}
					}
				}
			}else
			{
				$this->display();
			}
		}
		public function Logout(){
				D('User')->logout();
				$this->redirect('User/Login');
		}
		public function reg1(){
			$this->display();	
		}
		public function Register(){
			if(IS_POST)
			{
				$this->display();
			}
		}
		public function Confirm(){
			if(IS_POST)
			{
				if(D('User')->isExists(I('post.username')))
				{
						$this->error("注册失败,用户名已经存在.");
						return;
				}
				if(M('Company')->where("company='".I('post.company')."'")->count()>0)
				{
						$this->error("注册失败,公司名称已经存在.");
						return;
				}
				if(M('Company')->where("bLicence='".I('post.bLicence')."'")->count()>0)
				{
						$this->error("注册失败,营业执照号码已经存在.");
						return;
				}
				if(M('Company')->where("orgID='".I('post.orgID')."'")->count()>0)
				{
						$this->error("注册失败,组织机构代码证号码已经存在.");
						return;
				}
				$user = D('User');
				$data['username'] = $_POST["username"];//I('post.username');
				$data['password'] = $_POST["password"];
				$data['level'] = 0;
				
				$user->create($data);
				$uid = $user->add();
				if($uid > 0)
				{
					$company = M('Company');
					$company->create();
					$company->company = I('post.company');
					$company->where = I('post.cmbProvince').'-'.I('post.cmbCity').'-'.I('post.cmbArea');
					$company->bLicence = I('post.bLicence');
					$company->orgID = I('post.orgID');
					$company->buildTime = I('post.buildTime');
					$company->regCapital = I('post.regCapital');
					$company->paidUpCapital = I('post.paidUpCapital');
					$company->comDirector = I('post.comDirector');
					$company->mobile =I('post.mobile') ;
					$company->email = I('post.email');
					$company->mainBusiness = I('post.mainBusiness');
					$company->passRegconition = I('post.passRegconition');
					$company->allCapital = I('post.allCapital');
					$company->netAssets = I('post.netAssets');
					$company->salesIncome = I('post.salesIncome');
					$company->netProfit = I('post.netProfit');
					$company->id = $uid;
					$company->status = 0;  #公司状态 新注册
					$company->regTime = time_format(NOW_TIME);
					$company->regIp = get_client_ip(0);
					if($company->add() > 0)
					{
						$history = M('history');
			    	$history->create();
			    	$history->uId = $uid;
			    	$history->createTime = time_format(NOW_TIME);
			    	$history->operUser = $uid;
			    	$history->operIP = get_client_ip(0);
			    	$history->type="注册用户";
			    	$history->message= "[".$data["username"]."]注册";
						$history->add();
						D('User')->login($data['username'],$data['password']);
						$this->success("注册成功",U('Home/Index/Index'));
						return;
					}else
					{
						$history = M('history');
				    	$history->create();
				    	$history->uId = $uid;
				    	$history->createTime = time_format(NOW_TIME);
				    	$history->operUser = $uid;
						$history->operIP = get_client_ip(0);
			    		$history->type="注册用户";
				    	$history->message= "[".$data["username"]."]注册";
						$history->add();
						
						D('User')->login($data['username'],$data['password']);
						$this->success("注册成功，企业相关信息添加失败，请登录之后完善信息",U('Home/Index/Index'));
					}
				}else
				{
					add_log('user_confirm', 'registerFailed:'.$user->getDbError().'=='.($user->getLastSql()).'=='.(M()->getLastSql()),null );
					$this->error("注册失败");
				}
			}
		}

        //*********************************
		//Findpwd()
		//Find username / password
		//20140625  taochen
		//*********************************
		public function Findpwd(){
			if(IS_POST)
			{
				echo(I('post.company'));

				if(M('Company')->where("company='".I('post.company')."'")->count()>0)
				{
					$company1 = M('Company')->where("company='".I('post.company')."'")->find();	
					$company = $company1;					
						
				}
				if(M('Company')->where("bLicence='".I('post.bLicence')."'")->count()>0)
				{
					$company2 = M('Company')->where("bLicence='".I('post.bLicence')."'")->find();	
					$company = $company2;
						
				}
				if(M('Company')->where("orgID='".I('post.orgID')."'")->count()>0)
				{
					$company3 = M('Company')->where("orgID='".I('post.orgID')."'")->find();		
					$company = $company3;	
				}
				
				//dump($company); 

				if($company)
				{				
					$map['id'] = $company["id"] ;
					//$map['id'] = 161;
					$us = M('user');
					$user = $us->where($map)->find();
					//dump($user); 
					if($user)
					{
						$msg = '您在项目申报系统的用户名:'.$user["username"].' 密码:'.$user["password"].'【科创天使】';					

						$mobile = $company["mobile"];						
						$msgtype = 0;

						sendSMS($msg,$mobile,$msgtype);

						$tipmsg='用户名和密码以短信发送到您原来登记的手机号码.'.$mobile;

						$this->success($tipmsg,U('Home/Index/Index'));
					}
					else
					{
						$this->success('没有找到你的用户注册信息，请核对信息后重新找回密码.',U('Home/User/Findpwd'));
					}
				}
				else
				{
					$this->success('没有找到你的公司注册信息，请核对信息后重新找回密码.',U('Home/User/Findpwd'));
				}


				//测试用
				/*$msg = "测试：您在项目申报系统申请的用户名是tjac,密码是123456【科创天使】";	
				$mobile = 13803026441;
				$msgtype = 0;

				sendSMS($msg,$mobile,$msgtype);
				$this->success('测试：用户名和密码已以短信发送到您原来登记的手机号码.'+$mobile,U('Home/Index/Index'));  */

							
			}
			$this->display();
		}
		//end of Findpwd()

		//*********************************
		//SendMobileVerify()
		//send verify code to mobile
		//20140906  taochen
		//*********************************
		public function SendMobileVerify($mobile)
		{
			/*
			session_start();
			if( isset($_SESSION[’time’]) )
			{
				session_id();
				$_SESSION[’time’];
			}
			else
			{
				$_SESSION[’time’] = date("Y-m-d H:i:s");

			}
			$_SESSSION[’mcode’] = $mcode;

			if(strtotime($_SESSION[’time’])+180 < time())
			{
				session_destroy();
				unset($_SESSION);

			}
			*/
            
            $this->ajaxReturn('','验证码测试',1);
            
            //生成验证码
            $str = '1234567890';
            $mcode = null;
            for($i=0;$i<6;$i++) {
                $mcode.=$str[rand(0,9)];
            }

			
            $_SESSION['mcode'] = $mcode;
			dump(session("mcode"));
    	
				$smsmsg = '申报系统验证码：'.$mcode.'【科创天使】';					
	
				$msgtype = 0;
				sendSMS($smsmsg,$mobile,$msgtype);
								
				$msg='验证码发送成功';
				//$this->ajaxReturn(array('status'=>$msg));
				$this->ajaxReturn('0',$msg,1);
		}

		//*********************************
		//MobileVarify()
		//Mobile Varify
		//20140906  taochen
		//*********************************
		public function MobileVerify($mcode)
		{
			//$this->ajaxReturn('',$msg,0);
    		//$sendmcode =session("mcode");	
            
			$sendmcode = I('session.mcode');
			trace($sendmcode,'session中存的验证码');

			if($mcode == $sendmcode)
			{
				$msg='验证成功';
				$this->ajaxReturn('0',$msg,1);
			}
			else
			{
				$msg='验证失败请重试';
				$this->ajaxReturn('0',$msg,0);
								
			}				
				
		}
}