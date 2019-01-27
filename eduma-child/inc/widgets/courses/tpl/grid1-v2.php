<?php
global $post;

$limit             = $instance['limit'];
$columns           = $instance['grid-options']['columns'];
$view_all_course   = ( $instance['view_all_courses'] && '' != $instance['view_all_courses'] ) ? $instance['view_all_courses'] : false;
$view_all_position = ( $instance['view_all_position'] && '' != $instance['view_all_position'] ) ? $instance['view_all_position'] : 'top';
$sort              = $instance['order'];
$feature        = !empty( $instance['featured'] ) ? true : false ;

$condition = array(
	'post_type'           => 'lp_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);

if ( $sort == 'category' && $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'course_category' ) ) {
		$condition['tax_query'] = array(
			array(
				'taxonomy' => 'course_category',
				'field'    => 'term_id',
				'terms'    => $instance['cat_id']
			),
		);
	}
}

if ( $sort == 'popular' ) {
	global $wpdb;
	$query = $wpdb->prepare( "
	  SELECT ID, a+IF(b IS NULL, 0, b) AS students FROM(
		SELECT p.ID as ID, IF(pm.meta_value, pm.meta_value, 0) as a, (
	SELECT COUNT(*)
  FROM (SELECT COUNT(item_id), item_id, user_id FROM {$wpdb->prefix}learnpress_user_items GROUP BY item_id, user_id) AS Y
  GROUP BY item_id
  HAVING item_id = p.ID
) AS b
FROM {$wpdb->posts} p
LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id  AND pm.meta_key = %s
WHERE p.post_type = %s AND p.post_status = %s
GROUP BY ID
) AS Z
ORDER BY students DESC
	  LIMIT 0, $limit
 ", '_lp_students', 'lp_course', 'publish' );

	$post_in = $wpdb->get_col( $query );

	$condition['post__in'] = $post_in;
	$condition['orderby']  = 'post__in';
}

if( $feature ) {
	$condition['meta_query'] = array(
		array(
			'key' => '_lp_featured',
			'value' =>  'yes',
		)
	);
}

$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	echo '<div class="grid-1">';
	if ( $view_all_course && 'top' == $view_all_position ) {
		echo '<a class="view-all-courses position-top" href="' . get_post_type_archive_link( 'lp_course' ) . '">' . esc_attr( $view_all_course ) . ' <i class="lnr icon-arrow-right"></i></a>';
	}
	?>
	<div class="thim-course-grid">
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php _learn_press_count_users_enrolled_courses( array( $post->ID ) ); ?>
			<div class="lpr_course <?php echo 'course-grid-' . $columns; ?>">
				<div class="course-item">
					<?php
					echo '<div class="course-thumbnail">';
						echo '<a class="" href="' . esc_url( get_the_permalink() ) . '" >';
							echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_course_thumbnail_width', 320 ), apply_filters( 'thim_course_thumbnail_height', 220 ), get_the_title() );
						echo '</a>';
						//echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>';
					echo '</div>';
					?>
					<div class="thim-course-content">
						<div class="author">
							<a href="<?php echo esc_url( learn_press_user_profile_link( $post->post_author ) ); ?>">
								<?php
								$user_data   = get_userdata( $post->post_author );
								$author_name = '';
								if ( $user_data ) {
									if( !empty( $user_data->display_name ) ) {
										$author_name = $user_data->display_name;
									}else{
										$author_name = $user_data->user_login;
									}
								}
								echo $author_name;
								?>
							</a>
						</div>
						<h2 class="course-title">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
						</h2>

						<div class="course-meta">
							<?php learn_press_courses_loop_item_price(); ?>
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
						</div>
					</div>
				</div>
			</div>
			<?php
		endwhile;
		?>
	</div>
	<?php
	if ( $view_all_course && 'bottom' == $view_all_position ) {
		echo '<a class="view-all-courses position-bottom" href="' . get_post_type_archive_link( 'lp_course' ) . '">' . esc_attr( $view_all_course ) . ' <i class="lnr icon-arrow-right"></i></a>';
	}
	echo '</div>';
endif;

wp_reset_postdata();
