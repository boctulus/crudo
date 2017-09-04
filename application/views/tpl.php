<!DOCTYPE html>
<html>
    <head>
        <title><?= $header['title']; ?></title>
		
		<?php
			echo meta('content-type','text/html; charset=utf-8','equiv');
			foreach ($header['meta'] as $m)
				echo meta($m['name'], $m['content'], $m['type']);
		
			foreach ($header['css'] as $_css)
				echo link_tag("css/$_css");
	
			foreach ($header['js'] as $_js)
				echo link_tag("js/$_js");
		?>

		
    </head>
    <body>		
        <?= $content; ?>
		
		<div id="footer">
		<?php
			if (isset($footer['content'])) 
				echo $footer['content']; 
			
			if (isset($footer['js'])) 
				foreach ($footer['js'] as $_js)
					echo link_tag("js/$_js");
		?>
		</div>
    </body>
</html>
