<?php
	
	/*
		Please add following to htaccess (after RewriteEngine On):
		
		# IE Redirect
		RewriteCond %{REQUEST_FILENAME} !-f
		#RewriteCond %{REQUEST_FILENAME} !-d
		RewriteCond %{HTTP_USER_AGENT} "MSIE [6-8]" [NC]
		RewriteRule .? ie.php [L]
	*/
	
	function ie_version()
	{
		if ( preg_match('/(?i)msie ([6-8]+(\.[6-8]+)?)/i', $_SERVER['HTTP_USER_AGENT'], $matches) )
		{
			return (int)$matches[1];
		}
		
		return false;
	}
	
	switch ( ie_version() )
	{
		case 6:
			$year		= 2001;
			break;
		case 7:
			$year		= 2006;
			break;
		case 8:
			$year		= 2009;
			break;
		default:
			$year		= '';
			break;
	}
	
	if ( isset($_SERVER['HTTP_HOST']) )
	{
		$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
		$base_url .= '://'. $_SERVER['HTTP_HOST'];
	}
	else
	{
		$base_url = 'http://www.kwtdesign.co.uk';
	}
	
	$base_url = trim($base_url, '/') . '/MSIE/';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		
		<title>Why update browser?</title>
		
		<base href="<?=$base_url?>">

		<meta name="robots" content="noindex, nofollow">
		
		<meta http-equiv="cleartype" content="on">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<style>
			*:focus {
				outline: none;
			}
			
			body {
				background-color: #124F93;
				color: white;
				font-family: sans-serif;
				font-weight: 100;
				text-align: center;
				min-width: 610px;
			}
			
			h1 {
				margin: 0;
				padding: 0;
				font-weight: 100;
				font-size: 42px;
				margin-top: 200px;
			}
			
			p {
				font-size: 15px;
				font-weight: 100;
			}
			
			.btn-upgrade {
				width: 150px;
				height: 54px;
				line-height: 54px;
				background-color: #336FB3;
				color: white;
				text-decoration: none;
				font-size: 16px;
				text-align: center;
				display: block;
				margin: 5px auto;
				margin-top: 40px;
			}
			
			.btn-upgrade:hover {
				background-color: #2C619E;
			}
			
			hr {
				width: 420px;
				margin: 0;
				padding: 0;
				border: 0;
				border-top: 1px solid #407BBF;
				margin: 30px auto;
			}
			
			.event {
				width: 610px;
				margin: 50px auto;
				margin-top: 100px;
			}
			.event-l {
				width: 50%;
				float: left;
				min-height: 1px;
			}
			.event-l img {
				width: 100%;
			}
			.event-r {
				width: 50%;
				float: left;
				min-height: 1px;
				margin-top: 50px;
			}
			.event-title {
				color: #FFCC00;
				font-size: 24px;
				display: block;
				padding-left: 40px;
				text-align: left;
			}
			.event-description {
				font-size: 15px;
				display: block;
				padding-left: 40px;
				text-align: left;
				margin-top: 15px;
				line-height: 20px;
			}
		</style>
		
	</head>
	<body>
		
		<h1>YOUR BROWSER IS <?=$year ? (date('Y') - $year) . ' YEARS' : 'VERY'?> OLD</h1>
		
		<p>Perhaps it's time to move on. Download a modern browser that supports our website.</p>
		
		<p><a href="http://browsehappy.com" class="btn-upgrade" target="_blank">Upgrade Now <span style="font-size: 19px; font-weight: 100; font-family: arial">&rsaquo;</span></a></p>
		
		<hr>
		
		<?php if ( $year ) { ?>
			
			<div class="event">
				
				<?php switch ( $year ) {
					
					case 2001: ?>
						<div class="event-l">
							<img src="ico-potter.png" width="275" height="218">
						</div>
						<div class="event-r">
							<span class="event-title">In 2001....</span>
							<span class="event-description">The first Harry Potter film, the Philosopher's Stone made its debut in cinemas worldwide.</span>
						</div>
					<?php break;
					
					case 2006: ?>
						<div class="event-l">
							<img src="ico-yt.png" width="275" height="218">
						</div>
						<div class="event-r">
							<span class="event-title">In 2005....</span>
							<span class="event-description">The first video was uploaded to YouTube.</span>
						</div>
					<?php break;
					
					case 2009: ?>
						<div class="event-l">
							<img src="ico-jackson.png" width="275" height="218">
						</div>
						<div class="event-r">
							<span class="event-title">In 2009....</span>
							<span class="event-description">Michael Jackson died (aged 50).</span>
						</div>
					<?php break;
					
				} ?>
				
			</div>
			
		<?php } ?>
		
	</body>
</html>