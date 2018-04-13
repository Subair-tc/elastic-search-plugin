<?php
/*
Plugin Name: Onevoice Elastic Search
Version: 1.0
Description: Elastic Search for onevoice.
Author: Subair T C
Author URI:
Plugin URI:
Text Domain: onevoice-elastic-search
Domain Path: /languages
*/

/*
*	Function to Create new column on activity table.
*/

/* Set constant path to the plugin directory. */
define( 'ELASTIC_SEARCH_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );


define( 'ELASTIC_SEARCH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
/* Set the constant path to the plugin's includes directory. */
define( 'ELASTIC_SEARCH_INC', ELASTIC_SEARCH_PLUGIN_PATH . trailingslashit( 'inc' ), true );


/* including plugin settings pages*/
include_once( ELASTIC_SEARCH_INC . 'plugin-settings.php' );

/* including elastic push function*/
include_once( ELASTIC_SEARCH_INC . 'elastic-push-function.php' );

/*including create index fucntion */
include_once( ELASTIC_SEARCH_INC . 'elastic-create-index-function.php' );

/*including search fucntion */
include_once( ELASTIC_SEARCH_INC . 'elastic-search-function.php' );

/*including E & E Search fucntion */
include_once( ELASTIC_SEARCH_INC . 'evidence-education-search.php' );

/*including N & M Search fucntion */
include_once( ELASTIC_SEARCH_INC . 'news-mettings-search.php' );

/*including rare curate Search fucntion */
include_once( ELASTIC_SEARCH_INC . 'rarecurate-search.php' );

/*including rare curate Search fucntion */
include_once( ELASTIC_SEARCH_INC . 'rareclinical-search.php' );

/*including people Search fucntion */
include_once( ELASTIC_SEARCH_INC . 'people-search.php' );

/*including places Search fucntion */
include_once( ELASTIC_SEARCH_INC . 'places-search.php' );

/*including  Videos and visuals search */
include_once( ELASTIC_SEARCH_INC . 'videos-visuals.php' );

/*including rarehub search */
include_once( ELASTIC_SEARCH_INC . 'rarehub-search.php' );

/*including scial and media search */
include_once( ELASTIC_SEARCH_INC . 'social-media-search.php' );


/*including UGC content search */
include_once( ELASTIC_SEARCH_INC . 'ugc-contents-search.php' );


function onevoice_elastic_search_activate() {
	$the_page_title = 'Elastic Push';
    $the_page_name = 'onevoice-elastic-push';
	es_create_dianamic_pages( $the_page_title,$the_page_name  );

	// creating index creation page.
	$the_page_title2 = 'Elastic Create Index';
    $the_page_name2 = 'onevoice-elastic-create-index';
	es_create_dianamic_pages( $the_page_title,$the_page_name  );
}
register_activation_hook( __FILE__, 'onevoice_elastic_search_activate' );


// set my notification template
//Template fallback
add_action("template_redirect", 'template_redirect_onevoice_elastic_search_plugin');

function template_redirect_onevoice_elastic_search_plugin() {
	
	onevoice_elastic_search_plugin_template_redirection( 'onevoice-elastic-push','elastic-push.php' );
	onevoice_elastic_search_plugin_template_redirection( 'onevoice-elastic-create-index','create-index.php' );
}




/*
*	Function to Enqueue required scripts and Style.
*/
function add_onevoice_elastic_search_script() {
	wp_register_script( 'elastic-search', plugins_url( '/js/elastic-search.js', __FILE__ ), true );
	wp_enqueue_script( 'elastic-search' );
	wp_localize_script('elastic-search', 'Ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	));
	//wp_register_style( 'elastic-search', plugins_url( '/css/elastic-search.css', __FILE__ ) );
	//wp_enqueue_style( 'elastic-search' );
}

add_action( 'wp_enqueue_scripts', 'add_onevoice_elastic_search_script' );


/*
	Adding admin styles and scripts
*/
function add_onevoice_elastic_search_admin_style() {
	
	wp_register_style( 'site-noti-css', plugins_url( '/css/custom.css', __FILE__ ) );
	wp_enqueue_style( 'site-noti-css' );
	wp_register_style( 'sn_bootsrtrap', plugins_url( '/css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'sn_bootsrtrap' );
	wp_register_style( 'sn_dataTable', plugins_url( '/css/dataTables.bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'sn_dataTable' );
	
	wp_register_script( 'site-noti-js', plugins_url( '/js/custom.js', __FILE__ ), true );
	wp_enqueue_script( 'site-noti-js' );
	wp_register_script( 'sn_bootstrap', plugins_url( '/js/bootstrap.min.js', __FILE__ ), true );
	wp_enqueue_script( 'sn_bootstrap' );
	wp_register_script( 'sn_dataTable', plugins_url( '/js/jquery.dataTables.js', __FILE__ ), true );
	wp_enqueue_script( 'sn_dataTable' );
	wp_register_script( 'sn_dataTable_bootstrap', plugins_url( '/js/dataTables.bootstrap.min.js', __FILE__ ), true );
	wp_enqueue_script( 'sn_dataTable_bootstrap' );
	
	wp_localize_script('custom-js', 'Ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	));
}

//add_action( 'admin_enqueue_scripts', 'add_onevoice_elastic_search_admin_style' );

