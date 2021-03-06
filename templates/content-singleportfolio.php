
	<div id="pageheader" class="titleclass">
		<div class="container">
			<div class="page-header single-portfolio-item">
				<div class="row">
					<div class="col-md-8 col-sm-8">
						<?php if(kadence_display_portfolio_breadcrumbs()) { kadence_breadcrumbs(); } ?>
									<h1 class="entry-title"><?php the_title(); ?></h1>
		   			</div>
		   			<div class="col-md-4 col-sm-4">
		   				<div class="portfolionav clearfix">
		   					<?php global $post, $virtue_premium; if(!empty($virtue_premium['portfolio_arrow_nav']) && ($virtue_premium['portfolio_arrow_nav'] == 'cat') ) {$arrownav = true;} else {$arrownav = false;}	
		   					$parent_link = get_post_meta( $post->ID, '_kad_portfolio_parent', true ); if(!empty($parent_link) && ($parent_link != 'default')) {$parent_id = $parent_link;} else {$parent_id = $virtue_premium['portfolio_link'];}
		   					previous_post_link_plus( array('order_by' => 'menu_order', 'loop' => true, 'in_same_tax' => $arrownav, 'format' => '%link', 'link' => '<i class="icon-arrow-left"></i>') ); ?>
					   			<?php if( !empty($parent_id)){ ?>
					   				<a href="<?php echo get_page_link($parent_id); ?>">
									<?php } else {?> 
									<a href="../">
									<?php } ?>
					   				<i class="icon-grid"></i></a> 
					   				<?php next_post_link_plus( array('order_by' => 'menu_order', 'loop' => true, 'in_same_tax' => $arrownav, 'format' => '%link', 'link' => '<i class="icon-arrow-right"></i>') ); ?>
		   				</div>
		   			</div>
		   		</div>
		</div>
		</div><!--container-->
	</div><!--titleclass-->
  <?php if ( ! post_password_required() ) { ?>
	<?php global $post; $layout = get_post_meta( $post->ID, '_kad_ppost_layout', true ); 
						$ppost_type = get_post_meta( $post->ID, '_kad_ppost_type', true );
						 $imgheight = get_post_meta( $post->ID, '_kad_posthead_height', true );
						 $imgwidth = get_post_meta( $post->ID, '_kad_posthead_width', true );
             $autoplay = get_post_meta( $post->ID, '_kad_portfolio_autoplay', true );
             if(isset($autoplay) && $autoplay == 'no') {$slideauto = 'false';} else {$slideauto = 'true';}
			if($layout == 'above')  {
				$imgclass = 'col-md-12';
				$textclass = 'pcfull clearfix';
				$entryclass = 'col-md-8';
				$valueclass = 'col-md-4';
				$slidewidth_d = 1140;
		} elseif ($layout == 'three')  {
				$imgclass = 'col-md-12';
				$textclass = 'pcfull clearfix';
				$entryclass = 'col-md-12';
				$valueclass = 'col-md-12';
				$slidewidth_d = 1140;
			} else {
				$imgclass = 'col-md-7';
				$textclass = 'col-md-5 pcside';
				$entryclass = '';
				$valueclass = '';
				$slidewidth_d = 653;
			 	}
			 	$portfolio_margin = '';
		if (!empty($imgheight)) { $slideheight = $imgheight; } else { $slideheight = 450; } 
		if (!empty($imgwidth)) { $slidewidth = $imgwidth; } else { $slidewidth = $slidewidth_d; } 
		 ?>
		 <?php if ($ppost_type == 'imgcarousel') { ?>
	 <section class="postfeat carousel_outerrim loading">
            <div id="portfolio-carousel-gallery" class="fredcarousel fadein-carousel" style="overflow:hidden; height: <?php echo esc_attr($slideheight);?>px">
                <div class="gallery-carousel kad-light-wp-gallery initimagecarousel" data-carousel-container="#portfolio-carousel-gallery" data-carousel-transition="300" data-carousel-auto="<?php echo $slideauto; ?>" data-carousel-speed="7000" data-carousel-id="portfolioimgcarousel">
                  <?php global $post;
                      $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                            $attachments = array_filter( explode( ',', $image_gallery ) );
                              if ($attachments) {
                                foreach ($attachments as $attachment) {
                                $attachment_url = wp_get_attachment_url($attachment , 'full');
                                $image = aq_resize($attachment_url, null, $slideheight, false, false);
                                  if(empty($image)) {
                                    $image = array();
                                    $image[0] = $attachment_url;
                                    $image[1] = $slidewidth;
                                    $image[2] = $slideheight;
                                  }
                                  echo '<div class="carousel_gallery_item" style="float:left; margin: 0 5px; width:'.esc_attr($image[1]).'px; height:'.esc_attr($image[2]).'px;">';
                                  echo '<a href="'.esc_url($attachment_url).'" data-rel="lightbox">';
                                  echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" alt="'.esc_attr(get_post_field('post_excerpt', $attachment)).'"/>';
                                  echo '</a></div>';
                                }
                              }
                          } ?>      
            </div> <!--post gallery carousel-->
            <div class="clearfix"></div>
              <a id="prevport-portfolioimgcarousel" class="prev_carousel icon-arrow-left" href="#"></a>
              <a id="nextport-portfolioimgcarousel" class="next_carousel icon-arrow-right" href="#"></a>
          </div> <!--fredcarousel-->
        </section>
      <?php } ?>
    <div id="content">
     <div class="container">
    <div class="row">
      <div class="main <?php echo kadence_main_class(); ?> portfolio-single" role="main">
      <?php while (have_posts()) : the_post(); ?>		
  <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
      <div class="postclass">
      	<div class="row">
      		<div class="<?php echo $imgclass; ?>">
				<?php if ($ppost_type == 'flex') { ?>
					<div class="flexslider loading kt-flexslider kad-light-gallery" style="max-width:<?php echo esc_attr($slidewidth);?>px;" data-flex-speed="7000" data-flex-anim-speed="400" data-flex-animation="fade" data-flex-auto="<?php echo $slideauto; ?>">
                       <ul class="slides">
                          	<?php global $post;
                          	$image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          		if(!empty($image_gallery)) {
                    				$attachments = array_filter( explode( ',', $image_gallery ) );
                    					if ($attachments) {
											foreach ($attachments as $attachment) {
												$attachment_url = wp_get_attachment_url($attachment , 'full');
												$caption = get_post($attachment)->post_excerpt;
												$image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
													if(empty($image)) {$image = $attachment_url;}
												echo '<li><a href="'.esc_url($attachment_url).'" data-rel="lightbox" title="'.esc_attr($caption).'"><img src="'.esc_url($image).'" alt="'.esc_attr($caption).'" /></a></li>';
											}
										}
                    			} else {
                    				$attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
									$attachments = get_posts($attach_args);
										if ($attachments) {
											foreach ($attachments as $attachment) {
												$attachment_url = wp_get_attachment_url($attachment->ID , 'full');
												$image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
													if(empty($image)) {$image = $attachment_url;}
												echo '<li><a href="'.$attachment_url.'" data-rel="lightbox"><img src="'.esc_url($image).'"/></a></li>';
											}
                    					}	
								} ?>                            
						</ul>
              	</div> <!--Flex Slides-->
				<?php 	
				} else if ($ppost_type == 'rev' || $ppost_type == 'cyclone' || $ppost_type == 'ktslider') {

					global $post; $shortcodeslider = get_post_meta( $post->ID, '_kad_shortcode_slider', true ); if(!empty($shortcodeslider)) echo do_shortcode( $shortcodeslider );

				} else if ($ppost_type == 'video') { ?>
					
					<div class="videofit">
                  		<?php global $post; $video = get_post_meta( $post->ID, '_kad_post_video', true ); echo $video; ?>
                  	</div>
				<?php 
			} else if ($ppost_type == 'imagegrid') {
				$image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
				echo do_shortcode('[gallery ids="'.$image_gallery.'" columns="4"]');
			} else if ($ppost_type == 'carousel') { ?>
					          <?php if(isset($virtue_premium['slider_box_layout']) and $virtue_premium['slider_box_layout'] == 'sliderwide') { ?>
    <div id="imageslider" class="loading full-slider carousel_outerrim">
<?php } else { ?>
   <div id="imageslider" class="loading carousel_outerrim">
<?php } ?>
					    <div class="carousel_slider_outer fredcarousel fadein-carousel" style="overflow:hidden; max-width:<?php echo esc_attr($slidewidth);?>px; height: <?php echo esc_attr($slideheight);?>px; margin-left: auto; margin-right:auto;">
					        <div class="carousel_slider kad-light-gallery initcarouselslider" data-carousel-container=".carousel_slider_outer" data-carousel-transition="600" data-carousel-height="<?php echo esc_attr($slideheight); ?>" data-carousel-auto="<?php echo esc_attr($slideauto);?>" data-carousel-speed="9000" data-carousel-id="carouselslider">
					            <?php global $post;
                          		$image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          		if(!empty($image_gallery)) {
                    				$attachments = array_filter( explode( ',', $image_gallery ) );
                    					if ($attachments) {
										foreach ($attachments as $attachment) {
											         $attachment_url = wp_get_attachment_url($attachment , 'full');
											         $caption = get_post($attachment)->post_excerpt;
					                    	$image = aq_resize($attachment_url, null, $slideheight, false, false);
					                    	if(empty($image)) {
                                    $image = array();
                                    $image[0] = $attachment_url;
                                    $image[1] = $slidewidth;
                                    $image[2] = $slideheight;
                                  } 
					                        echo '<div class="carousel_gallery_item" style="float:left; display: table; position: relative; text-align: center; margin: 0; width:auto; height:'.esc_attr($image[2]).'px;">';
					                        echo '<div class="carousel_gallery_item_inner" style="vertical-align: middle; display: table-cell;">';
					                        echo '<a href="'.esc_url($attachment_url).'" data-rel="lightbox" title="'.esc_attr($caption).'">';
					                        echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'"  />';
					                        echo '</a>'; ?>
					                      </div>
					                    </div>
					                  <?php } ?>
					                  <?php } ?>
					                  <?php } ?>
					            </div>
					            <div class="clearfix"></div>
					              <a id="prevport-carouselslider" class="prev_carousel icon-arrow-left" href="#"></a>
					              <a id="nextport-carouselslider" class="next_carousel icon-arrow-right" href="#"></a>
					          </div> <!--fredcarousel-->
					  </div><!--Container-->
				<?php 
				} else if ($ppost_type == 'imagelist') { ?>
				<div class="kad-light-gallery">
					<?php global $post;
                          	$image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          		if(!empty($image_gallery)) {
                    				$attachments = array_filter( explode( ',', $image_gallery ) );
                    					if ($attachments) {
                    						$counter = 0;
											foreach ($attachments as $attachment) {
												$attachment_url = wp_get_attachment_url($attachment , 'full');
												$caption = get_post($attachment)->post_excerpt;
												$image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
													if(empty($image)) {$image = $attachment_url;}
												echo '<div class="portfolio_list_item pli'.$counter.'"><a href="'.$attachment_url.'" rel="lightbox" class="lightboxhover" title="'.$caption.'"><img src="'.$image.'" alt="'.$caption.'" /></a></div>';
												$counter ++;
											}
										}
                    			} else {
                    				$attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
									$attachments = get_posts($attach_args);
										if ($attachments) {
											$counter = 0;
											foreach ($attachments as $attachment) {
												$attachment_url = wp_get_attachment_url($attachment->ID , 'full');
												$image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
													if(empty($image)) {$image = $attachment_url;}
												echo '<div class="portfolio_list_item pli'.$counter.'"><a href="'.$attachment_url.'" rel="lightbox" class="lightboxhover"><img src="'.$image.'"/></a></div>';
												$counter ++;
											}
                    					}	
								} ?>  
							</div>  
				<?php } else if ($ppost_type == 'imagelist2') { ?>
				<div class="kad-light-gallery portfolio_image_list_style2">
					<?php global $post;
                          	$image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          		if(!empty($image_gallery)) {
                    				$attachments = array_filter( explode( ',', $image_gallery ) );
                    					if ($attachments) {
                    						$counter = 0;
											foreach ($attachments as $attachment) {
												$attachment_url = wp_get_attachment_url($attachment , 'full');
												$caption = get_post($attachment)->post_excerpt;
												$image = aq_resize($attachment_url, $slidewidth, true);
													if(empty($image)) {$image = $attachment_url;}
												echo '<div class="portfolio_list_item pli'.$counter.'"><a href="'.$attachment_url.'" rel="lightbox" class="lightboxhover" title="'.$caption.'"><img src="'.$image.'" alt="'.$caption.'" /></a></div>';
												$counter ++;
											}
										}
                    			} else {
                    				$attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
									$attachments = get_posts($attach_args);
										if ($attachments) {
											$counter = 0;
											foreach ($attachments as $attachment) {
												$attachment_url = wp_get_attachment_url($attachment->ID , 'full');
												$image = aq_resize($attachment_url, $slidewidth, true);
													if(empty($image)) {$image = $attachment_url;}
												echo '<div class="portfolio_list_item pli'.$counter.'"><a href="'.$attachment_url.'" rel="lightbox" class="lightboxhover"><img src="'.$image.'" /></a></div>';
												$counter ++;
											}
                    					}	
								} ?>  
							</div>  
				<?php 
				} else if ($ppost_type == 'imgcarousel') {
				}else if ($ppost_type == 'none') {
					$portfolio_margin = "kad_portfolio_nomargin";
				} else {					
						$post_id = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url( $post_id);
						$image = aq_resize( $img_url, $slidewidth, $slideheight, true ); //resize & crop the image
						if(empty($image)) {$image = $img_url;} 
							if($image) : ?>
                                    <div class="imghoverclass portfolio-single-img">
                                    	<a href="<?php echo $img_url ?>" rel="lightbox" class="lightboxhover">
                                    		<img src="<?php echo $image ?>" alt="<?php echo get_post($post_id)->post_excerpt; ?>"  />
                                    	</a>
                                    </div>
                            <?php endif; ?>
				<?php } ?>
        	</div><!--imgclass -->
  			<div class="<?php echo $textclass; ?>">
		    	<div class="entry-content <?php echo $entryclass; ?> <?php echo $portfolio_margin; ?>">
		      	<?php the_content(); ?>
		  		</div>
	    	<div class="<?php echo $valueclass; ?>">
	    			<div class="pcbelow">
				    <ul class="portfolio-content disc">
				    	<?php global $post; $project_v1t = get_post_meta( $post->ID, '_kad_project_val01_title', true );
				    						$project_v1d = get_post_meta( $post->ID, '_kad_project_val01_description', true );
				    						$project_v2t = get_post_meta( $post->ID, '_kad_project_val02_title', true );
				    						$project_v2d = get_post_meta( $post->ID, '_kad_project_val02_description', true );
				    						$project_v3t = get_post_meta( $post->ID, '_kad_project_val03_title', true );
				    						$project_v3d = get_post_meta( $post->ID, '_kad_project_val03_description', true );
				    						$project_v4t = get_post_meta( $post->ID, '_kad_project_val04_title', true );
				    						$project_v4d = get_post_meta( $post->ID, '_kad_project_val04_description', true );
				    						$project_v5t = get_post_meta( $post->ID, '_kad_project_val05_title', true );
				    						$project_v5d = get_post_meta( $post->ID, '_kad_project_val05_description', true ); ?>
				    <?php if (!empty($project_v1t)) echo '<li class="pdetails"><span>'.$project_v1t.'</span> '.$project_v1d.'</li>'; ?>
				    <?php if (!empty($project_v2t)) echo '<li class="pdetails"><span>'.$project_v2t.'</span> '.$project_v2d.'</li>'; ?>
				    <?php if (!empty($project_v3t)) echo '<li class="pdetails"><span>'.$project_v3t.'</span> '.$project_v3d.'</li>'; ?>
				    <?php if (!empty($project_v4t)) echo '<li class="pdetails"><span>'.$project_v4t.'</span> '.$project_v4d.'</li>'; ?>
				    <?php if (!empty($project_v5t)) echo '<li class="pdetails"><span>'.$project_v5t.'</span> <a href="'.$project_v5d.'" target="_new">'.$project_v5d.'</a></li>'; ?>
				    </ul><!--Portfolio-content-->
				</div>
				</div>
    	</div><!--textclass -->
    </div><!--row-->
    <div class="clearfix"></div>
    </div><!--postclass-->
    <footer>
      <?php wp_link_pages(array('before' => '<nav id="page-nav" class="wp-pagenavi"><p>' . __('Pages:', 'virtue'), 'after' => '</p></nav>')); ?>
      <?php global $post; $portfolio_carousel = get_post_meta( $post->ID, '_kad_portfolio_carousel_recent', true ); if ($portfolio_carousel != 'no') { get_template_part('templates/bottomportfolio', 'carousel'); } ?>
    </footer>
    <?php global $virute_premium; if(isset($virtue_premium['portfolio_comments']) && $virtue_premium['portfolio_comments'] == 1) { 
    comments_template('/templates/comments.php'); 
	} ?>
  </article>
<?php endwhile; ?>
<?php } else { ?>
    <div id="content">
     <div class="container">
    <div class="row">
      <div class="main <?php echo kadence_main_class(); ?> portfolio-single" role="main">
      <?php echo get_the_password_form();
    }?>
</div>