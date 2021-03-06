<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>{$page_title}</title>
<link href="{$C_PATH_ROOT}/assets/css/base.css" type='text/css' rel='stylesheet' />
</head>
<body>
	<div id='top'>
		<h1>{$C_APP_TITLE}</h1>
	</div>
	<div id='content'>
		<div class='error'>
		    {foreach $error_codes as $code}
		        <p class="error_code">{$code}</p>
		    {/foreach}
			
			{foreach $errors as $err}
		        <p class="error_message">{$err}</p>
		    {/foreach}
		</div>
		
		{$content_for_layout}
	</div>
	<div id='footer'>
		
	</div>
</body>
</html>