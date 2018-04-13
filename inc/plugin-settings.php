<?php
function es_create_dianamic_pages( $the_page_title,$the_page_name  ){
	 delete_option("my_plugin_page_title");
    add_option("my_plugin_page_title", $the_page_title, '', 'yes');
    delete_option("my_plugin_page_name");
    add_option("my_plugin_page_name", $the_page_name, '', 'yes');
    delete_option("my_plugin_page_id");
    add_option("my_plugin_page_id", '0', '', 'yes');
    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {
        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
		// the default 'Uncatrgorised'
        $_p['post_category'] = array(1); 

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }
    delete_option( 'my_plugin_page_id' );
    add_option( 'my_plugin_page_id', $the_page_id );
	
}

function onevoice_elastic_search_plugin_template_redirection( $page_name,$template_name  ) {
    global $wp;
    $plugindir = dirname( __FILE__ );

   if ($wp->query_vars["pagename"] == $page_name ) {
        $templatefilename = $template_name;
        if (file_exists(TEMPLATEPATH . '/onevoice-elastic-search/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/onevoice-elastic-search/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/templates/' . $templatefilename;
        }
        do_theme_redirect_onevoice_elastic_search_plugin($return_template);
    }
	
}

function do_theme_redirect_onevoice_elastic_search_plugin($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include_once($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}