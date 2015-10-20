<?php ?>
	</div> <!-- end #content-area -->
	<div id="footer">
		<div class="container">
			<?php // if ( !is_home() ) { ?>
				<div id="footer-widgets" class="clearfix">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
					<?php endif; ?>
				</div> <!-- end #footer-widgets -->
			<?php // } ?>

			<div id="footer-bottom" class="clearfix<?php if ( !is_home() ) echo ' nobg'; ?>">
				<p id="copyright"><?php esc_html_e('Designed by ','MyCuisine'); ?> <a href="http://paellabycarlos.com" title="WordPress Designer">Paella by Carlos</a> | &copy; 2013 - <?php echo date("Y") ?></p>
			</div> 	<!-- end #footer-bottom -->
		</div> 	<!-- end .container -->
	</div> <!-- end #footer -->
<?php if (is_page( 'request-a-quote' )) { ?>
<script type="text/javascript">
jQuery(function($) {
$("#otherz").change(function(){
    if($(this).val() == "Other") {
       $('.oth-hide').addClass('oth-show');
    } else {
       $('.oth-hide').removeClass('oth-show');
    }
});
});
</script>
<?php } ?>
	<?php get_template_part('includes/scripts'); ?>
	<?php wp_footer(); ?>
</body>
</html>