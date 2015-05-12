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
			$verify->entry(2);
		}
		
		public function login($uname = '', $pwd = '', $code = ''){
			if(IS_POST){ //登录验证
				if(check_verify($code))
				{
					$this->error('验证码输入错误！');
				}else
				{
					if(D('User')->login($uname,$pwd))
					{
						$this->success('登录成功！',U('Home/Index/index'));
					}else
					{
						$this->error('登录失败');
					}
				}
			}else
			{
				$this->display();
			}
		}
		public function Logout(){
				D('User')->logout();
				$this->redirect('User/login');
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
				
				$user = D('User');
				$data['username'] = I('post.username');
				$data['password'] = $_POST["password"];
				$data['level'] = 0;
				
				$user->create($data);
				if($user->id>0)
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
					$company->id = $user->id;
					$company->status = 0;
					$company->regTime = time_format(NOW_TIME);
					$company->regIp = get_client_ip(0);
					if($company->add())
					{
						D('User')->login($uname,$pwd);
						$this->success("注册成功",U('Home/Index/index'));
						return;
					}else
					{
						$this->error("注册成功，企业相关信息添加失败");
					}
				}else
				{
					$this->error("注册失败");
				}
			}
		}
}