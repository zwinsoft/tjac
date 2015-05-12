<?php

namespace Home\Model;
use Think\Model;
//use User\Api\UserApi;

/**
 * 文档基础模型
 */
class UserModel extends Model{

    /* 自动验证规则 */
		protected $_validate = array(
			array('username', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		);
	
		/* 自动完成规则 */
		protected $_auto = array(
			
			array('loginTime', time_format, self::MODEL_INSERT,'function',NOW_TIME),
			array('regIp', 'get_client_ip', self::MODEL_INSERT, 'function', 0),
			array('lastLoginIp', 'get_client_ip', self::MODEL_UPDATE, 'function', 0),
		);
    

    /**
     * 登录指定用户
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($username,$password){
    	  $map = array('username' => $username, 'password' => $password);
        
        $user = $this->field(true)->where($map)->select();
        if($user){
        	if(count($user)>0)
        	{
        		if($user[0]['isDeleted']==0)
        		{
        			/* 登录用户 */
        			$this->autoLogin($user[0]);
        			//记录行为
        			//var_dump($user);
        			add_log('user_login', 'loginSuccess', $user[0]['id'] );
        			return true;
        		}
        		add_log('user_login', 'loginFailed_isDeleted', $user[0]['id'] );
        		//echo '1';
        		//var_dump($user);
        	}
        	add_log('user_login', 'loginFailed_notExists'.$username.'|'.$password );
        	//echo '2';
        	//var_dump($user);
        }
        
        //echo '3';
				//var_dump($user);	
				add_log('user_login', 'loginFailed_DataError'.$username.'|'.$password );
        return false;
    }
		public function isExists($username)
		{
			$map = array('username' => $username);
			$user = $this->field(true)->where($map)->select();
			if($user && count($user) > 0){
				return true;
			}
			return false;
		}
    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_id', null);
        session('user_name', null);
        session('user_level', null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'id'             => $user['id'],
            'loginTime' => time_format(NOW_TIME),
            'lastLoginIp'   => get_client_ip(0),
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $c = M('Company')->where('id='.$user['id'])->getField('company');
        
        
		session('company',$c);
        session('user_id', $user['id']);
        session('user_name', $user['username']);
        session('user_level', $user['level']);

    }
    

}
