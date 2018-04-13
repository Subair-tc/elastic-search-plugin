<?php

function evidence_education_search( $post_searchname ='', $post_category_name ='', $post_year_name='',  $post_source_name='', $post_organisation_name='', $post_author_names='', $post_limits = 6 , $from = 0 ) {

	if( ! $from ){
		$from = 0;
	}

	$subquery = '';
	$flag =0 ;
	if( $post_searchname ) {
		setcookie( 'session_eesearchname', $post_searchname, time() + 180, '/' );
		$flag = 1;
		
		$subquery .= '"should": [
						{
							"query_string": {
								"default_field": "content",
								"query": "'.$post_searchname.'"
								}
						},
						{
							"query_string": {
								"default_field": "title",
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
						}
						],
						 "minimum_should_match" : 1';
	}

	if( $post_category_name ) {

		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		$category = implode( ' , ', $post_category_name );
		setcookie( 'session_eecategory', $category, time() + 180, '/' );
		$subquery .='"must": [{
							"match": {
								"evidance_category_slug": {
									"query": ["'.$category .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}
	if( $post_year_name ) {
		
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		
		$years = implode( ' , ', $post_year_name );
		setcookie( 'session_eeyear', $post_year_name, time() + 180, '/' );
		$subquery .='"must": [{
							"match": {
								"year_evidence": {
									"query": ["'.$years .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}

	if ( $post_source_name ) {
		setcookie( 'session_eesource', $post_source_name, time() + 180, '/' );
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}

		$subquery .='"must": [{
							"match": {
								"source_evidence": {
									"query": ["'.$post_source_name .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}


	if ( $post_organisation_name ) {

		setcookie( 'session_eeorganization', $post_organisation_name, time() + 180, '/' );

		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}

		$subquery .='"must": [{
							"match": {
								"organization_evidence": {
									"query": ["'.$post_organisation_name .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}

	if ( $post_author_names ) {

		setcookie( 'session_eeauthor', $post_author_names, time() + 180, '/' );

		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}

		$subquery .='"must": [{
							"match": {
								"author_evidence": {
									"query": ["'.$post_author_names .'"],
									"operator": "or",
									"zero_terms_query": "all"
								}
							}
						}]';
	}

	if( ! $post_limits ) {
		$post_limits = 6;
	}

	setcookie( 'session_eelimits', $post_limits, time() + 180, '/' );

	
	$query = '

	{
		"from": '. $from .',
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
					"field": "evidance_category_slug.slug",
					"size": 0
				},
				"aggs": {
					"tops": {
						"top_hits": {
							"size": 50
						}
					}
				}
			}
		}
	}

	';

	//echo '<pre>';var_dump($query);echo '</pre>'; exit;


	
	$search_host = '10.132.22.123';
	$search_port = '9200';
	$index_name = 'onescdvoice-content';
	$type = 'evidance-education';
	$results  = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
	//echo '<pre>';var_dump($results);echo '</pre>'; 
	//echo $results['count'];
	//echo '<pre>';var_dump($results['results']);echo '</pre>';

	
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
				
				$post_title = $result_item->_source->title;
				$evidence_cource = $result_item->_source->source_evidence;
				$content =	$result_item->_source->content;
				$truncated_content = apply_filters( 'the_content', substr( $content, 0,450 ) );
				$post_link = $result_item->_source->post_url;
				$readmore = 'readmore';
				
				if ( $displayed_count == $post_limits ) {
					$msg .= '<div class="evidence_content_view_all" style="display:none">';
					$view_all_flag = 1;
				}
				$msg .= '
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
					
				</div>';
				
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