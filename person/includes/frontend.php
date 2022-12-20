<div class="<?php echo $module->get_classname(); ?>">
	<div class="fl-person-content">
	
		<?php
		
		// render fields via class render functions
		
		$module->render_image();
		
		$module->render_name();

		$module->render_title();
		
		$module->render_email();
		
		$module->render_phone();
		
		$module->render_fax();
		
		
		?>
		<div class="fl-person-text-wrap">
			<?php

			// bio / text field
			$module->render_text();

			?>
		</div>
		
		<?php
		

		
		
		?>
	</div>
	
</div>
