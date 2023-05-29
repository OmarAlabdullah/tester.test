<div class="image-banner contact-banner<?=($controller['webp'] ? '' : ' no-webp')?>">
	<div class="site-width">
		<div class="image-banner-orange-block contactdetails">
			CONTACT
		</div>
	</div>
</div>

<div class="content_for_layout_holder">
	<div class="content_for_layout">
		
		<div class="contact_holder">
			
			<div class="contact_right_holder">
				<?php
					if(post())
					{
				?>
				Bedankt voor uw berricht.
				<br /><br />
				We zullen z.s.m. uw contactaanvraag in behandeling nemen.
				<?php
					}else
					{
				?>
				<form method="post" action="<?=SELF?>">
					
					<input type="hidden" name="session_id" value="" />
					
					Naam:<br />
					<input type="text" name="name" /><br />
					<br />
					<div style="display: none; ">
					Achternaam:<br />
					<input type="text" name="surname" /><br />
					<br />
					</div>
					Straatnaam en huisnummer*<br />
					<input type="text" name="street_homenumber" required /><br />
					<br />
					Plaats<br />
					<input type="text" name="city" /><br />
					<br />
					Emailadres*<br />
					<input type="text" name="email" required /><br />
					<br />
					Telefoonnummer*<br />
					<input type="text" name="phone" required /><br />
					<br />
					Vraag/opmerkingen<br />
					<textarea name="remarks"></textarea>
					<br />
					<br />
					<button class="btn" href="/">VERSTUUR</button>
				</form>
				<?php
					}
				?>
				<span class="clear"></span>
			</div>
			<span class="clear"></span>
			
			<div class="contact_left_holder">
				<a class="contact_left_block" href="/vraag_en_antwoord">
					<div class="contact_left_block_image">
						<img src="/assets/images/icon-vraag-antwoord.png" />
					</div>
					<div class="contact_left_block_content">
						<b>Heeft u een vraag?</b><br />
						<span class="contact_left_block_second">Bekijk hier de meestgestede vragen ></span>
					</div>
				</a>
				<a class="contact_left_block" href="tel:0418234444">
					<div class="contact_left_block_image">
						<img src="/assets/images/icon-telefoon.png" />
					</div>
					<div class="contact_left_block_content">
						<b>+31 (0)418 - 23 44 44</b><br />
						<span class="contact_left_block_second">Wij zijn bereikbaar van maandag t/m vrijdag van 09:00 tot 17:00 uur.</span>
					</div>
				</a>
				<a class="contact_left_block" href="https://wa.me/1418234444" target="_blank" rel="noreferrer noopener">
					<div class="contact_left_block_image">
						<img src="/assets/images/icon-whatsapp.png" />
					</div>
					<div class="contact_left_block_content">
						<b>WhatsAppen</b><br />
						<span class="contact_left_block_second">U kunt ons appen op ons vaste telefoonnummer +31(0)418-234444.</span>
					</div>
				</a>
				<a class="contact_left_block" href="mailto:info@drs-infra.nl">
					<div class="contact_left_block_image">
						<img src="/assets/images/icon-mailen.png" />
					</div>
					<div class="contact_left_block_content">
						<b>info@drs-infra.nl</b><br />
						<span class="contact_left_block_second">Wij beantwoorden uw mail binnen 1 werkdag.</span>
					</div>
				</a>
			</div>
		</div>
		
		<span class="clear"></span>
		
	</div>
</div>

<script>
$(document).ready(function()
{
	$('textarea[name="remarks"]').focus(function()
	{
		$('input[name="session_id"]').val(<?=(time())?>);
	});
});
</script>