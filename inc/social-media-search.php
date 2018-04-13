<?php

function social_media_elastic_search( $post_search_name ='', $post_limits=6 ){
	
	setcookie( 'session_curatesearch', $post_search_name, time() + 180, '/' );
	
	if ( $post_limits == '' ) {
		$post_limits = 6;
	}
	setcookie( 'session_curatelimits', $post_limits, time() + 180, '/' );
	
	$search_host = '10.132.22.123';
	$search_port = '9200';
	$index_name = 'onescdvoice-content';
	
	
	
	// social and media.
	$query = '
	{
		"query": {
			"bool": {
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
					
				]
			}
		},
		"size": 250
	}';
	$type = 'media-social';
	$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
	$total_resulsts = $results->hits->total;
	$results = $results->hits->hits;
	
	$i = 0;
	$section_title = 'social';
	$section_url_field = 'social_url';
	$section_bg_class = 'social_bg';
	if( $total_resulsts ) {
		$msg = get_social_media_result( $i, $total_resulsts , $section_title,  $results,$section_url_field, $section_bg_class ,$post_limits );
	}
	
	
	// Photo.
	$query = '
	{
		"query": {
			"bool": {
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
							"default_field": "photo_category_name",
							"query": "'.$post_search_name.'"
						}
					},
					{
						"query_string": {
							"default_field": "photo_url",
							"query": "'.$post_search_name.'"
						}
					},
					{
						"query_string": {
							"default_field": "photo_information_sourse",
							"query": "'.$post_search_name.'"
						}
					}
					
				]
			}
		},
		"size": 250
	}';
	$type = 'photo';
	$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
	$total_resulsts = $results->hits->total;
	$results = $results->hits->hits;
	
	$i = 0;
	$section_title = 'photo';
	$section_url_field = 'photo_url';
	$section_bg_class = 'photo_bg';
	if( $total_resulsts ) {
		$msg .= get_social_media_result( $i, $total_resulsts , $section_title,  $results,$section_url_field, $section_bg_class ,$post_limits );
	}
	
	// fundraising.
	$query = '
	{
		"query": {
			"bool": {
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
							"default_field": "fundraising_category_name",
							"query": "'.$post_search_name.'"
						}
					},
					{
						"query_string": {
							"default_field": "fundraising_parent_category_name",
							"query": "'.$post_search_name.'"
						}
					},
					{
						"query_string": {
							"default_field": "fundraise_url",
							"query": "'.$post_search_name.'"
						}
					},
					{
						"query_string": {
							"default_field": "fundraise_information_sourse",
							"query": "'.$post_search_name.'"
						}
					}
					
				]
			}
		},
		"size": 250
	}';
	$type = 'fundraising';
	$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
	$total_resulsts = $results->hits->total;
	$results = $results->hits->hits;
	
	$i = 0;
	$section_title = 'fundraising';
	$section_url_field = 'fundraise_url';
	$section_bg_class = 'fundraising_bg';
	if( $total_resulsts ) {
		$msg .= get_social_media_result( $i, $total_resulsts , $section_title,  $results,$section_url_field, $section_bg_class ,$post_limits );
	}
	
	
	
	
	
	// websites.
	$query = '
	{
		"query": {
			"bool": {
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
							"default_field": "website_category_name",
							"query": "'.$post_search_name.'"
						}
					},
					{
						"query_string": {
							"default_field": "website_url",
							"query": "'.$post_search_name.'"
						}
					},
					{
						"query_string": {
							"default_field": "website_information_sourse",
							"query": "'.$post_search_name.'"
						}
					}
					
				]
			}
		},
		"size": 250
	}';
	$type = 'website';
	$results = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
		
	$total_resulsts = $results->hits->total;
	$results = $results->hits->hits;
	
	$i = 0;
	$section_title = 'website';
	$section_url_field = 'website_url';
	$section_bg_class = 'website_bg';
	if( $total_resulsts ) {
		$msg .= get_social_media_result( $i, $total_resulsts , $section_title,  $results,$section_url_field, $section_bg_class ,$post_limits );
	}
	
	return $msg;
}


function get_social_media_result( $i, $total_resulsts , $section_title,$results,$section_url_field, $section_bg_class ,$post_limits ) {
	$output = '';
		$displayed_count = 0;
		$twitter_text_limit = ot_get_option( 'twitter_text_limit' );
		if ( $total_resulsts ) {
			$output .='
			<div class="panel panel-default">
				<div class="" role="tab" id="headingOne' . $i . '">';
					$output .= '
					<h4 class="panel-title website-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne' . $i . '" aria-expanded="true" aria-controls="collapseOne' . $i . '"  class="collapsed '.$section_bg_class.'">
						 '.$section_title.' &nbsp; (' . $total_resulsts . ')
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
				$section_url = $result->_source->$section_url_field;
				$title_post = '<a href="' . $section_url . ' " target="_blank">' . $post_title . '</a>';
				$truncated_title = "";
				if ( strlen( $post_title ) > $twitter_text_limit ) { 
					$truncated_title = "...";
				} 
				
				if ( strlen( $post_content ) > 190 ) {
					$allcontent = '
					<div style="display:none;" class="rc_socia_entry">' . $post_content . '</div>
						<div class="rms">
							<div class="short-desc">' . substr( $post_content,0,190 ) . '...
								<span style="" class="readMoreSpan">
								read more<i class="fa fa-angle-right" style=""></i>
							</span>
						</div>
					</div>';
					} else {
						$allcontent = '<div class="rc_socia_entry">' . $post_content . '</div>';
					}
				$post_link = $result->_source->post_url;
				
				if ( $displayed_count == $post_limits ) {
					$output .= '<div class="evidence_content_view_all" style="display:none">';
					$view_all_flag = 1;
				}
				
				$output .= '
					<div class="evidence_info_content " >
						<div class="info_cont_hide social-result-wrap" style="display:block;">
							<div class="social-tittle-result" data-id="' . $post_id . '">
								' . $post_title . '<br>
							</div>
							' . $allcontent . '
							<div class="social-share-box">
								 <div class="social-share"> <span class="icon-share"></span> share
									<div class="social-share-hov social-share-inner">
										<div class="share-discuss share-item share-item-inner">
											<a href="' . site_url() . '/groups/rarecourage/?shareid=' . $post_id . '" class="page-discuss-courage"><span class="icon-talk icon-left"></span> discuss in rareCourage</a>
										</div>
										
										<div class="post-facebook courage-social-cont share-item-inner fb_share_detail">
											<a  href="javascript:void(0)" title="share on Facebook" id="btnfbShare" data-detailurl="'.$post_link.'">
												<div  class="head_fb header_facebook_share  icon-left" style="cursor:pointer;">
													<i class="fa fa-facebook"></i> 
												</div>Facebook
											</a> 
										</div>
										
										<div class="post-twitter courage-social-cont share-item-inner">
											 <a data-href="https://twitter.com/intent/tweet?source=&text=:%20" title="share on Twitter" onclick="window.open(\'https://twitter.com/intent/tweet?text=\'+ encodeURIComponent(\''.trim( substr( $post_title, 0,$twitter_text_limit) ).$truncated_title.'\') + \' on @'.$GLOBALS['onvoice_label']['lbltwtonevoice'].' \' + encodeURIComponent(\''.$post_link.'\')); return false;">
												<div  class="head_tweet header_twitter_share  icon-left" style="cursor:pointer;">
													<i class="fa fa-twitter"></i>  
												</div>Twitter
											</a>
										</div>
											
										<div  class="share-item-inner">
											<div  class="head_mailer_element" style="cursor:pointer;" title="share via email" data-detailurl="'.$post_link.'" data-check="'.$post_title.'">
												<div class="head_mailer icon-left"><i class="fa  fa-envelope"></i></div>
												email
											</div>
										</div>
										<div class="share-team share-item share_with_rareteam share-item-inner" post="'.$post_id.'" ><span class="share-team-icon icon-left">
											</span>share to rareTeam
										</div>
									</div>
								</div>
								
								<div class="page-myBinder share-binder share-item" id="social-media" data-id="'.$post_id.'">
									<span class="icon-myBinder"></span> +myBinder
								</div>
							</div>
						</div>
					</div>';
				$displayed_count ++;
			}
			
			if ( $view_all_flag ) {
				$output .= '</div>';
			}
			if ( $total_resulsts > $post_limits ) {
				//$remaining_post_count = $result_items_count - $post_limits;
				$output .= '<span class="curate-readmore-new" >view all ('.$total_resulsts.') </span>';
			}
			
			$output .= '</div></div></div>';
		}
		
		
		return $output;
}