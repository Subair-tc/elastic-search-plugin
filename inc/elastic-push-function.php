<?php
function onevoice_elastic_search_data_push( $search_host,$search_port,$index,$doc_type,$type ) {
	global $wpdb;
	// UGC contents
	if( $type == 'ugc' ) {
	
		$doc_type = 'UGC_contents';
		
		$table = $wpdb->prefix.'bp_activity';
		$results = $wpdb->get_results( "SELECT a.id,a.user_id,a.item_id,a.secondary_item_id,a.content,a.primary_link FROM $table a   WHERE ( a.type='activity_update' OR a.type='activity_comment' )" );
		$content_to_push = '';
		$i = 0;
		foreach ( $results as $result ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "UGC_contents", "_id" : '. $result->id .' } }
		';
			$content = strip_tags( stripslashes( mb_convert_encoding ( $result->content,"UTF-8", "auto" ) ) );
			
			$tags = $wpdb->get_results( $wpdb->prepare( "SELECT t1.tag_id,t1.tag_name FROM onevoice_activity_tags t1 INNER JOIN onevoice_activity_tags_details t2 ON t1.tag_id = t2.tag_id WHERE t2.activity_id = %d", $result->id ) );
			$activity_tags = array();
			$activity_tag_ids = array();
			foreach ( $tags as $tag ) {
				array_push($activity_tags,$tag->tag_name);
				array_push($activity_tag_ids,$tag->tag_id);
			}
			
			$json_arr = array(
					id       			=> $result->id,
					user_id       		=> $result->user_id,
					group_id      		=> $result->item_id,
					secondary_item_id  	=> $result->secondary_item_id,
					content          	=> str_replace("'", "", $content),
					primary_link        => $result->primary_link,
					activity_tag_ids	=> $activity_tag_ids,
					activity_tags       => $activity_tags
				);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}

		
	} elseif( $type == 'evidence' ) {
		
		$doc_type = 'evidance-education';
		
		$args = array (
			'post_type' 		=> 'evidance-education',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		//var_dump( $the_query->posts );
		
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "evidance-education", "_id" : "post_'. $posts->ID .'" } }
			';
			$content 			= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			$year 				= get_post_meta( $posts->ID, 'wpcf-year_evidence', true );
			$year				= explode( ',',$year );
			$sourse 			= get_post_meta( $posts->ID, 'wpcf-journal', true );
			$sourse				= explode( ',',$sourse );
			$organization 		= get_post_meta( $posts->ID, 'wpcf-organisation_evidence', true );
			$organization		= explode( ',',$organization );
			$authors			= get_post_meta( $posts->ID, 'wpcf-authors', true );
			$authors			= explode( ',',$authors );
			$doi				= get_post_meta( $posts->ID, 'wpcf-doi-curate', true );
			$doi				= explode( ',',$doi );
			$source_db			= get_post_meta( $posts->ID, 'wpcf-source-database', true );
			$source_db			= explode( ',',$source_db );
			$abstract_source	= get_post_meta( $posts->ID, 'wpcf-abstract_source', true );
			$abstract_source	= explode( ',',$abstract_source );
			$conference			= get_post_meta( $posts->ID, 'wpcf-conference', true );
			$conference			= explode( ',',$conference );
			$language			= get_post_meta( $posts->ID, 'wpcf-language', true );
			$language			= explode( ',',$language );
			$isbn				= get_post_meta( $posts->ID, 'wpcf-isbn', true );
			$isbn				= explode( ',',$isbn );
			$presentation_no	= get_post_meta( $posts->ID, 'wpcf-presentation-number', true );
			$presentation_no	= explode( ',',$isbn );
			$full_text_source	= get_post_meta( $posts->ID, 'wpcf-full-text-source', true );
			$full_text_source	= explode( ',',$full_text_source );
			$pubdate			= get_post_meta( $posts->ID, 'wpcf-evidence-pubdate', true );
			$pubdate			= explode( ',',$full_text_source );
			$full_source		= get_post_meta( $posts->ID, 'wpcf-full-text-source', true );
			$full_source		= explode( ',',$full_source );
			$source_pmc			= get_post_meta( $posts->ID, 'wpcf-full-text-source-pmc', true );
			$source_pmc			= explode( ',',$source_pmc );
			$squib				= get_post_meta( $posts->ID, 'wpcf-squib', true );
			$squib				= explode( ',',$squib );
			$user_relation		= get_post_meta( $posts->ID, 'wcf-user-relation', true );
			$post_relation		= get_post_meta( $posts->ID, 'cptr_related', true );
			
			//Roundup article
			$roundup_image					= get_post_meta( $posts->ID, 'wpcf-round-up-article-image', true );
			$roundup_author_image			= get_post_meta( $posts->ID, 'wpcf-author-image', true );
			$roundup_author_link			= get_post_meta( $posts->ID, 'wpcf-author-link', true );
			$roundup_author					= get_post_meta( $posts->ID, 'wpcf-author-info', true );
			$roundup_author					= explode( ',',$roundup_author );
			$roundup_author_organisation	= get_post_meta( $posts->ID, 'wpcf-author-organization', true );
			$roundup_author_organisation	= explode( ',',$roundup_author_organisation );
			$roundup_organisation_link		= get_post_meta( $posts->ID, 'wpcf-organization-link', true );
			$is_roundup_artcle				= get_post_meta( $posts->ID, 'wpcf-round-up-article-template', true );
			
			// Read the categories.
			$categories = wp_get_post_terms( $posts->ID, 'evidance-categories');
			$caregory_names = array();
			$caregory_slugs = array();
			foreach( $categories as $category ) {
				array_push( $caregory_names,$category->name );
				array_push( $caregory_slugs,$category->slug );
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id       					=> $posts->ID,
				title       				=> str_replace("'", "", $posts->post_title ),
				post_date       			=> $posts->post_date,
				content      				=> str_replace("'", "", $content),
				post_url      				=> get_the_permalink($posts->ID),
				evidance_category_name 		=> $caregory_names,
				evidance_category_slug 		=> $caregory_slugs,
				rarerelated_tag_name 		=> $rarerelated_tag_names,
				rarerelated_tag_slug 		=> $rarerelated_tag_slugs,
				year_evidence				=> $year,
				source_evidence				=> str_replace("'", "", $sourse),
				organization_evidence		=> str_replace("'", "", $organization),
				author_evidence				=> str_replace("'", "", $authors),
				doi							=> $doi,
				source_db					=> $source_db,
				abstract_source				=> $abstract_source,
				conference					=> $conference,
				language					=> $language,
				isbn						=> $isbn,
				presentation_no				=> $presentation_no,
				full_text_source			=> $full_text_source,
				pubdate						=> $pubdate,
				source_pmc					=> $source_pmc,
				full_source					=> $full_source,
				squib						=> $squib,
				user_relation				=> $user_relation,
				post_relation				=> $post_relation,
				roundup_image				=> $roundup_image,
				roundup_author_image		=> $roundup_author_image,
				roundup_author_link			=> $roundup_author_link,
				roundup_author				=> $roundup_author,
				roundup_author_organisation	=> $roundup_author_organisation,
				roundup_organisation_link	=> $roundup_organisation_link,
				is_roundup_artcle			=> $is_roundup_artcle,
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
		
		
		
	} elseif( $type == 'fundraising' ) {
		$doc_type = 'fundraising';
		
		$args = array (
			'post_type' 		=> 'fundraising',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "fundraising", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$fundraise_url				 	=	get_post_meta( $posts->ID, 'wpcf-fundraising-url', true );
			$fundraise_information_sourse	=	get_post_meta( $posts->ID, 'wpcf-fundraising-information-source', true );
			
			$categories = wp_get_post_terms( $posts->ID, 'fundraising-category');
			$caregory_names = array();
			$caregory_slugs = array();
			$caregory_parnets_name = array();
			$caregory_parnets_slug = array();
			foreach( $categories as $category ) {
				if( $category->parent ) {
					array_push( $caregory_names,$category->name );
					array_push( $caregory_slugs,$category->slug );
				} else {
					array_push( $caregory_parnets_name,$category->name );
					array_push( $caregory_parnets_slug,$category->slug );
				}
				
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id       							=> $posts->ID,
				title       						=> str_replace("'", "", $posts->post_title ),
				post_date       					=> $posts->post_date,
				content      						=> str_replace("'", "", $content),
				post_url      						=> get_the_permalink($posts->ID),
				fundraising_category_name 			=> $caregory_names,
				fundraising_category_slug 			=> $caregory_slugs,
				fundraising_parent_category_name	=> $caregory_parnets_name,
				fundraising_parent_category_slug	=> $caregory_parnets_slug,
				rarerelated_tag_name 				=> $rarerelated_tag_names,
				rarerelated_tag_slug 				=> $rarerelated_tag_slugs,
				fundraise_url						=> str_replace("'", "", $fundraise_url),
				fundraise_information_sourse  		=> str_replace("'", "", $fundraise_information_sourse),
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	} elseif( $type == 'media-social' ) {
		$doc_type = 'media-social';
		
		$args = array (
			'post_type' 		=> 'media-social',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "media-social", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$social_url 			= get_post_meta( $posts->ID, 'wpcf-social-url', true );
			$social_company 		= get_post_meta( $posts->ID, 'wpcf-company', true );
			$social_information_sourse 	=  get_post_meta( $posts->ID, 'wpcf-social-information-source', true );
			
			$categories = wp_get_post_terms( $posts->ID, 'social-category');
			$caregory_names = array();
			$caregory_slugs = array();
			$caregory_parnets_name = array();
			$caregory_parnets_slug = array();
			foreach( $categories as $category ) {
				if( $category->parent ) {
					array_push( $caregory_names,$category->name );
					array_push( $caregory_slugs,$category->slug );
				} else {
					array_push( $caregory_parnets_name,$category->name );
					array_push( $caregory_parnets_slug,$category->slug );
				}
				
			}
			
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			$json_arr = array(
				id       						=> $posts->ID,
				title       					=> str_replace("'", "", $posts->post_title ),
				post_date       				=> $posts->post_date,
				content      					=> str_replace("'", "", $content),
				post_url      					=> get_the_permalink($posts->ID),
				social_category_name	 		=> $caregory_names,
				social_category_slug 			=> $caregory_slugs,
				social_category_parent_name		=> $caregory_parnets_name,
				social_category_parent_slug		=> $caregory_parnets_slug,
				rarerelated_tag_name	 		=> $rarerelated_tag_names,
				rarerelated_tag_slug 			=> $rarerelated_tag_slugs,
				social_url						=> $social_url,
				social_company					=> str_replace("'", "", $social_company),
				social_information_sourse  		=> str_replace("'", "", $social_information_sourse),
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	} elseif( $type == 'news-meeting' ) {
		$doc_type = 'news-meeting';
		
		$args = array (
			'post_type' 		=> 'news-meeting',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "news-meeting", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$news_image 				=	get_post_meta( $posts->ID, 'wpcf-news-image', true );
			$news_url 					=	get_post_meta( $posts->ID, 'wpcf-news-url', true );
			$news_date 					=	get_post_meta( $posts->ID, 'wpcf-newsdate', true );
			$news_information_sourse 	=	get_post_meta( $posts->ID, 'wpcf-news-information-source', true );
			
			$categories = wp_get_post_terms( $posts->ID, 'news-meetings-cat');
			$caregory_names = array();
			$caregory_slugs = array();
			foreach( $categories as $category ) {
				array_push( $caregory_names,$category->name );
				array_push( $caregory_slugs,$category->slug );
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id       						=> $posts->ID,
				title       					=> str_replace("'", "", $posts->post_title ),
				post_date       				=> $posts->post_date,
				content      					=> str_replace("'", "", $content),
				post_url      					=> get_the_permalink($posts->ID),
				news_place_type_name	 		=> $caregory_names,
				news_place_type_slug 			=> $caregory_slugs,
				rarerelated_tag_name	 		=> $rarerelated_tag_names,
				rarerelated_tag_slug 			=> $rarerelated_tag_slugs,
				news_image						=> $news_image,
				news_url						=> $news_url,
				news_date						=> $news_date,
				news_information_sourse  		=> str_replace("'", "", $news_information_sourse ),
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	}  elseif( $type == 'people' ) {
		$doc_type = 'people';
		
		$args = array (
			'post_type' 		=> 'people',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "people", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$categories = wp_get_post_terms( $posts->ID, 'persona-type');
			$caregory_names = array();
			$caregory_slugs = array();
			foreach( $categories as $category ) {
				array_push( $caregory_names,$category->name );
				array_push( $caregory_slugs,$category->slug );
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id       					=> $posts->ID,
				title       				=> str_replace("'", "", $posts->post_title ),
				post_date       			=> $posts->post_date,
				content						=> str_replace("'", "", $content),
				post_url      				=> get_the_permalink($posts->ID),
				person_type_name			=> $caregory_names,
				person_type_slug			=> $caregory_slugs,
				rarerelated_tag_name		=> $rarerelated_tag_names,
				rarerelated_tag_slug		=> $rarerelated_tag_slugs,
				person_title				=> get_post_meta( $posts->ID, 'wpcf-title', true ),
				person_first_name			=> get_post_meta( $posts->ID, 'wpcf-first-name', true ),
				person_middle_name			=> get_post_meta( $posts->ID, 'wpcf-middle-name', true ),
				person_last_name			=> get_post_meta( $posts->ID, 'wpcf-last-name', true ),
				person_qualification		=> get_post_meta( $posts->ID, 'wpcf-qualification', true ),
				person_specialities			=> get_post_meta( $posts->ID, 'wpcf-specialities', true ),
				person_primary_role			=> get_post_meta( $posts->ID, 'wpcf-primary-role', true ),
				person_street_address		=> str_replace("'", "",get_post_meta( $posts->ID, 'wpcf-address', true ) ),
				person_city					=> get_post_meta( $posts->ID, 'wpcf-city', true ),
				person_state				=> get_post_meta( $posts->ID, 'wpcf-state', true ),
				person_countrypeople		=> get_post_meta( $posts->ID, 'wpcf-countrypeople', true ),
				person_phone				=> get_post_meta( $posts->ID, 'wpcf-phone', true ),
				person_image				=> get_post_meta( $posts->ID, 'wpcf-image', true ),
				person_affiliationinfo		=> str_replace("'", "",get_post_meta( $posts->ID, 'wpcf-affiliationinfo', true ) ),
				person_afflitaion_type		=> get_post_meta( $posts->ID, 'wpcf-afflitaion_type', true ),
				person_type_of_expert		=> get_post_meta( $posts->ID, 'wpcf-type-of-expert', true ),
				person_online_profile		=> get_post_meta( $posts->ID, 'wpcf-online_profile', true ),
				person_additional_links		=> get_post_meta( $posts->ID, 'wpcf-additional-links', true ),
				person_information_source	=> str_replace("'", "",get_post_meta( $posts->ID, 'wpcf-people-information-source', true ) )
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	} elseif( $type == 'place' ) {
		$doc_type = 'place';
		
		$args = array (
			'post_type' 		=> 'place',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "place", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$categories = wp_get_post_terms( $posts->ID, 'places-type');
			$caregory_names = array();
			$caregory_slugs = array();
			foreach( $categories as $category ) {
				array_push( $caregory_names,$category->name );
				array_push( $caregory_slugs,$category->slug );
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id							=> $posts->ID,
				title						=> str_replace("'", "", $posts->post_title ),
				post_date					=> $posts->post_date,
				content						=> str_replace("'", "", $content),
				post_url      				=> get_the_permalink($posts->ID),
				place_type_name				=> $caregory_names,
				place_type_slug				=> $caregory_slugs,
				rarerelated_tag_name		=> $rarerelated_tag_names,
				rarerelated_tag_slug		=> $rarerelated_tag_slugs,
				place_address				=> str_replace("'", "",  get_post_meta( $posts->ID, 'wpcf-organization-address', true ) ),
				place_image					=> get_post_meta( $posts->ID, 'wpcf-organization-image', true ),
				place_email					=> get_post_meta( $posts->ID, 'wpcf-organization-email', true ),
				place_phone					=> get_post_meta( $posts->ID, 'wpcf-organization-phone', true ),
				place_phone_additional		=> get_post_meta( $posts->ID, 'wpcf-phone-additional', true ),
				place_official_website		=> get_post_meta( $posts->ID, 'wpcf-official-website', true ),
				place_city					=> get_post_meta( $posts->ID, 'wpcf-places-city', true ),
				place_state					=> get_post_meta( $posts->ID, 'wpcf-places-state', true ),
				place_country				=> get_post_meta( $posts->ID, 'wpcf-places-country', true ),
				place_url_1					=> get_post_meta( $posts->ID, 'wpcf-url-i', true ),
				place_url_2					=> get_post_meta( $posts->ID, 'wpcf-url-ii', true ),
				place_url_3					=> get_post_meta( $posts->ID, 'wpcf-url-iii', true ),
				place_orphanet_url			=> get_post_meta( $posts->ID, 'wpcf-orphanet-url', true ),
				place_country_state_city	=> get_post_meta( $posts->ID, 'wpcf-country-state-city', true ),
				place_further_details_1		=> str_replace("'", "",  get_post_meta( $posts->ID, 'wpcf-further-details-i', true ) ),
				place_further_details_2		=> str_replace("'", "",  get_post_meta( $posts->ID, 'wpcf-further-details-ii', true ) ),
				place_further_details_3		=> str_replace("'", "",  get_post_meta( $posts->ID, 'wpcf-further-details-iii', true ) ),
				place_information_source	=> str_replace("'", "",  get_post_meta( $posts->ID, 'wpcf-places-information-source', true ) ),
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	} elseif( $type == 'photo' ) {
		$doc_type = 'photo';
		
		$args = array (
			'post_type' 		=> 'photo',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "photo", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$categories = wp_get_post_terms( $posts->ID, 'photo-categories');
			$caregory_names = array();
			$caregory_slugs = array();
			$caregory_parnets_name = array();
			$caregory_parnets_slug = array();
			foreach( $categories as $category ) {
				if( $category->parent ) {
					array_push( $caregory_names,$category->name );
					array_push( $caregory_slugs,$category->slug );
				} else {
					array_push( $caregory_parnets_name,$category->name );
					array_push( $caregory_parnets_slug,$category->slug );
				}
				
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id							=> $posts->ID,
				title						=> str_replace("'", "", $posts->post_title ),
				post_date					=> $posts->post_date,
				content						=> str_replace("'", "", $content),
				post_url      				=> get_the_permalink($posts->ID),
				photo_category_name			=> $caregory_names,
				photo_category_slug			=> $caregory_slugs,
				photo_parent_category_name	=> $caregory_parnets_name,
				photo_parent_category_slug	=> $caregory_parnets_slug,
				rarerelated_tag_name		=> $rarerelated_tag_names,
				rarerelated_tag_slug		=> $rarerelated_tag_slugs,
				photo_url					=> get_post_meta( $posts->ID, 'wpcf-photo-url', true ),
				photo_information_sourse 	=> str_replace("'", "", get_post_meta( $posts->ID, 'wpcf-photo-information-source', true ) ),
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	} elseif( $type == 'website' ) {
		$doc_type = 'website';
		
		$args = array (
			'post_type' 		=> 'website',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "website", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$categories = wp_get_post_terms( $posts->ID, 'website-category');
			$caregory_names = array();
			$caregory_slugs = array();
			$caregory_parnets_name = array();
			$caregory_parnets_slug = array();
			foreach( $categories as $category ) {
				if( $category->parent ) {
					array_push( $caregory_names,$category->name );
					array_push( $caregory_slugs,$category->slug );
				} else {
					array_push( $caregory_parnets_name,$category->name );
					array_push( $caregory_parnets_slug,$category->slug );
				}
				
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			$json_arr = array(
				id								=> $posts->ID,
				title							=> str_replace("'", "", $posts->post_title ),
				post_date						=> $posts->post_date,
				content							=> str_replace("'", "", $content),
				post_url      					=> get_the_permalink($posts->ID),
				website_category_name			=> $caregory_names,
				website_category_slug			=> $caregory_slugs,
				website_parent_category_name	=> $caregory_parnets_name,
				website_parent_category_slug	=> $caregory_parnets_slug,
				rarerelated_tag_name			=> $rarerelated_tag_names,
				rarerelated_tag_slug			=> $rarerelated_tag_slugs,
				website_url						=> get_post_meta( $posts->ID, 'wpcf-website-url', true ),
				website_information_sourse 		=> str_replace("'", "", get_post_meta( $posts->ID, 'wpcf-websites-information-source', true ) ),
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	}  elseif( $type == 'video-visual' ) {
		$doc_type = 'video-visual';
		
		$args = array (
			'post_type' 		=> 'video-visual',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "video-visual", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$categories = wp_get_post_terms( $posts->ID, 'video-category');
			$caregory_names = array();
			$caregory_slugs = array();
			$caregory_ids 	= array();
			foreach( $categories as $category ) {
				array_push( $caregory_names,$category->name );
				array_push( $caregory_slugs,$category->slug );
				array_push( $caregory_ids,$category->term_id );
			}
			$categories = wp_get_post_terms( $posts->ID, 'video-subject');
			$subject_names ='';
			$subject_slugs ='';
			foreach( $categories as $category ) {
				$subject_names .= $category->name.',';
				$subject_slugs .= $category->slug.',';
			}
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id							=> $posts->ID,
				title						=> str_replace("'", "", $posts->post_title ),
				post_date					=> $posts->post_date,
				content						=> str_replace("'", "", $content),
				post_url      				=> get_the_permalink($posts->ID),
				video_category_name			=> $caregory_names,
				video_category_slug			=> $caregory_slugs,
				video_category_ids			=> $caregory_ids,
				video_subject_names			=> $subject_names,
				video_subject_slug			=> $subject_slugs,
				rarerelated_tag_names		=> $rarerelated_tag_names,
				rarerelated_tag_slug		=> $rarerelated_tag_slugs,
				video_date					=> get_post_meta( $posts->ID, 'wpcf-date', true ),
				content_format				=> get_post_meta( $posts->ID, 'wpcf-content-format', true ),
				thumbnail_image				=> get_post_meta( $posts->ID, 'wpcf-thumbnail-image', true ),
				content_subject				=> get_post_meta( $posts->ID, 'wpcf-content-subject', true ),
				content_subject				=> get_post_meta( $posts->ID, 'wpcf-type-for-curation', true ),
				type_for_curation		 	=> str_replace("'", "", get_post_meta( $posts->ID, 'wpcf-video_description', true ) ),
				video_information_sourse 	=> str_replace("'", "", get_post_meta( $posts->ID, 'wpcf-information_source', true ) ),
				
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
		
	} elseif ( $type == 'rarehub' ) {
		$doc_type = 'rarehub';
		
		$args = array (
			'post_type' 		=> 'rarehub',
			'posts_per_page' 	=> '-1'
		);
		$the_query = new WP_Query( $args );
		foreach( $the_query->posts as $posts ) {
			$content_to_push .= '{ "index" : { "_index" : "'.$index.'", "_type" : "rarehub", "_id" : "post_'. $posts->ID .'" } }
			';
			
			$content 		= strip_tags( stripslashes( mb_convert_encoding (  $posts->post_content,"UTF-8", "auto" ) ) );
			
			$categories = wp_get_post_terms( $posts->ID, 'clinical-category');
			$caregory_names = array();
			$caregory_slugs = array();
			$caregory_ids 	= array();
			foreach( $categories as $category ) {
				array_push( $caregory_names,$category->name );
				array_push( $caregory_slugs,$category->slug );
				array_push( $caregory_ids,$category->term_id );
			}
			
			
			// Read the rarerelated tags.
			$rarerelated_tags= wp_get_post_terms( $posts->ID, 'rarerelated-tag');
			$rarerelated_tag_names = array();
			$rarerelated_tag_slugs = array();
			foreach( $rarerelated_tags as $rarerelated_tag ) {
				array_push( $rarerelated_tag_names,$rarerelated_tag->name );
				array_push( $rarerelated_tag_slugs,$rarerelated_tag->slug );
			}
			
			$json_arr = array(
				id							=> $posts->ID,
				title						=> str_replace("'", "", $posts->post_title ),
				post_date					=> $posts->post_date,
				content						=> str_replace("'", "", $content),
				post_url      				=> get_the_permalink($posts->ID),
				clinical_category_name		=> $caregory_names,
				clinical_category_slug		=> $caregory_slugs,
				clinical_category_ids		=> $caregory_ids,
				rarerelated_tag_names		=> $rarerelated_tag_names,
				rarerelated_tag_slug		=> $rarerelated_tag_slugs,
				condition					=> get_post_meta( $posts->ID, 'wpcf-condition', true ),
				status						=> get_post_meta( $posts->ID, 'wpcf-status', true ),
				intervention				=> get_post_meta( $posts->ID, 'wpcf-intervention', true ),
				study_id					=> get_post_meta( $posts->ID, 'wpcf-study-id', true ),
				phase_of_development		=> get_post_meta( $posts->ID, 'wpcf-phase-of-development', true ),
				study_type					=> get_post_meta( $posts->ID, 'wpcf-study-type', true ),
				size_enrollment				=> get_post_meta( $posts->ID, 'wpcf-size-enrollment', true ),
				study_design				=> get_post_meta( $posts->ID, 'wpcf-study-design', true ),
				study_description			=> get_post_meta( $posts->ID, 'wpcf-study-description', true ),
				primary_outcomes			=> get_post_meta( $posts->ID, 'wpcf-primary-outcomes', true ),
				secondary_outcomes			=> get_post_meta( $posts->ID, 'wpcf-secondary-outcomes', true ),
				inclusion_criteria			=> get_post_meta( $posts->ID, 'wpcf-inclusion-criteria', true ),
				exclusion_criteria			=> get_post_meta( $posts->ID, 'wpcf-exclusion-criteria', true ),
				sponsor						=> get_post_meta( $posts->ID, 'wpcf-sponsor', true ),
				investigators				=> get_post_meta( $posts->ID, 'wpcf-investigators', true ),
				sourcedatabase				=> get_post_meta( $posts->ID, 'wpcf-sourcedatabase', true ),
				view_full_trial_record		=> get_post_meta( $posts->ID, 'wpcf-view-full-trial-record', true ),
				results						=> get_post_meta( $posts->ID, 'wpcf-results', true ),
				otheroutcomes				=> get_post_meta( $posts->ID, 'wpcf-otheroutcomes', true ),
				study_website				=> get_post_meta( $posts->ID, 'wpcf-study-website', true ),
				study_details				=> get_post_meta( $posts->ID, 'wpcf-study-details', true ),
				study_start_date			=> get_post_meta( $posts->ID, 'wpcf-study-start-date', true ),
				study_end_date				=> get_post_meta( $posts->ID, 'wpcf-study-end-date', true ),
				study_source_link			=> get_post_meta( $posts->ID, 'study-source-link', true ),
				countries					=> get_post_meta( $posts->ID, 'wpcf-countries', true ),
				secondary_category			=> get_post_meta( $posts->ID, 'wpcf-secondary-category', true ),
				center_name					=> get_post_meta( $posts->ID, 'wpcf-center-name', true ),
				center_state				=> get_post_meta( $posts->ID, 'wpcf-center-state', true ),
				publications				=> get_post_meta( $posts->ID, 'wpcf-publications', true ),
				keyword						=> get_post_meta( $posts->ID, 'wpcf-keyword', true ),
				type_of_trail				=> get_post_meta( $posts->ID, 'wpcf-type-of-trail', true ),
				type_of_sponsor				=> get_post_meta( $posts->ID, 'wpcf-type-of-sponsor', true ),
				center_city					=> get_post_meta( $posts->ID, 'wpcf-center-city', true ),
				zip_code					=> get_post_meta( $posts->ID, 'wpcf-zip-code', true ),
				lat_and_long				=> get_post_meta( $posts->ID, 'wpcf-lat-and-long', true ),
				last_updated_date			=> get_post_meta( $posts->ID, 'wpcf-last-updated-date', true ),
				journal						=> get_post_meta( $posts->ID, 'wpcf-journal', true ) 
			);
			$json = json_encode($json_arr);
			if( json_last_error() ){
				echo json_last_error(); exit;
			}
			$content_to_push .= $json.'
		';
		}
	} else {
		echo 'Error 404';
		exit;
	}
	
	
	//pushing contents
	//echo '<pre>';
	//print_r($content_to_push); 
	//echo '</pre>';
	
	
	$baseUri = 'http://'.$search_host.':'.$search_port.'/'.$index.'/'.$doc_type.'/_bulk';
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_URL, $baseUri);
		curl_setopt($ci, CURLOPT_PORT, $search_port);
		curl_setopt($ci, CURLOPT_TIMEOUT, 20000);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_FORBID_REUSE, 0);
		curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ci, CURLOPT_POSTFIELDS, $content_to_push);
		$response = curl_exec($ci);
		echo '<pre>';var_dump( $response );echo '</pre>';
		if( curl_error( $ci ) ){
			echo curl_error($ci );
		}
		
		curl_close( $ci );
		exit;

}