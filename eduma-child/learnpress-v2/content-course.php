<?php
/**
 * Template for displaying course content within the loop
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$theme_options_data = get_theme_mods();
$class = isset($theme_options_data['thim_learnpress_cate_grid_column']) && $theme_options_data['thim_learnpress_cate_grid_column'] ? 'course-grid-'.$theme_options_data['thim_learnpress_cate_grid_column'] : 'course-grid-3';
if ( !defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}
$class .= ' lpr_course';

?>
<div id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
	<?php do_action( 'learn_press_before_courses_loop_item' ); ?>
	<div class="course-item">
		<?php learn_press_course_thumbnail(); ?>
		<div class="thim-course-content">
			<?php
			learn_press_course_instructor();
			//do_action( 'learn_press_before_the_title' );
			the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			do_action( 'learn_press_after_the_title' );
			?>
			<div class="course-meta">
				<?php learn_press_course_instructor(); ?>
				<?php thim_course_ratings(); ?>
				<?php //learn_press_course_students(); ?> <!-- lkm 201803 주석처리 -->
				<!-- lkm 201803 start -->
				<div class="course-students">
					<label>화면해설</label>
					<div class="value"><!--<i class="fa fa-group"></i>-->
						<?php 
							$dvs_yn = do_shortcode('[acf field="dvs_yn"]'); 
							if($dvs_yn == 'YES'){
								echo '화면해설';
							} elseif($dvs_yn == 'MAYBE'){
								echo '화면이해가능';
							}
						?>
					</div>
				</div>
				<div class="course-students">
					<label>수화</label>
					<div class="value"><!--<i class="fa fa-group"></i>-->
						<?php 
							$signlang_yn = do_shortcode('[acf field="signlang_yn"]'); 
							if($signlang_yn == 'YES'){
								echo '수화';
							}
						?>
					</div>
				</div>
				<div class="course-students">
					<label>자막</label>
					<div class="value"><!--<i class="fa fa-group"></i>-->
						<?php 
							$subtitle_yn = do_shortcode('[acf field="subtitle_yn"]'); 
							if($subtitle_yn == 'YES'){
								echo '자막';
							}
						?>
					</div>
				</div>
				<!-- lkm 201803 end -->
				<?php thim_course_ratings_count(); ?>
				<?php learn_press_course_price(); ?>
			</div>
			<div class="course-description">
				<?php
				do_action( 'learn_press_before_course_content' );
				echo thim_excerpt(25);
				do_action( 'learn_press_after_course_content' );
				?>
			</div>
			<?php learn_press_course_price(); ?>
            <div class="course-meta list_courses">
                <?php learn_press_course_price(); ?>
                <?php thim_course_number_students(); ?>
                <?php thim_course_ratings_meta(); ?>
            </div>
			<div class="course-readmore">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
			</div>
		</div>
	</div>
	<?php do_action( 'learn_press_after_courses_loop_item' ); ?>
</div>
