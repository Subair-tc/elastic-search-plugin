<?php

function places_elastic_search( $searchname ='', $loc_name ='', $category_name ='', $countryplace_name ='',$citypl_val ='', $statepl_val ='', $limits_value = 6  ) {
	
	if ( $searchname ) {
		$flag = 1;
		setcookie( 'session_plsearchname', $searchname, time() + 180, '/' );
		
		$subquery .= '
			"should": [
				{
					"query_string": {
						"default_field": "title",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "content",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_address",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_image",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_email",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_phone",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_phone_additional",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_official_website",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_city",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_state",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_country",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_url_1",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_url_2",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_url_3",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_orphanet_url",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_country_state_city",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_further_details_1",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_further_details_2",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_further_details_3",
						"query": "'.$searchname.'"
					}
				},
				{
					"query_string": {
						"default_field": "place_information_source",
						"query": "'.$searchname.'"
					}
				}
				
			],
			"minimum_should_match" : 1';
	}
	
	
	if ( $category_name) {
		$categories = implode( ' , ', $category_name );
		$categories_arr   = array_filter( $categories_name );
		$count_categories = count( $categories_arr );
		setcookie( 'session_pl_category', $categories, time() + 180, '/' );
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		$subquery .='
			"must": [{
				"match": {
					"place_type_name": {
						"query": ["'.$categories .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
	}
	
	if ( $countryplace_name ) {
		$country_name = implode( ' , ', $countryplace_name );
		$country_arr   = array_filter( $countryplace_name );
		$count_country = count( $country_arr );
		setcookie( 'session_pl_country', $country_name, time() + 180, '/' );
		$subquery .='
			"must": [{
				"match": {
					"place_country": {
						"query": ["'.$country_name .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
		
	}
	
	if ( $citypl_val ) {
		$loc_city = implode( ' , ', $citypl_val );
		$loc_city_arr   = array_filter( $citypl_val );
		$count_loc_city = count( $loc_city_arr );
	    setcookie( 'session_pl_city', $loc_city, time() + 180, '/' );
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		$subquery .='
			"must": [{
				"match": {
					"place_city": {
						"query": ["'.$loc_city .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
	}
	if ( $statepl_val ) {
		$loc_state = implode( ' , ', $loc_state );
		$loc_state_arr   = array_filter( $statepl_val );
		$count_loc_state = count( $loc_state_arr );
		setcookie( 'session_pl_state', $loc_state, time() + 180, '/' );
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		$subquery .='
			"must": [{
				"match": {
					"place_city": {
						"query": ["'.$loc_city .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
	}
	
	
	if( ! $limits_value ) {
		$limits_value = 6;
	}
	setcookie( 'session_pllimits', $limits_value, time() + 180, '/' );
	
	
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
						"field": "place_type_slug.slug",
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
	$type = 'place';
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
			<div class="" role="tab" id="headingOne' . $i . $i . '">
				<h4 class="panel-title evidence-education-title">
					<a names="' . $result_item_category . '" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne' . $i . $i . '" aria-expanded="true" aria-controls="collapseOne' . $i . $i . '"  class="collapsed grey_bg">
				' . $result_item_category . '&nbsp; (' . $result_items_count . ')
						<div class="cl_indicator">+ show</div>
					</a>
				</h4>
			</div>';
			
			$msg .= '
			<div id="collapseOne' . $i . $i . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne' . $i . $i . '">
				<div class="panel-body">';
			
			$displayed_count  = 0;
			$view_all_flag = 0;
			$j = 1;
			
			foreach( $result_items as $result_item ) {
				
				$post_id 			= $result_item->_source->id;
				$post_title 		= $result_item->_source->title;
				$post_url			= $result_item->_source->post_url;
				$place_image 		= $result_item->_source->place_image;
				if ( ! $place_image ) {
					$place_image = get_template_directory_uri() . '/images/ee_places.png';
				}
				$place_address		= $result_item->_source->place_address;
				$place_email		= $result_item->_source->place_email;
				$place_phone		= $result_item->_source->place_phone;
				$pace_website		= $result_item->_source->place_official_website;
				
				if ( $displayed_count == $limits_value ) {
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
									<img src="' . $place_image . '">
								</a>
							</div>
						
							
							
							<div class="pp_bucket_info">
								<a class="" href="' . $post_url . '">
									<h2>' . $post_title . '</h2>
								</a>' . $place_address. '<br>';
								if ( $place_email ) {
									$msg .= '<a  href="mailto:' . $place_email . '">email</a> &nbsp';
								}
								if ( $place_phone ) {
									$msg .= '<a  title="' . $place_phone . '" href="tel:' . $place_phone . '">phone</a> &nbsp;';
								}
								if ( $pace_website ) {
									$msg .= '<a  href="' . $pace_website . '">website</a> &nbsp;';
								}
								$msg .= '
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
			if ( $result_items_count > $limits_value ) {
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