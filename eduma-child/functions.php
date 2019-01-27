<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 1001 );

// END ENQUEUE PARENT ACTION


/* 추가 - lkm 201803 */
// Staying Current Page after Login
function itdream_login_redirect( $redirect_to, $request, $user  ) {
	return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : get_permalink();
}
add_filter( 'login_redirect', 'itdream_login_redirect', 10, 3 );

// Staying Current Page after Logout
/* 실행 안됨. 보류
function itdream_logout_redirect($logouturl, $redir){
	return $logouturl . '&amp;redirect_to='.get_permalink();
}
add_filter('logout_url', 'itdream_logout_redirect', 1, 1);

add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
  wp_redirect( get_permalink() );
  exit();
}
*/

// 우커머스에 원화(KRW) 추가
add_filter( 'woocommerce_paypal_supported_currencies', 'add_currency_krw');
function add_currency_krw( $current_currencies ) {
    array_push( $current_currencies, 'KRW' );
    return $current_currencies;
}

// 우커머스 확인없이 로그아웃 하기
function wpbox_bypass_logout_confirmation() {
    global $wp;
    if ( isset( $wp->query_vars['customer-logout'] ) ) {
        wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
        exit;
    }
}
add_action( 'template_redirect', 'wpbox_bypass_logout_confirmation' );