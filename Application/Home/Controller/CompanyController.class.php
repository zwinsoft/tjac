<?php
//
namespace Home\Controller;
use Think\Controller;
class CompanyController extends Controller {
    public function Base(){
    	$this->assign('m2','class="active"');
    	if(is_Complogin())
    	{
    		if(!IS_POST)
    		{
	    		$company = M("Company");
	    		$map = array('id' => session("user_id"));
	        
	        $data = $company->where($map)->select();
	    		$this->assign('data',$data[0]);
					$this->display();
				}else
				{
					$company = M('Company');
					//$data["id"] = I("post.id");
					$data["company"] = I('post.company');
					$data["where"] = I('post.cmbProvince').'-'.I('post.cmbCity').'-'.I('post.cmbArea');
					$data["subWhere"] = I('post.subWhere');
					$data["bLicence"] = I('post.bLicence');
					$data["orgID"] = I('post.orgID');
					$data["buildTime"] = I('post.buildTime');
					$data["regCapital"] = I('post.regCapital');
					$data["paidUpCapital"] = I('post.paidUpCapital');
					$data["comDirector"] = I('post.comDirector');
					$data["mobile"] =I('post.mobile') ;
					$data["email"] = I('post.email');
					$data["mainBusiness"] = I('post.mainBusiness');
					$data["passRegconition"] = I('post.passRegconition');
					$data["allCapital"] = I('post.allCapital');
					$data["netAssets"] = I('post.netAssets');
					$data["salesIncome"] = I('post.salesIncome');
					$data["netProfit"] = I('post.netProfit');
					$data["loginTime"] = time_format(NOW_TIME);
					$data["regIp"] = get_client_ip(0);
					//dump($data);
					if($company->where('id='.I('post.id'))->save($data)==1)
					{
						if(I('post.isnext')=="1")
						{
							$this->success("修改成功", U('Company/Files'));	
						}else
						{
							$this->success("修改成功");
						}
					}else
					{
						$this->error('更新失败');
					}
					
				}
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

    public function files(){
    	$this->assign('m3','class="active"');
    	if(is_Complogin())
			{
				if(IS_POST)
				{
						$filenum = M('file')->where('companyid='.session("user_id").' and type in (1,2,3,4,5,6,7,8,10,11)')->count();
						if($filenum<10)
						{
							$this->error('上传资料不全，请补充后再提交');
							return;
						}

						$company = M('company');
						$company->sbTime = time_format(NOW_TIME);
			    	$company->status = 3; //提交公司资质待审核
			    	$company->where('id='.session("user_id"))->save();
			    	$history = M('history');
			    	$history->create();
			    	$history->uId = session("user_id");
			    	$history->createTime = time_format(NOW_TIME);
			    	$history->operUser = session("user_id");
			    	$history->operIP = get_client_ip(0);
			    	$history->type="提交审核公司资料";
			    	$history->message= "[".session("user_name")."]将公司资质提交审核";
						$history->add();
			    	$this->success('提交审核成功，请您耐心等待',U('Home/Index/Index'),3);
				}else
				{
				$company =M('company')->where('id='.session("user_id"))->find();
				$this->assign('data',$company);
				$this->display();
				}
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
    public function info1(){
			$this->assign('m6','class="active"');
			if(is_Complogin())
			{
				if(IS_POST)
				{
					if($_POST["id"]==session("user_id"))
					{
						$info = M("info");
						
						$rules = array ( 
								array('updateTime', time_format, 3,'function',NOW_TIME),
							);
						$info->create($_POST);
						if($info->where("id=".session("user_id"))->count()>0)
						{
							$info->auto($rules)->save();
						}
						else
						{
							$info->auto($rules)->add();
						}
						if(I('post.isnext')=="1")
						{
							$this->success("保存成功，请填写企业知识产权", U('Company/Info2'));	
						}else
						{
							$this->success("保存成功");
						}
					  
					}else
					{
						$this->error("超时,或者参数错误");
					}
				}else
				{
				
				$i =M("info");
				$vo = $i->where("id=".session("user_id"))->find();
				$c = M("company")->where("id=".session("user_id"))->find();
				if(!isset($vo))
				{
					$vo["qiyemingcheng"] = $c["company"];
					$vo["yingyezhizhaohaoma"] = $c["bLicence"];
					$vo["zuzhijigoudaima"] = $c["orgID"];
					$vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
					$vo["zuceziben"] = $c["regCapital"];
					$vo["shishouziben"] = $c["paidUpCapital"];
					
				}
				$vo["comDirector"] = $c["comDirector"];
				$vo["mobile"] = $c["mobile"];
				$vo["email"] = $c["email"];
				$this->assign("vo",$vo);
				$company =M('company')->where('id='.session("user_id"))->find();
				$this->assign('data',$company);
				//$this->display();
	
				$infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".session("user_id"))->select();		
				//var_dump($infoGudong);
				$this->assign("infoGudong",$infoGudong);
				$this->display();
	
				}
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

    public function save()
    {
    	if(!is_Complogin())
    	{
				$this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
			}
  			if(IS_POST)
			{
				$data = $_POST ;
				$data["updateTime"] = time_format(NOW_TIME);
    		$info = M("infoGudong");
				
				$info->create($data);
				if($info->where("id=".I("post.id")." and uid=".session("user_id"))->count()>0)
				{
					$info->save();
				}
				else
				{
					$this->ajaxReturn(array('status'=>'这不是你的项目'));
				}
			}
			$this->ajaxReturn(array('status'=>'OK'));
    }
    public function del()
    {
    	if(!is_Complogin())
    	{
				$this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
			}
    	
			if(IS_POST)
			{
					$data = $_POST ;
					$data["updateTime"] = time_format(NOW_TIME);
					$data["isDeleted"] = 1;
	    		$info = M("infoGudong");
								
					$info->create($data);
					if($info->where("id=".I("post.id")." and uid=".session("user_id"))->count()>0)
					{
						$info->save();
					}
					else
					{
						$this->ajaxReturn(array('status'=>'这不是你的项目'));
					}
			}
			$this->ajaxReturn(array('status'=>'OK'));
	   }
	
	public function add()
    {
    	if(!is_Complogin())
    	{
				$this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
			}
	  	if(IS_POST)
			{
				$data = $_POST ;
				$data["updateTime"] = time_format(NOW_TIME);
    		    $info = M("infoGudong");
				$data["uid"]=session("user_id");
				$data["isDeleted"] = 0;
				$info->create($data);
				$info->auto($rules)->add();
				
			}
			$this->ajaxReturn(array('status'=>'OK'));
  	}


    public function info2(){
    	$this->assign('m7','class="active"');
    	if(is_Complogin())
    	{
    		
    		if(IS_POST)
				{
					$data = $_POST ;
					$data["updateTime"] = time_format(NOW_TIME);
					if($_POST["uid"]==session("user_id"))
					{
						//dump(I("get.act"));
							if(I("get.act")=="mod" || I("get.act")=="add")
							{
								
							}else if(I("get.act")=="mod2" || I("get.act")=="add2")
							{
								
							}
							if(I("get.act")=="")
							{
								$info = M("Zhishichanquan");
							
								$rules = array ( 
										array('updateTime', time_format, 3,'function',NOW_TIME),
									);
								$info->create($data);
								if($info->where("id=".session("user_id"))->count()>0)
								{
									$info->auto($rules)->save();
								}
								else
								{
									$info->auto($rules)->add();
								}
							}
							if(I('post.isnext')=="1")
							{
								$this->success("保存成功,请继续填写项目基本情况", U('Project/Info1'));	
							}else
							{
								$this->success("保存成功");
							}
							//$this->success("保存成功");
						
					}else
					{
						$this->error("超时,或者参数错误");
					}
				}else
				{
				if(I("get.act")=="del")
				{
					
					
					
				}
				else if(I("get.act")=="del2")
				{
					 
					
				}
				
				$i =M("Zhishichanquan");
				$vo = $i->where("id=".session("user_id"))->find();
				$zl = M("zhuanli")->where("isDeleted=0 and uid=".session("user_id"))->select();
				$zzq = M("zhuzuoquan")->where("isDeleted=0 and uid=".session("user_id"))->select();
				//dump($vo);
				$vos = array();
				if(!isset($vo))
				{
					$vos["zhuanli"] = $zl;
					$vos["zhuzuoquan"] = $zzq;
					$vos["zscq"] =$vo;
				}else
				{
					$vos["zhuanli"] = $zl;
					$vos["zhuzuoquan"] = $zzq;	
					$vos["zscq"] =$vo;
				}
				//dump($vo);
				$this->assign("vo",$vos);
				$company =M('company')->where('id='.session("user_id"))->find();
				$this->assign('data',$company);
				$this->display();
				}
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
    public function info2_mod()
	{
		$info = M("Zhuanli");
		$data = $_POST ;
		$data["updateTime"] = time_format(NOW_TIME);
		$data["uid"]=session("user_id");
		$rules = array ( 
				array('updateTime', time_format, 3,'function',NOW_TIME),
			);
		
		$info->create($data);
		//dump($_POST);
		if($info->where("id=".I("post.id"))->count()>0)
		{
			$info->auto($rules)->save();
		}
		else
		{
			$info->auto($rules)->add();
		}
		$this->ajaxReturn(array('status'=>'OK'));
	}

	public function info2_del()
	{
		$info = M("Zhuanli");
		$info->isDeleted=1;
		$info->updateTime = time_format(NOW_TIME);
		$info->where("id=".I("post.id"))->save();
		$this->ajaxReturn(array('status'=>'OK'));
	}
	
	public function info2_mod2()
	{
		$info = M("Zhuzuoquan");
		$data = $_POST ;
		$data["updateTime"] = time_format(NOW_TIME);	
		$data["uid"]=session("user_id");	
		$rules = array ( 
				array('updateTime', time_format, 3,'function',NOW_TIME),
			);
		$info->create($data);
		if($info->where("id=".I("post.id"))->count()>0)
		{
			$info->auto($rules)->save();
		}
		else
		{
			$info->auto($rules)->add();
		}
		$this->ajaxReturn(array('status'=>'OK'));
	}

	public function info2_del2()
	{
		$info = M("Zhuzuoquan");	
		$info->isDeleted=1;
		$info->updateTime = time_format(NOW_TIME);
		$info->where("id=".I("post.id"))->save();
		$this->ajaxReturn(array('status'=>'OK'));
	}
	
	public function filedetail($type)
    {
    	$uid=I('get.userid');
    	if($uid=="")
    	{
    		$uid=	session("user_id");
    	}
    	$file = M('file');
    	$f = $file->where('isDeleted=0 and type = '.$type.' and uploaduser='.$uid)->find();	
    	//dump($f);
    	if($f)
    	{
    		$this->show("<a target='_blank' href='/Public/".$f['url']."'>".$f['title']."</a>");	
    	}else
    	{
    		$this->show('未上传文件');	 
    	}
    }
    public function filesdetails($type)
    {
    	$uid=I('get.userid');
    	if($uid=="")
    	{
    		$uid=	session("user_id");
    	}
    	if(I("get.act")=="del")
    	{
    		$d["isDeleted"]=1;
    		M('file')->where("uid='".I("get.uid")."'")->save($d);
    	}
    	$company =M('company')->where('id='.$uid)->find();
    	$file = M('file');
    	$fs = $file->where('isDeleted=0 and type = '.$type.' and uploaduser='.$uid)->select();	
    	//dump($f);
    	if($fs)
    	{
    		$content="";
    		foreach($fs as $f)
    		{
    			//dump($company.status);
    			if($company["status"] <= 5)
    			{
    				$content=$content."<a target='_blank' href='/Public/".$f['url']."'>".$f['title']."</a> | <a href='{:U(Company/FilesDetails)}&type=".$type."&act=del&uid=".$f['uid']."'>删除此附件</a><br/>";	
    			}	else
    			{
    				$content=$content."<a target='_blank' href='/Public/".$f['url']."'>".$f['title']."</a> <br/>";
    			}
    		}
    		$this->show($content);
    	}else
    	{
    		$this->show('未上传文件');	
    	}
    	
    }
    public function upload()
    {
    	
			/* 调用文件上传组件上传文件 */
			$config = array(
			    'maxSize'    =>    10240000,
			    'savePath'   =>    './Uploads/',
			    'rootPath'	 =>		 './Public/',
			    'saveRule'   =>    array('get_file_name',array('__FILE__')),
			    'exts'       =>    array('jpg', 'gif', 'png', 'jpeg','doc','docx','xls','xlsx','zip','rar'),
			    'autoSub'    =>    true,
			    'subName'    =>    'get_user_id',
			);
			$upload = new \Think\Upload($config);// 实例化上传类
			if($_FILES['file1'])
			{
				$info =   $upload->uploadOne($_FILES['file1']);
		    $type = 1;
		  }
			if($_FILES['file2'])
			{
				$info =   $upload->uploadOne($_FILES['file2']);
		    $type = 2;
		  }
		  if($_FILES['file3'])
			{
				$info =   $upload->uploadOne($_FILES['file3']);
		    $type = 3;
		  }
		  if($_FILES['file4'])
			{
				$info =   $upload->uploadOne($_FILES['file4']);
		    $type = 4;
		  }
		  if($_FILES['file5'])
			{
				$info =   $upload->uploadOne($_FILES['file5']);
		    $type = 5;
		  }
		  if($_FILES['file6'])
			{
				$info =   $upload->uploadOne($_FILES['file6']);
		    $type = 6;
		  }
		  if($_FILES['file7'])
			{
				$info =   $upload->uploadOne($_FILES['file7']);
		    $type = 7;
		  }
		  if($_FILES['file8'])
			{
				$info =   $upload->uploadOne($_FILES['file8']);
		    $type = 8;
		  }
		  if($_FILES['file9'])
			{
				$info =   $upload->uploadOne($_FILES['file9']);
		    $type = 9;
		  }
		  if($_FILES['file10'])
			{
				$info =   $upload->uploadOne($_FILES['file10']);
		    $type = 10;
		  }
		  if($_FILES['file11'])
			{
				$info =   $upload->uploadOne($_FILES['file11']);
		    $type = 11;
		  }
		  if($_FILES['file12'])
			{
				$info =   $upload->uploadOne($_FILES['file12']);
		    $type = 12;
		  }
		  if($_FILES['file13'])
			{
				$info =   $upload->uploadOne($_FILES['file13']);
		    $type = 13;
		  }
		  if($_FILES['file14'])
			{
				$info =   $upload->uploadOne($_FILES['file14']);
		    $type = 14;
		  }
		  if($_FILES['file15'])
			{
				$info =   $upload->uploadOne($_FILES['file15']);
		    $type = 15;
		  }
		  if(!$info) {// 上传错误提示错误信息
		    	//dump($upload);
		       $this->show("<script>alert('".$upload->getError()."')</script>");
			   $this->assign("msg",$upload->getError());
		  }else{// 上传成功 获取上传文件信息
	    		//dump($info);;
	        if($type>14)
	        {
	        	$files = M("file");
		        $f = $files->where('type = '.$type.' and uploaduser='.session("user_id"))->find();
						
						$data['uid'] = $info['savename'];
						$data['type'] = $type;
						$data['url'] = $info['savepath'].$info['savename'];
						$data['size'] = $info['size'];
						$data['uploadUser'] = session("user_id");
						$data['mime'] = $info['ext'];
						$data['companyId'] =  session("user_id");
						$data['projectId'] = session("user_id");
						$data['title'] = $info['name'];
						$data['isDeleted'] = 0;
						$files->add($data);
						
	        }else
	        {
		        $files = M("file");
		        $f = $files->where('type = '.$type.' and uploaduser='.session("user_id"))->find();
						
						$data['uid'] = $info['savename'];
						$data['type'] = $type;
						$data['url'] = $info['savepath'].$info['savename'];
						$data['size'] = $info['size'];
						$data['uploadUser'] = session("user_id");
						$data['mime'] = $info['ext'];
						$data['companyId'] =  session("user_id");
						$data['projectId'] = session("user_id");
						$data['title'] = $info['name'];
						$data['isDeleted'] = 0;
						if($f)
						{
							$files->where('type = '.$type.' and uploaduser='.session("user_id"))->save($data);
						}else
						{
							$files->add($data);
						}
					}
					if($type>14) 
					{
						$this->redirect('Company/filesdetails',array('type'=>$type,'status'=>'sucess'));
					}
					else
					{
						$this->redirect('Company/filedetail',array('type'=>$type,'status'=>'sucess'));
					}
	    }
	  }
	    
}