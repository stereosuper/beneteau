<?php
/*
Template Name: Emploi
*/

get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<?php $sidebar_menu = wp_nav_menu( array(
		'echo' => false,
		'theme_location' => 'primary',
		'container' => false,
		'menu_class' => 'sidebar-menu',
		'menu_id' => 'submenu',
		'depth' => 0,
		'walker' => new CustomWalkerNavSubMenu()
		) );
	?>

	<div class='container<?php echo (strpos($sidebar_menu, '<li')!==FALSE)?' container-sidebar':''; ?>'>

		<?php if (strpos($sidebar_menu, '<li')!==FALSE) : ?>
			<aside class='sidebar wrapper-sticky' id='sidebar'>
				<div class='content-sidebar' id='blockSticky'>
					<span class='bg-sidebar'></span>
					<?php echo $sidebar_menu; ?>
				</div>
			</aside>
		<?php endif; ?>

		<div class='content'>
			<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

			<h1 class='isAnimated'><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <form id="keywordsearch" class='sf-form' name="keywordsearch" method="get" action="https://beneteau-group.jobs2web.com/search/" lang="en_US" xml:lang="en_US">
                <div class='sf-field'>
                    <label for='keywordsearch-q'><?php _e('Search by Keyword', 'beneteau'); ?></label>
                    <input id="keywordsearch-q" name="q" type="text" value="" title="<?php _e('Search by Keyword', 'beneteau'); ?>"/>
                </div>
                <div class='sf-field'>
                    <label for='keywordsearch-locationsearch'><?php _e('Search by Location', 'beneteau'); ?></label>                
                    <input id="keywordsearch-locationsearch" name="locationsearch" type="text" value="" title="<?php _e('Search by Location', 'beneteau'); ?>"/> <input class="keywordsearch-source" type="hidden" name="utm_source" value="CSSearchWidget" /> 
                </div>
                <input class="keywordsearch-startrow" type="hidden" name="startrow" value="1" /> 
                <button id="keywordsearch-button" type="submit"><?php _e('Search', 'beneteau'); ?></button> 
            </form>
            <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script> 
            <script src="https://beneteau-group.jobs2web.com/view/widgets/keywordsearchHandler.js"></script>


            <iframe src='https://rmk-map-2.valhalla2.stage.jobs2web.com/map/?esid=HI2MHAJYg9DEYIQhTPsdSw%3D%3D&locale=en_US&uselcl=false&watercolor=%23FFFFFF&jobdomain=beneteau.valhalla2.stage.jobs2web.com &maplbljob=Job&maplbljobs=jobs&mapbtnsearchjobs=Search+jobs&centerpoint=35.1,31.3&mapzoom=1&keyword=&regionCode=US&parentURL=http%3A%2F%2F beneteau.valhalla2.stage.jobs2web.com%2Fcontent%2FSearch-by-Location%2F%3Flocale%3Dfr_FR' class='sf-map' frameborder='0' style='width:100%;height:400px;'></iframe>

            <?php
                
                dynamic_sidebar( 'job' ); ?>
		</div>

	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
