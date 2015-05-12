<?php

namespace Home\Model;
use Think\Model;
//use User\Api\UserApi;

/**
 * 
 */
class UserModel extends Model{

    /* 自动验证规则 */
		protected $_validate = array(
			array('username', '/^[a-zA-Z]\w{0,30}$/', '文档标识不合法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
			array('username', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		);
	
		/* 自动完成规则 */
		protected $_auto = array(
			
			array('loginTime', NOW_TIME, self::MODEL_INSERT),
			array('regIp', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
			array('lastLoginIp', 0, self::MODEL_INSERT),
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
        		//echo '1';
        		//var_dump($user);
        	}
        	//echo '2';
        	//var_dump($user);
        }
        //echo '3';
				//var_dump($user);	
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
        
				
        session('user_id', $user['id']);
        session('user_name', $user['username']);
        session('user_level', $user['level']);

    }
    

}
