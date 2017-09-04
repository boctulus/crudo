	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<div style="display:none">	
	<?php
    	echo file_get_contents('http://www.ravaonline.com/v2/precios/panel.php?m=LID');	// lideres
		echo file_get_contents('http://www.ravaonline.com/v2/precios/panel.php?m=GEN');	// panel general
		//echo file_get_contents('http://www.ravaonline.com/v2/precios/panel.php?m=BON');
	?>
	</div>
	
	<script>
	var stocks = [];
	
	var dia,mes,dia_hoy, mes_hoy;
	[dia,mes] = $('.titulomodulotd>span')[0].innerHTML.trim().split('/');	// chequear no esa		
	[dia_hoy, mes_hoy ] = [new Date().getDate(), new Date().getMonth()+1];
	
	var dif = dia_hoy-parseInt(dia);
	if ((dif==0 || dif==1) && parseInt(mes)==mes)
	{	
		$('tr.ci').each((i,el)=>{	
			var tds = $(el).find('td');		
			var security = {};		
			
			security.name  = tds[0].innerText;
			security.close = tds[1].innerText;		
			security.per  = tds[2].innerText;
			security.prev = tds[3].innerText;
			security.open = tds[4].innerText;
			security.min  = tds[5].innerText;
			security.max  = tds[6].innerText;
			security.time = tds[7].innerText;
			security.nom  = tds[8].innerText;
			security.efe  = tds[9].innerText;
			
			stocks.push(security);
		});
		
		$('tr.cp').each((i,el)=>{	
			var tds = $(el).find('td');		
			var security = {};		
			
			security.name  = tds[0].innerText;
			security.close = tds[1].innerText;		
			security.per  = tds[2].innerText;
			security.prev = tds[3].innerText;
			security.open = tds[4].innerText;
			security.min  = tds[5].innerText;
			security.max  = tds[6].innerText;
			security.time = tds[7].innerText;
			security.nom  = tds[8].innerText;
			security.efe  = tds[9].innerText;
			
			stocks.push(security);
		});	
		
		// cambiar por promise
		setTimeout(()=>
		{	
			$.ajax({
				type: 'POST',
				url: 'http://localhost/predict/Titles/store_merval',
				data: {dia:dia,mes:mes, json: JSON.stringify(stocks)},
				dataType: 'text' // json
			})
			.done( function( rta ) {				
			$('body').append(rta);
				$('body').append('---');
			})
			.fail( function( data ) {
				console.log('fail');
				console.log(data.responseText);
			});
		},500);	
		
	}
	
	
	// @return int minutos que debo esperar antes de pedir mas datos.
	function getInterval(){
		var intervalo = 5;
		
		var d = new Date();
		var h = d.getHours();		
		var m = d.getMinutes();
		var w = d.getDay();
		
		// domingo
		if (w==0)
			intervalo = (24 - h + 10.5)*60 -m;
		else
			// sabado
			if (w==6)
				intervalo = (48 - h + 10.5)*60 -m;
			else
				// viernes afterhours
				if ((w==5) && (h>=18))
					intervalo = (72 - h + 10.5)*60 - m;
				else		
					if ((h>=11 && h<=17) || (h==10 && m>=30))
						intervalo = 5;
					else
						if (h>=18)
							//  hasta las 10:30 AM del dia sig.
							intervalo = (10.5 -h +24)*60 - m; 
						else
							// si es temprano
							if (h<11)
								intervalo = (10.5 - h)*60 + - m;	
							//else
							//	intervalo = 60;
		
		return intervalo;
	}
	
	// refresco 
	$(document).ready(function() {		
		var interval = getInterval();
		
		console.info('Seteo intervalo en '+interval.toString()+' minutos');
	
		// setTimeout
		setInterval(function() {
			window.location.reload(true);
		}, interval*60*1000);
	});
	</script>

	
	
	