<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs'); ?>

<div class="container">
	<div id="content" class="clearfix">
		<div id="left-area">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="entry post clearfix">
			<div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="box_count" data-action="like" data-show-faces="true" data-share="false"></div>
				<?php if (get_option('mycuisine_integration_single_top') <> '' && get_option('mycuisine_integrate_singletop_enable') == 'on') echo(get_option('mycuisine_integration_single_top')); ?>

				<?php if (get_option('mycuisine_thumbnails') == 'on') { ?>
					<?php
						$thumb = '';
						$width = 212;
						$height = 213;
						$classtext = 'post-thumb';
						$titletext = get_the_title();
						$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Entry');
						$thumb = $thumbnail["thumb"];
					?>

					<?php if($thumb <> '') { ?>
						<div class="post-thumbnail">
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
							<span class="post-overlay"></span>
						</div> 	<!-- end .post-thumbnail -->
					<?php } ?>
				<?php } ?>

				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p><strong>'.esc_html__('Pages','MyCuisine').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php edit_post_link(esc_html__('Edit this page','MyCuisine')); ?>
			</div> <!-- end .entry -->

			<?php if (get_option('mycuisine_integration_single_bottom') <> '' && get_option('mycuisine_integrate_singlebottom_enable') == 'on') echo(get_option('mycuisine_integration_single_bottom')); ?>

			<?php if (get_option('mycuisine_468_enable') == 'on') { ?>
					  <?php if(get_option('mycuisine_468_adsense') <> '') echo(get_option('mycuisine_468_adsense'));
					else { ?>
					   <a href="<?php echo esc_attr(get_option('mycuisine_468_url')); ?>"><img src="<?php echo esc_attr(get_option('mycuisine_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
			   <?php } ?>
			<?php } ?>

			<?php if (get_option('mycuisine_show_postcomments') == 'on') comments_template('', true); ?>
		<?php endwhile; endif; ?>
		</div> 	<!-- end #left-area -->

		<?php get_sidebar(); ?>
	</div> <!-- end #content -->
	<div id="bottom-shadow"></div>
</div> <!-- end .container -->

<?php get_footer(); ?>