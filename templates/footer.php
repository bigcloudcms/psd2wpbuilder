<?php
	global $virtue_premium;

	/**
	* 
	* @hooked kt_sitewide_shortcode_output - 10
	* @hooked kt_sitewide_calltoaction_output - 20
	*
	*/
	do_action('kt_before_footer');
?>
<?php if(isset($virtue_premium['footersection_width']) and $virtue_premium['footersection_width'] == 'boxed') { ?>
   <footer id="containerfooter" class="container footerclass" role="contentinfo">
  <div class="no-container">
<?php } else { ?>
   <footer id="containerfooter" class="footerclass" role="contentinfo">
  <div class="container">
<?php } ?>
      <div class="row">
  		<?php if(isset($virtue_premium['footer_layout'])) { $footer_layout = $virtue_premium['footer_layout']; } else { $footer_layout = 'onec'; }
  			if ($footer_layout == "fourc") {
  				if (is_active_sidebar('footer_1') ) { ?> 
					<div class="col-md-3 col-sm-6 footercol1">
					<?php dynamic_sidebar('footer_1'); ?>
					</div> 
            	<?php }; ?>
				<?php if (is_active_sidebar('footer_2') ) { ?> 
					<div class="col-md-3 col-sm-6 footercol2">
					<?php dynamic_sidebar('footer_2'); ?>
					</div> 
		        <?php }; ?>
		        <?php if (is_active_sidebar('footer_3') ) { ?> 
					<div class="col-md-3 col-sm-6 footercol3">
					<?php dynamic_sidebar('footer_3'); ?>
					</div> 
	            <?php }; ?>
				<?php if (is_active_sidebar('footer_4') ) { ?> 
					<div class="col-md-3 col-sm-6 footercol4">
					<?php dynamic_sidebar('footer_4'); ?>
					</div> 
		        <?php }; ?>
		    <?php } else if($footer_layout == "threec") {
		    	if (is_active_sidebar('footer_third_1') ) { ?> 
					<div class="col-md-4 footercol1">
					<?php dynamic_sidebar('footer_third_1'); ?>
					</div> 
            	<?php }; ?>
				<?php if (is_active_sidebar('footer_third_2') ) { ?> 
					<div class="col-md-4 footercol2">
					<?php dynamic_sidebar('footer_third_2'); ?>
					</div> 
		        <?php }; ?>
		        <?php if (is_active_sidebar('footer_third_3') ) { ?> 
					<div class="col-md-4 footercol3">
					<?php dynamic_sidebar('footer_third_3'); ?>
					</div> 
	            <?php }; ?>
            <?php } else if($footer_layout == "twoc") {
					if (is_active_sidebar('footer_double_1') ) { ?>
					<div class="col-md-6 col-sm-6 footercol1">
					<?php dynamic_sidebar('footer_double_1'); ?> 
					</div> 
		            <?php }; ?>
		        <?php if (is_active_sidebar('footer_double_2') ) { ?>
					<div class="col-md-6 col-sm-6 footercol2">
					<?php dynamic_sidebar('footer_double_2'); ?> 
					</div> 
		            <?php }; ?>
        <?php } else {
					if (is_active_sidebar('footer_single_1') ) { ?>
					<div class="col-md-12 footercol1">
					<?php dynamic_sidebar('footer_single_1'); ?> 
					</div> 
		            <?php }; ?>
		        <?php } ?>
        </div> <!-- Row -->
        <div class="footermenu clearfix">
    		
    		<?php if (has_nav_menu('footer_navigation')) :
        	?><div class="footernav clearfix"><?php 
              wp_nav_menu(array('theme_location' => 'footer_navigation', 'menu_class' => 'footermenu'));
            ?></div><?php
        	endif;?>
        	
    	</div><!-- footermenu -->
      </div><!-- no-container -->
</footer>
<?php wp_footer(); ?>
