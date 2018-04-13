<?php

function rare_clinical_elastic_search( $post_search_name = '', $limits = 6 ){
	// Search full content for post type.
	
	$search_host = '10.132.22.123';
	$search_port = '9200';
	$index_name = 'onescdvoice-content';
	$numberof_posts_clinical = 0;
	
	$msg = '';
	
	//evidenceeducation
	
	$type = 'rarehub';
	$query = '
	
	{
		"query": {
			"bool": {
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
					
				]
			}
		},
		"size": 50
	}';
	
	$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
	
	$total_resulsts = $results->hits->total;
	$results = $results->hits->hits;
	$numberof_posts_clinical += $total_resulsts;
	$i = 10;
	$drg_class = '-rareHub';
	$section_title = 'rarehub';
	
	$msg .= get_clinical_search_message( $i, $total_resulsts , $drg_class, $section_title,  $results, $limits );
	

	$return['posts'] = $msg;
	$return['total_results'] = $numberof_posts_clinical;
	return $return;
}


function get_clinical_search_message( $i, $total_resulsts , $drg_class,$section_title,  $results, $limits =6  ) {

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