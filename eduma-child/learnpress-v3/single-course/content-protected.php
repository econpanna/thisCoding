<?php
/**
 * Template for displaying message for course content protected.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/content-protected.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<div class="message message-error learn-press-message error">

    <?php
    if( $can_view_item && $can_view_item == 'not-enrolled' ){
        echo apply_filters( 'learn_press_content_item_protected_message',
            //__( 'This content is protected, please enroll course to view this content!', 'learnpress' ) );
			__( "이 강좌를 보려면 수강신청을 먼저 해주세요.", 'learnpress' ) );

        learn_press_course_enroll_button();
    } else{
        echo apply_filters( 'learn_press_content_item_protected_message',
            //sprintf( __( 'This content is protected, please <a href="%s">login</a> and enroll course to view this content!', 'learnpress' ), thim_get_login_page_url() ) );
            sprintf( __( '이 강좌를 보려면 <a href="%s">로그인</a> 후 수강신청을 먼저 해주세요.' , 'learnpress' ), thim_get_login_page_url() ) );
    }
    ?>

</div>
