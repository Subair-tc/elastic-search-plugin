<?php

function rarehub_elastic_search( $post_search_name ='',$post_keyword_name ='', $post_centername ='', $post_sponsorname ='', $post_typeoftrial_name ='', $post_centerstate_name ='',  $post_phase_name ='', $post_limits =6  ) {
	
	$subquery = '';
	$flag =0;
	
	if (  $post_search_name ) {
		setcookie( 'session_hubsearchname', $post_search_name, time() + 180, '/' );
		$flag = 1;
		$subquery .= '
			"should": [
				{
					"query_string": {
						"default_field": "content",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "title",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "condition",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "status",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "intervention",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_id",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "phase_of_development",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_type",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "size_enrollment",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_design",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_description",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "primary_outcomes",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "secondary_outcomes",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "inclusion_criteria",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "exclusion_criteria",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "sponsor",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "investigators",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "sourcedatabase",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "view_full_trial_record",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "results",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "otheroutcomes",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_website",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_details",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_start_date",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_end_date",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "study_source_link",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "countries",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "secondary_category",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "center_name",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "center_state",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "publications",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "keyword",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "type_of_trail",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "type_of_sponsor",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "center_city",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "zip_code",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "lat_and_long",
						"query": "'.$post_search_name.'"
						}
				},
				{
					"query_string": {
						"default_field": "last_updated_date",
						"query": "'.$post_search_name.'"
						}
				}
				
			],
			"minimum_should_match" : 1';
		
	}
	
	if ( $post_keyword_name ) {
		setcookie( 'session_hubkeyword_name', $post_keyword_name, time() + 180, '/' );
		$subquery .='"must": [{
							"match": {
								"keyword": {
									"query": ["'.$post_keyword_name .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}
	
	if ( $post_centername ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		setcookie( 'session_hubcentername', $post_centername, time() + 180, '/' );
		
		$subquery .='"must": [{
							"match": {
								"center_name": {
									"query": ["'.$post_centername .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}
	
	if ( $post_sponsorname ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		setcookie( 'session_hubsponsorname', $post_sponsorname, time() + 180, '/' );
		
		$subquery .='"must": [{
							"match": {
								"sponsor": {
									"query": ["'.$post_sponsorname .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}
	
	
	if ( $post_typeoftrial_name ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		setcookie( 'session_hubtypeoftrial', $post_typeoftrial_name, time() + 180, '/' );
		$typeoftrial = implode( ' , ', $post_typeoftrial_name );
		
		$subquery .='"must": [{
							"match": {
								"type_of_trail": {
									"query": ["'.$typeoftrial .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}
	
	if ( $post_centerstate_name ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		setcookie( 'session_hubcenterstate', $post_centerstate_name, time() + 180, '/' );
		
		//$centerstate               = explode( ',', $post_centerstate_name );
		
		$subquery .='"must": [{
							"match": {
								"center_state": {
									"query": ["'.$post_centerstate_name .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}
	
	if ( $post_phase_name ) {
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		setcookie( 'session_hubphase', $post_phase_name, time() + 180, '/' );
		//$phase_name               = implode( ' , ', $post_phase_name );
		$subquery .='"must": [{
							"match": {
								"phase_of_development": {
									"query": ["'.$post_phase_name .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}
	

	if( $post_limits == '' ) {
		$post_limits = 6;
	}
	setcookie( 'session_hublimits', $post_limits, time() + 180, '/' );
	
	
	
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
					"field": "clinical_category_slug.slug",
					"size": 0
				},
				"aggs": {
					"tops": {
						"top_hits": {
							"size": 500
						}
					}
				}
			}
		}
	}

	';
	
	//echo '<pre>';var_dump( $query );echo '</pre>';
	$search_host = '10.132.22.123';
	$search_port = '9200';
	$index_name = 'onescdvoice-content';
	$type = 'rarehub';
	$results  = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
	//echo '<pre>';var_dump($results);echo '</pre>'; 
	//echo $results['count'];
	

	
	$total_resulsts = $results->hits->total;
	$results = $results->aggregations->by_category->buckets;
	//echo '<pre>';var_dump($total_resulsts);echo '</pre>'; 
	//echo '<pre>';var_dump($results);echo '</pre>';
	
	if( $total_resulsts ) {
		$i = 0; 
		
		//$msg = '<div class="panel-group" id="curate-accordion" role="tablist" aria-multiselectable="true">';
		
		//echo '<pre>';var_dump($results);echo '</pre><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
		foreach( $results as $result ) {
			
			$result_item_category = $result->key;
			$result_items = $result->tops->hits->hits;
			$result_items_count =  $result->tops->hits->total;
			
			//echo '<pre>';var_dump( $result_items );echo '</pre><br/>';
			
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
			foreach( $result_items as $result_item ) {
				
				$post_id = $result_item->_source->id;
				
				$post_title 		= $result_item->_source->title;
				$content 			= $result_item->_source->content;
				$truncated_content 	= substr( $content, 0,450 );
				$post_link 			= $result_item->_source->post_url;
				$category_slug 		= $result_item->_source->clinical_category_slug;
				$sponsor 			= $result_item->_source->sponsor;
				$location 			= $result_item->_source->countries;
				$journal			= $result_item->_source->journal;
				$uid          		= get_current_user_id();
				$reviewed_user 		= get_post_meta( $post_id, 'reviewed_' . $uid . '', true );
				
				
				if ( $displayed_count == $post_limits ) {
					$msg .= '<div class="evidence_content_view_all" style="display:none">';
					$view_all_flag = 1;
				}
				$msg .= '
				<div class="rareHub_info_content" data-id="' . $post_id . '">';
					if ( 'recruiting' == $category_slug ) {
						
						if ( $uid ) {
							if ( $reviewed_user == $uid ) {
								$msg .= '<span  class="review_status"><i class="fa fa-check"> </i>reviewed</span>';
							} else {
								$msg .= '
								<span class="markedstatus' . $post_id . '" >
									<a id="' . $post_id . '" class="review-click">
										<i class="fa  fa-thumbs-up"> </i> review
									</a>
								</span>';
							}
						}
					}
					$msg .= '
					
					<div class="info_title collapsed">
						<h2>' .$post_title . '</h2>
						<span style="font-size:13px;font-style:italic;color:#677783;">' . $journal. '</span>
					</div>
					
					<div class="info_cont_hide">
						<p>
							<span class="highlight">purpose:</span> ' . $truncated_content . '
						</p>';
						if( $sponsor ){
							$msg .= '
							<p>
								<span class="highlight">sponsor:</span> ' . $sponsor . '
							</p>';
						}
						if ( $location ) {
							$msg .= '
							<p>
								<span class="highlight">location:</span> ' . $location . '                                                        
							</p>';
						}                                                                                                     
						$msg .= '
						<div class="trial_readmore">
							<a class="button_grey" href="' . $post_link . '">read more</a>
						</div>
					</div>
				</div>';
				
				
				
				
				
				/*$msg .= '
				<div class="evidence_info_content" >
				
					<div class="drg-evidence info_title collapsed" data-id="' . $post_id . '">
						<h2>' . $post_title. '</h2>
						<span style="font-size:13px;font-style:italic;color:#677783;">
							' . ucfirst( $evidence_cource  ) . '
						</span>
					</div>
				
					<div class="info_cont_hide">
						<p>' . $truncated_content . '</p>
						<div class="trial_readmore">
							<a class="button_grey"  href="' . $post_link . '"  >read more</a>
				
						</div>
					</div>
					
				</div>';*/
				
				$displayed_count ++;
				
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
	//$msg .= '</div>';
	return $msg;
	//var_dump($msg);
}