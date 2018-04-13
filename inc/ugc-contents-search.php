<?php


function search_ugc_contents( $search_term ='', $post_limits = 6 ) {
	
	if( !is_user_logged_in() || ! $search_term ) {
		$return['posts'] = '<span style="font-size:20px;">no results found</span>';
		$return['total_results'] = 0;
		return $return;
	}
	
	if( $post_limits == '' ) {
		$post_limits = 6;
	}
	
	$query = '
		{
		"query": {
			"filtered": {
				"query": {
					"bool": {
						"should": [{
							"query_string": {
								"default_field": "content",
								"query": "'.$search_term.'"
							}
						}],
						"minimum_should_match": "1"


					}
				}
			}
		},
		"size": 0,
		"aggs" : {
			"by_users" : {
				"terms" : { "field" : "user_id", "size": 0 },
				"aggs": {
					"tops": {
						"top_hits": {
							"size": 100
						}
					}
				}
			}
		}

	}';
	
	
	
	$search_host 	= '10.132.22.123';
	$search_port 	= '9200';
	$index_name 	= 'onescdvoice-content';
	$type			= 'UGC_contents';
	$msg = '';
	$results =  onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
	$total_resulsts = $results->hits->total;
	$results = $results->aggregations->by_users->buckets;
	
	if ( $total_resulsts ) {
		
		foreach( $results as $result ) {
			
			$user_id = $result->key;
			$result_items = $result->tops->hits->hits;
			$result_items_count =  $result->tops->hits->total;
			
			$user_data = get_user_by( 'id', $user_id );
			$user_avatar = bp_core_fetch_avatar(
								array(
									'item_id' => $user_id,
									'type' => 'thumb',
									'html' => true,
									'alt' => 'User Avatar',
								)
							);
			
			//echo '<pre>';var_dump( $result_items );echo '</pre><br/>';
			
			$msg .= 
			'<div class="panel panel-default">';
			
			$msg .= '
			<div class="" role="tab" id="headingThree' . $i . '">
				<h4 class="panel-title rareCourage-panel-title">
					<a class="collapsed grey_bg" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-courage' . $i . '" aria-expanded="false" aria-controls="collapseThree' . $i . '">  
						<span style="">' . $user_avatar . '</span>'
						.  $user_data->display_name . '&nbsp; (' . $result_items_count . ')
						<div class="cl_indicator">+ show</div>
					</a>
				</h4>
			</div>';
			
			$msg .= '
			<div id="collapse-courage' . $i . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree' . $i . '">
				<div class="panel-body">';
				
			$displayed_count  = 0;
			$view_all_flag = 0;
			foreach( $result_items as $result_item ) {
				
				$activity_id = $result_item->_source->id;
				$activity_content = $result_item->_source->content;
				$activity_link = $result_item->_source->primary_link.'activity/'.$activity_id ;
				
				if ( $displayed_count == $post_limits ) {
					$msg .= '<div class="evidence_content_view_all" style="display:none">';
					$view_all_flag = 1;
				}
				$msg .= '
					<div class="courage-list courage gblcourage " data-id="' . $activity_id . '" >
						<div class="courage-list-title collapsed drgout">
							' . $activity_content . ' <br>
							<span class="courage_post_posted_date">
								<a href="' .$activity_link . '" class="external"  >click to see entire conversation</a>
							</span>
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
	
	
	$return['posts'] = $msg;
	$return['total_results'] = $total_resulsts;
	return $return;
	
}