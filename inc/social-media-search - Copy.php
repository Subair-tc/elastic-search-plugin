<?php

function social_media_elastic_search( $post_search_name ='', $post_limits=6 ){
	
	setcookie( 'session_curatesearch', $post_search_name, time() + 180, '/' );
	
	if ( $post_limits = '' ) {
		$post_limits = 6;
	}
	setcookie( 'session_curatelimits', $post_limits, time() + 180, '/' );
	
	
	
	$subquery = '
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
					"default_field": "social_category_name",
					"query": "'.$post_search_name.'"
				}
			},
			{
				"query_string": {
					"default_field": "social_category_parent_name",
					"query": "'.$post_search_name.'"
				}
			},
			{
				"query_string": {
					"default_field": "social_company",
					"query": "'.$post_search_name.'"
				}
			},
			{
				"query_string": {
					"default_field": "social_information_sourse",
					"query": "'.$post_search_name.'"
				}
			}
			
		],
		"minimum_should_match" : 1';
		
		
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
					"field": "social_category_parent_slug.slug",
					"size": 0
				},
				"aggs": {
					"by_sub_category": {
						"terms": {
							"field": "social_category_slug.slug",
							"size": 0
						},
						"aggs": {
							"tops": {
								"top_hits": {
									"size": 250
								}
							}
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
	$type = 'media-social';
	$results  = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );

	$total_resulsts = $results->hits->total;
	$results = $results->aggregations->by_category->buckets;
	
	
	//echo '<pre>';var_dump($total_resulsts);echo '</pre>'; 
	//echo '<pre>';var_dump($results);echo '</pre>';
	
	if( $total_resulsts ) {
		foreach ( $results as $result ) {
			$result_item_category = $result->key;
			$result_items = $result->tops->hits->hits;
			$result_items_count =  $result->tops->hits->total;
		}
	}
	
}