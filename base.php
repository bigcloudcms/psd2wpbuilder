<?php 
if (is_page('Membership Checkout')) { 
  do_action('get_header'); get_template_part('templates/headlanding');
} else { 
    do_action('get_header'); get_template_part('templates/head');
}  
  global $virtue_premium; 
  if(isset($virtue_premium["smooth_scrolling"]) && $virtue_premium["smooth_scrolling"] == '1') {$scrolling = '1';}  else if(isset($pinnacle["smooth_scrolling"]) && $pinnacle["smooth_scrolling"] == '2') { $scrolling = '2';} else {$scrolling = '0';}
  if(isset($virtue_premium["smooth_scrolling_hide"]) && $virtue_premium["smooth_scrolling_hide"] == '1') {$scrolling_hide = '1';} else {$scrolling_hide = '0';} 
  if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == '1') {$animate = '1';} else {$animate = '0';}
  if(isset($virtue_premium['sticky_header']) && $virtue_premium['sticky_header'] == '1') {$sticky = '1';} else {$sticky = '0';}
  if(isset($virtue_premium['product_tabs_scroll']) && $virtue_premium['product_tabs_scroll'] == '1') {$pscroll = '1';} else {$pscroll = '0';}
  if(isset($virtue_premium['header_style'])) {$header_style = $virtue_premium['header_style'];} else {$header_style = 'standard';}
  if(isset($virtue_premium['select2_select'])) {$select2_select = $virtue_premium['select2_select'];} else {$select2_select = '1';}
  ?>
<body <?php body_class(); ?> data-smooth-scrolling="<?php echo esc_attr($scrolling);?>" data-smooth-scrolling-hide="<?php echo esc_attr($scrolling_hide);?>" data-jsselect="<?php echo esc_attr($select2_select);?>" data-product-tab-scroll="<?php echo esc_attr($pscroll); ?>" data-animate="<?php echo esc_attr($animate);?>" data-sticky="<?php echo esc_attr($sticky);?>">

    <div class="siteloader-wrap transition-enabled" style="transition: none;opacity: 1;transform: translateZ(0px);"><div class="siteloader-inner"><div class="siteloader-cell"><div class="siteloader"><div class="siteloader-extra"></div></div></div></div></div>
    
<?php 
    if (is_page('Membership Checkout')) { 
        do_action('kt_beforeheader'); 
       do_action('kt_afterheader'); include kadence_template_path(); 
} else { 
  ?> 
    <?php if(isset($virtue_premium['headersection_width']) and $virtue_premium['headersection_width'] == 'full') { ?>
    <div id="wrapper">
      <div class="container">
             </div><!-- /.content -->
  </div><!-- /.wrap -->
<?php } else { ?>
 <div id="wrapper" class="container">
     <div id="header" class="container">
<?php } ?>
  <!--[if lt IE 8]><div class="alert"> <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'virtue'); ?></div><![endif]-->
  <?php
   do_action('kt_beforeheader');
    if($header_style == 'center') {
          if(isset($virtue_premium['shrink_center_header']) && $virtue_premium['shrink_center_header'] == "1") {
           get_template_part('templates/header-style-two-shrink');
          } else {
            get_template_part('templates/header-style-two');
          } 
    } else if ($header_style == 'shrink') {
      get_template_part('templates/header-style-three');
    } else {
      get_template_part('templates/header');
    }
  ?>
        
        <?php do_action('kt_afterheader');
        include kadence_template_path(); ?>

           <?php if (kadence_display_sidebar()) : ?>            
      <?php if(isset($virtue_premium['sidebar_layout']) && $virtue_premium['sidebar_layout'] == 'onlyrightsidebar') { ?>
             <aside id="ktsidebar" class="rightside <?php echo esc_attr(kadence_sidebar_class()); ?> kad-sidebar" role="complementary">
        <div class="sidebar">
          <?php include kadence_sidebar_path(); ?>
        </div><!-- /.sidebar -->
      </aside><!-- /aside -->
               <?php } ?> 
     <?php if(isset($virtue_premium['sidebar_layout']) && $virtue_premium['sidebar_layout'] == 'bothsidebars') { ?>
      <aside id="ktsidebar" class="rightside <?php echo esc_attr(kadence_sidebar_class()); ?> kad-sidebar" role="complementary">
        <div class="sidebar">
          <?php include kadence_sidebar_path(); ?>
        </div><!-- /.sidebar -->
      </aside><!-- /aside -->
        <?php } ?>  
    <?php endif; ?>
      </div><!-- /.row-->
          </div>
    </div><!-- /.content -->
  </div><!-- /.wrap --> <?php
} 
 ?>
<?php 
    if (is_page('Membership Checkout')) { 
} else { 
  do_action('get_footer'); get_template_part('templates/footer');
      ?>  <div class="footertext clearfix">
    <div class="container">
    <div class="credit">
    <?php $footertext = $virtue_premium['footer_text']; echo do_shortcode($footertext); ?>
     </div>
    </div> 
</div><!--Wrapper-->
<div class="overlay" aria-hidden="true"></div><?php
} 
 ?>
</body>
