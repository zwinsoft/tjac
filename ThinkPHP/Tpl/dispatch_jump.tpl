<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<script type="text/javascript" src="__STATIC__/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="__STATIC__/js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="__STATIC__/css/bootstrap-responsive.css">
<link rel="stylesheet" type="text/css" href="__STATIC__/css/index.css">
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff;  }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
</head>
<body >
<div id="head">


     <div class="container" style="width:950px;">
    		
        <div style="float:left;"><h1 class="logoTitle" style="font-size:30px;">天津市科技型中小企业天使投资项目申报系统</h1></div>

    </div>

</div>

<div class="container" style="width:950px;">
	
	<div class=" mainContent">
		<div class="system-message" style="text-align:center;">
			<present name="message">
			<p class="success"><?php echo($message); ?></p>
			<else/>
			<p class="error"><?php echo($error); ?></p>
			</present>
			<p class="detail"></p>
			<p class="jump">
			页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
			</p>
		</div>
		<script type="text/javascript">
		(function(){
		var wait = document.getElementById('wait'),href = document.getElementById('href').href;
		var interval = setInterval(function(){
			var time = --wait.innerHTML;
			if(time <= 0) {
				location.href = href;
				clearInterval(interval);
			};
		}, 1000);
		})();
		</script>
	</div>
</div>
</body>
</html>
