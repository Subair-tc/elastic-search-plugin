<?php

function rare_curate_elastic_search( $post_searchname = '', $limits = 6 ){
	// Search full content for post type.
	
	$search_host = '10.132.22.123';
	$search_port = '9200';
	$index_name = 'onescdvoice-content';
	$numberof_posts_curate = 0; // for returning total number of posts (used in global search).
	
	if ( 'evidenceeducation' == $post_searchname ) {
		
		$type = 'evidenceeducation';
		$query = '
		
		{
			"query": {
				"bool": {
					"must": [{
						"match_all": {}
					}]
				}
			},
			"size": 50
		}
		
		';
		$responds = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $responds->hits->total;
		$results = $responds->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 0;
		$drg_class = '-evidence';
		$section_title = 'evidence & education';
		
		$msg =   get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title,  $results, $limits );
		
	} elseif ( 'newsmeetings' == $post_searchname ) {
		$type = 'news-meeting';
		$query = '
		
		{
			"query": {
				"bool": {
					"must": [{
						"match_all": {}
					}]
				}
			},
			"size": 50
		}
		
		';
		$responds = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $responds->hits->total;
		$results = $responds->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 0;
		$drg_class = 'news-meetings-tab';
		$section_title = 'news & meetings';
		
		$msg =   get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title,  $results, $limits );
	
	} else {
		
		$msg = '';
		
		//evidenceeducation
		
		$type = 'evidance-education';
		$query = '
		
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "source_evidence",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "organization_evidence",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "author_evidence",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "doi",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "abstract_source",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "language",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "full_text_source",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "source_pmc",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';

		//echo '<pre>';var_dump($query);echo '</pre>';
		
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 0;
		$drg_class = '-evidence';
		$section_title = 'evidence & education';
		
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title,  $results, $limits );
		
		
		
		// news-meeting
		
		$type = 'news-meeting';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "news_information_sourse",
								"query": "'.$post_searchname.'"
							}
						}
					]
				}
			},
			"size": 50
		}';
		
		//echo '<pre>';var_dump($query);echo '</pre>';
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 1;
		$drg_class = 'news-meetings-tab';
		$section_title = 'news & meetings';
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class,$section_title,  $results, $limits );
		
		
		//people
		
		$type = 'people';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_first_name",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_middle_name",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_last_name",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_qualification",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_specialities",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_primary_role",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_street_address",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_city",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_state",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_countrypeople",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_phone",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_affiliationinfo",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_image",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_affiliationinfo",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_afflitaion_type",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_type_of_expert",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_online_profile",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_additional_links",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "person_information_source",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';
		
		//echo '<pre>';var_dump($query);echo '</pre>';
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 2;
		$drg_class = 'people';
		$section_title = 'people';
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class,$section_title,  $results, $limits );
		
		
		// place
		
		
		$type = 'place';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_address",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_image",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_email",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_phone",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_phone_additional",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_official_website",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_city",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_state",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_country",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_url_1",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_url_2",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_url_3",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_orphanet_url",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_country_state_city",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_further_details_1",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_further_details_2",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_further_details_3",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "place_information_source",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';
		
		//echo '<pre>';var_dump($query);echo '</pre>';
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 3;
		$drg_class = 'place';
		$section_title = 'places';
		
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title, $results, $limits );
		
		
		
		// video-visual
		
		$type = 'video-visual';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "video_date",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "video_description",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "video_information_sourse",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';
		
		//echo '<pre>';var_dump($query);echo '</pre>';
		
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 4;
		$drg_class = 'rare-videos';
		$section_title = 'videos & visuals';
		
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title, $results, $limits );
		
		
		
			// photo
		
		$type = 'photo';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "photo_url",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "photo_information_sourse",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';
		
		//echo '<pre>';var_dump($query);echo '</pre>';
		
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 5;
		$drg_class = 'rc_socia_entry';
		$section_title = 'photo';
		
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title, $results, $limits );
		
		
		//website 
		
		$type = 'website';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "website_url",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "website_information_sourse",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';
		
		//echo '<pre>';var_dump($query);echo '</pre>';
		
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 6;
		$drg_class = 'rc_socia_entry';
		$section_title = 'websites';
		
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title, $results, $limits );
		
		// fundraising
		
		
		$type = 'fundraising';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "fundraise_url",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "fundraise_information_sourse",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';
		
		//echo '<pre>';var_dump($query);echo '</pre>';
		
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 7;
		$drg_class = 'rc_socia_entry';
		$section_title = 'fundraising';
		
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title, $results, $limits );
		
		// Social and media.
		
		$type = 'media-social';
		
		$query = '
		{
			"query": {
				"bool": {
					"should": [
						{
							"query_string": {
								"default_field": "title",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "social_url",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "social_company",
								"query": "'.$post_searchname.'"
							}
						},
						{
							"query_string": {
								"default_field": "social_information_sourse",
								"query": "'.$post_searchname.'"
							}
						}
						
					]
				}
			},
			"size": 50
		}';
	//echo '<pre>';var_dump($query);echo '</pre>';
		
		$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
		$total_resulsts = $results->hits->total;
		$results = $results->hits->hits;
		$numberof_posts_curate += $total_resulsts;
		$i = 8;
		$drg_class = 'rc_socia_entry';
		$section_title = 'social';
		
		$msg .= get_curate_search_message( $i, $total_resulsts , $drg_class, $section_title, $results, $limits );
	}
	
	
	
	$return['posts'] = $msg;
	$return['total_results'] = $numberof_posts_curate;
	return $return;
}


function get_curate_search_message( $i, $total_resulsts , $drg_class,$section_title,  $results, $limits =6  ) {
	
	$output = '';
		//$i = 0;
		$displayed_count = 0;
		if ( $total_resulsts ) {
			//$drg_class = '-evidence';
			$output .= '
				<div class="panel panel-default">
					<div class = "" role="tab" id="headingOne' . $i . '">';
			$output .= '
				<h4 class="panel-title evidence-education-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne' . $i . '" aria-expanded="true" aria-controls="collapseOne' . $i . '"  class="collapsed grey_bg">
						<span class="icon-evidence-and-education" style="color:#fff;"></span>
						'.$section_title.' &nbsp; (' . $total_resulsts . ') 
						<div class="cl_indicator">+ show</div>
					</a>
				</h4>';
			
			$output .= '
				</div>';
			
			$output .= '
				<div id="collapseOne' . $i . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne' . $i . '">
				<div class="panel-body">';
			foreach ( $results as $result ) {
				
				$post_id = $result->_source->id;
				$post_title = $result->_source->title;
				$post_content =	$result->_source->content;
				$post_link = $result->_source->post_url;
				
				if ( $displayed_count == $limits ) {
					$output .= '<div class="evidence_content_view_all" style="display:none">';
					$view_all_flag = 1;
				}
				
				$output .= '
					<div class="evidence_info_content " >
						<div class="' . $drg_class . ' info_title collapsed" data-id="' . $post_id . '">
							<h2>' . $post_title . '</h2><br>
						</div>';
						$output .= '
						<div class="info_cont_hide">';
						
							if ( strlen( $post_content ) > 250 ) {
									$output .= '<p>' . substr( $post_content,0,250 ) . '...</p>';
							} else {
									$output .= '<p>' . $post_content . '</p>';
							}

							$output .= '
							<div class="trial_readmore">
								<a class="button_grey" href="' . $post_link . '">read more</a>
							</div>';

							$output .= '
						</div>
					</div>';
				$displayed_count ++;
			}
			
			if ( $view_all_flag ) {
				$output .= '</div>';
			}
			if ( $total_resulsts > $limits ) {
				//$remaining_post_count = $result_items_count - $post_limits;
				$output .= '<span class="curate-readmore-new" >view all ('.$total_resulsts.') </span>';
			}
			
			$output .= '</div></div></div>';
		}
		
		
		return $output;
}