<?php
return array(
	//'配置项'=>'配置值'
	    /* 模块相关配置 */
     'DEFAULT_MODULE'     => 'Home',
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'LmCx:2E+z[!kD"1N>U~3cB{f9WSqZt]Tl/V&PM|0', //默认数据加密KEY

    /* 调试配置 */
    'SHOW_PAGE_TRACE' => true,

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => '115.28.142.237', // 服务器地址
    'DB_NAME'   => 'tjacTest', // 数据库名
    'DB_USER'   => 'tjacTest', // 用户名
    'DB_PWD'    => 'tjacTest_123',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'tjac_', // 数据库表前缀
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
 
    ),

    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'tjac', //session前缀
    'COOKIE_PREFIX'  => 'tjac_', // Cookie前缀 避免冲突
    'WEB_SITE_TITLE'=>'测试：天津市科技型中小企业天使投资项目申报系统',
    
    /* 短信配置 */
    'SMS_COMPANY_NOTIFY' => true,
	'SMS_URL'=>'http://sms.3etone.com',
	'SMS_ACCOUNT'=>'zhiyisd',
	'SMS_PWD'=>'zy123qwe',
	'SMS_UID'=>'235',
);
