<?php
/**
 * Plugin Name: WP CLI Flush Cache
 * Plugin URI:  https://github.com/ocean90/wp-cli-user-flush-cache
 * Description: Using wp cli, flush the cache of a user or a site.
 * Version:     1.0.0
 * Author:      Dominik Schilling
 * Author URI:  https://dominikschilling.de
 * License:     GPL v2.0+
 */

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Clears the cache of a user.
 *
 * ## OPTIONS
 *
 * <user>
 * : User ID, user email, or user login.
 */
function ds_wpcli_flush_cache_for_user( $args, $assoc_args ) {
	$fetcher = new WP_CLI\Fetchers\User();
	$user = $fetcher->get_check( $args[0] );

	wp_cache_delete( $user->user_email, 'useremail' );
	wp_cache_delete( $user->user_nicename, 'userslugs' );
	wp_cache_delete( $user->user_login, 'userlogins' );
	wp_cache_delete( $user->ID, 'users' );

	WP_CLI::success( "Cache flushed for user '$user->user_login'." );
}

WP_CLI::add_command( 'user flush-cache', 'ds_wpcli_flush_cache_for_user' );

/**
 * Clears the cache of a site.
 *
 * ## OPTIONS
 *
 * <id>
 * : ID of a site.
 */
function ds_wpcli_flush_cache_for_site( $args, $assoc_args ) {
	$fetcher = new \WP_CLI\Fetchers\Site();
	$site = $fetcher->get_check( $args[0] );

	clean_blog_cache( $site );

	WP_CLI::success( "Cache flushed for site '$site->domain$site->path'." );
}

WP_CLI::add_command( 'site flush-cache', 'ds_wpcli_flush_cache_for_site' );
