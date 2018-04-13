<?php

function people_elastic_search( $post_loc_name ='',$post_typeofexpert_name='', $post_peop_city_val='', $post_peop_state_val='',$post_afflitaion_type_name='', $post_people_country_name='', $post_search_name='', $post_limits = 6  ) {
		
	$subquery = '';
	$flag =0 ;
	if( $post_search_name ) {
		$flag = 1;
		setcookie( 'session_ppsearchname', $post_search_name, time() + 180, '/' );
		
		$subquery .= '
			"should": [
				{
					"query_string": {
						"default_field": "title",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "content",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_title",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_first_name",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_middle_name",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_last_name",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_qualification",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_specialities",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_primary_role",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_street_address",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_city",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_state",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_countrypeople",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_phone",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_affiliationinfo",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_image",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_affiliationinfo",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_afflitaion_type",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_type_of_expert",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_online_profile",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_additional_links",
						"query": "'.$post_search_name.'"
					}
				},
				{
					"query_string": {
						"default_field": "person_information_source",
						"query": "'.$post_search_name.'"
					}
				}
				
			],
			"minimum_should_match" : 1';
	}
		
	if( $post_peop_city_val ) {
		
		
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		
		$loc_city_arr = array_filter( $post_peop_city_val );
		$count_loc_city = count( $loc_city_arr );
		$loc_city = implode( ' , ', $post_peop_city_val );
		setcookie( 'session_peo_city', $loc_city, time() + 180, '/' );
		
		$subquery .='
			"must": [{
				"match": {
					"person_city": {
						"query": ["'.$loc_city .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
	}
	
	if ( $post_peop_state_val ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		
		$loc_state = implode( ' , ', $post_peop_state_val );
		$loc_state_arr = array_filter( $post_peop_state_val );
		$count_loc_state = count( $loc_state_arr );
		setcookie( 'session_peo_state', $loc_state, time() + 180, '/' );
		
		$subquery .='
			"must": [{
				"match": {
					"person_state": {
						"query": ["'.$loc_state .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
		
	}
	
	if ( $post_typeofexpert_name ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		
		$typeofexpert = implode( ' , ', $post_typeofexpert_name );
		$typeofexpert_arr = array_filter( $post_typeofexpert_name );
		$count_typeofexpert_arr = count( $typeofexpert_arr );
		setcookie( 'session_typofexpert', $typeofexpert, time() + 180, '/' );
		
		$subquery .='
			"must": [{
				"match": {
					"person_type_of_expert": {
						"query": ["'.$loc_state .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
	}
	
	if ( $post_afflitaion_type_name ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		
		$afflitaion_type = implode( ' , ', $post_afflitaion_type_name );
		$afflitaion_type_arr = array_filter( $post_afflitaion_type_name );
		$count_afflitaion_type_arr = count( $afflitaion_type_arr );
		setcookie( 'session_affiliation', $afflitaion_type, time() + 180, '/' );
		
		$subquery .='
			"must": [{
				"match": {
					"person_afflitaion_type": {
						"query": ["'.$loc_state .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
	}
	
	if(  $post_people_country_name ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		$country = implode( ' , ', $post_people_country_name );
		$country_arr = array_filter( $post_people_country_name );
		$count_country = count( $country_arr );
		setcookie( 'session_peo_country', $country, time() + 180, '/' );
		$subquery .='
			"must": [{
				"match": {
					"person_countrypeople": {
						"query": ["'.$loc_state .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
	}
		
		
		
		
	if( ! $post_limits ) {
		$post_limits = 6;
	}

	setcookie( 'session_pplimits', $post_limits, time() + 180, '/' );
		
		
		$query = '
		{
			"query": {
				"filtered": {
					"query": {
						"bool": {'.$subquery.'
							
							
						}
					}
				}
			},
			"size": 0,
			"aggs": {
				"by_category": {
					"terms": {
						"field": "person_type_slug.slug",
						"size": 0
					},
					"aggs": {
						"tops": {
							"top_hits": {
								"size": 100
							}
						}
					}
				}
			}
		}

		';
	//echo '<pre>';var_dump($query);echo '</pre>'; 	
	//echo '<pre>';var_dump($subquery);echo '</pre>'; 	
	$search_host = '10.132.22.123';
	$search_port = '9200';
	$index_name = 'onescdvoice-content';
	$type = 'people';
	$results  = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );

	$total_resulsts = $results->hits->total;
	$results = $results->aggregations->by_category->buckets;
	
	
	//echo '<pre>';var_dump($total_resulsts);echo '</pre>'; 
	//echo '<pre>';var_dump($results);echo '</pre>'; 
	
	if( $total_resulsts ) {
		$i = 0; 
		foreach( $results as $result ) {
				
			$result_item_category = $result->key;
			$result_items = $result->tops->hits->hits;
			$result_items_count =  $result->tops->hits->total;
			
			
			$msg .= '<div class="panel panel-default">';
				
			$msg .= '
			<div class="" role="tab" id="headingOne' . $i . '">
				<h4 class="panel-title evidence-education-title">
					<a names="' . $result_item_category . '" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne' . $i . '" aria-expanded="true" aria-controls="collapseOne' . $i . '"  class="collapsed grey_bg">
				' . $result_item_category . '&nbsp; (' . $result_items_count . ')
						<div class="cl_indicator">+ show</div>
					</a>
				</h4>
			</div>';
			
			$msg .= '
			<div id="collapseOne' . $i . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne' . $i . '">
				<div class="panel-body">';
			
			$displayed_count  = 0;
			$view_all_flag = 0;
			$j = 1;
			
			foreach( $result_items as $result_item ) {
				
				$post_id 			= $result_item->_source->id;
				$post_title 		= $result_item->_source->title;
				$post_url			= $result_item->_source->post_url;
				$post_image 		= $result_item->_source->person_image;
				if (!$post_image ) {
					$post_image = get_template_directory_uri() . '/images/pp_doctor.png';
				}
				$specialities		= $result_item->_source->person_specialities;
				$address  			= $result_item->_source->person_street_address;
				$city  				= $result_item->_source->person_city;
				$state				= $result_item->_source->person_state;
				$countrypeople		= $result_item->_source->person_countrypeople;
				$first_name			= $result_item->_source->person_first_name;
				$middle_name		= $result_item->_source->person_middle_name;
				$last_name			= $result_item->_source->person_last_name;
				$qualification		= $result_item->_source->person_qualification;
				
				$person_full_name 	= $post_title.$first_name.$middle_name.$last_name.$qualification;
				
				if ( $displayed_count == $post_limits ) {
					$msg .= '<div class="evidence_content_view_all" style="display:none">';
					$view_all_flag = 1;
				}
				if( $j % 2 == 1 ) {
					$msg .= '<div class="row">';
				}
				
				
					$msg .= '
					<div class="col-sm-6">
						<div class="pp_bucket people" data-id="' . $post_id . '">
							<div class="pp_bucket_img">
								<a class="" href="' . $post_url . '">
									<img src="' . $post_image . '">
								</a>
							</div>
						
							<div class="pp_bucket_info">
							
								<a class="" href="' . $post_url . '">
									<h2>' . $person_full_name . '</h2>
								</a>'. $specialities. '<br>';
								
								if ( $address ) {
									$msg .= $address. '<br>';
								}
								if ( $city ) {
									$msg .= $city . '';
								}
								if ( ( $state || $countrypeople ) && ( $city != '' ) ) {
									$msg .= ',  ';
								}
								if ( $state ) {
									$msg .= $state;
								}
								if ( ( $countrypeople ) && ( $state ) ) {
									$msg .= ',  ';
								}
								if ( $countrypeople )  {
									$msg .= $countrypeople;
								}

								$msg .= '<br>
							</div>
						</div>
					</div>';
				
				
				if( $j++ % 2 == 0 ) {
					$msg .= '</div>';
				}
				$displayed_count ++;
				
			}
			if( --$j % 2 != 0 ) {
					$msg .= '</div>';
			}
			
			if ( $view_all_flag ) {
				$msg .= '</div>';
			}
			if ( $result_items_count > $post_limits ) {
				//$remaining_post_count = $result_items_count - $post_limits;
				$msg .= '<span class="curate-readmore-new" >view all ('.$result_items_count.') </span>';
			}
			$msg .= '</div>'; //panel body
			$msg .= '</div>'; // collapse
			$msg .= '</div>'; //panel panel default.
			$i++;
		}
	}
	
	return $msg;
}