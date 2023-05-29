

<div class="app_center">
	<div class="big_icon"><span class="fas fa-info"></span></div>
	<div class="maintext">DRS Infra</div>
	<div class="subtext"><a href="tel:+31182239888">+31 (0)182 - 239 888</a></div>
</div>

<br />

<div class="text_block">
	
	Login: <?=$userLoggedIn['Worker']['name']?> [<?=$userLoggedIn['Worker']['id']?>]<br />
	App revision: <?=$controller['revision']?><br />
	Device: <?=($controller['mobile'] ? ($controller['iphone'] ? 'iPhone' : 'Mobile or Tablet') : 'Desktop')?><br />
	WebP support: <?=($controller['webp'] ? 'Yes' : 'No')?><br />
	<!--Installatie datum: <?=($userLoggedIn['Worker']['app_install_date'] != '0000-00-00' ? date('d-m-Y', strtotime($userLoggedIn['Worker']['app_install_date'])) : '<b>Nog niet geinstalleerd</b>')?><br />-->
	In app: <span class="app_status"></span><br />
	Service worker registered: <span class="service_worker_status"></span><br />
	Push registered: <span class="push_status"></span><br />
	Phone language: <?=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)?><br />
	Connection speed: <span class="connection_speed"></span><br />
	
</div>

<div style="clear: both;"></div>

<div class="app_center">
	<a href="/workers/settings"><?=tl('Terug naar instellingen')?></a>
</div>

<br /><br />

<div id="refresher"></div>

<script>
	var start_y = -1;
	var refresh_pulses = 0;
	$(document).ready(function()
	{
		back_button('<?=tl('Instellingen')?>', '/workers/settings');
		
		console.log('match',  (window.matchMedia('(display-mode: standalone)').matches) );
		$('.app_status').html((window.matchMedia('(display-mode: standalone)').matches) ? 'Yes' : 'No');
		
		$('.connection_speed').html(navigator.connection.effectiveType);
		
		$('body').on('touchstart', function(e)
		{
			refresh_pulses = 0;
			start_y = e.touches[0].pageY + $(window).scrollTop();
		});
		$('body').on('touchend', function(e)
		{
			console.log('rp', refresh_pulses);
			if(refresh_pulses >= 100)
			{
				location.reload(true);
			}
			refresh_pulses = 0;
			start_y = -1;
			$('#refresher').hide();
		});
		
		$('body').on('touchmove', function(e)
		{
			if(start_y >= 0)
			{
				var diff = start_y - e.touches[0].pageY;
				
				if(diff < -20)
				{
					var opc = (refresh_pulses / 100);
					$('#refresher').css('opacity', opc).show();
					if(refresh_pulses >= 100)
						$('#refresher').css('color', '#2f7bff');
					else
						$('#refresher').css('color', '');
					refresh_pulses = Math.abs(diff);
				}
			}
		});
	});
</script>