<?php
function news_meetings_search( $twitter_text_limit='',$searchname ='', $limits=6 ) {
	
	if($twitter_text_limit ='') {
		$twitter_text_limit  = ot_get_option( 'twitter_text_limit' );
	}
	if( $searchname  ){
		$subquery .= '"should": [
					{
						"query_string": {

							"default_field": "content",
							"query": "'.$searchname.'"

						}
					},
					{
						"query_string": {

							"default_field": "title",
							"query": "'.$searchname.'"

						}
					}
					],
					 "minimum_should_match" : 1';
		
	}
	
	
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
						"field": "news_place_type_slug.slug",
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
	
	//var_dump($query);exit;
		$search_host = '10.132.22.123';
		$search_port = '9200';
		$index_name = 'onescdvoice-content';
		$type = 'news-meeting';
		$results  = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
	
		
		$total_resulsts = $results->hits->total;
		$results = $results->aggregations->by_category->buckets;
		if ( $total_resulsts ) {
			$i = 0; 
		
			$msg = '<div class="panel-group" id="curate-accordion" role="tablist" aria-multiselectable="true">';
			foreach( $results as $result ) {
			
				$result_item_category = $result->key;
				$result_items = $result->tops->hits->hits;
				$result_items_count =  $result->tops->hits->total;
			
				$msg .= '<div class="panel panel-default">';
			
				$msg .= '
				<div class="" role="tab" id="headingOne' . $i . '">
					<h4 class="panel-title news-meetings-title">
						<a names="' . $result_item_category . '" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne' . $i . '" aria-expanded="true" aria-controls="collapseOne' . $i . '"  class="collapsed grey_bg">
					' . $result_item_category . '&nbsp; (' . $result_items_count . ')
							<div class="cl_indicator">+ show</div>
						</a>
					</h4>
				</div>';
				
				$msg .= '
				<div id="collapseOne' . $i . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne' . $i . '">
					<div class="panel-body"> <div class="row">';
				
				$displayed_count  = 0;
				$view_all_flag = 0;
				foreach( $result_items as $result_item ) {
					
					$post_id = $result_item->_source->id;
					
					$post_title = $result_item->_source->title;
					$truncated_title = "";
					if ( strlen( $post_title ) > $twitter_text_limit ) { 
						$truncated_title = "...";
					} 
					
					$post_link = $result_item->_source->post_url;
					
					$newsimage = $result_item->_source->news_image;
					if( $newsimage == '' ) {
						$newsimage =  get_template_directory_uri() . '/images/no-img-cont.png';
					}
					
					
					$news_url = $result_item->_source->news_url;
					$newsdate = $result_item->_source->news_date;
					
					if ( $displayed_count == $limits ) {
						$msg .= '<div class="evidence_content_view_all" style="display:none">';
						$view_all_flag = 1;
					}
					
					$msg .= '
					<div class="col-sm-12">
						<div class="feat_new_block news-meetings-tab" data-id="' . $post_id . '">
							<div class="feat_new_block_img col-sm-3">
								<a class="curate_exernalClick"  href="' . $news_url. '" target="_blank">
									<img src="' . $newsimage. '">
								</a>
							</div>';
							
							$msg .= '
							<div class="feat_new_block_title col-sm-9">
								<a class="curate_exernalClick"  href="' . $news_url . '" target="_blank">
									<p><h2>' . $post_title . '</h2></p>
								</a>';
								
								$msg .= '
								<div class="news-top-share">
									<div class="time">';
										if ( $newsdate ) {
											$msg .= $display_date = date( 'M j, Y', $newsdate );
										}
									$msg .= '
									</div>

									<div class="clinical_share">
										<div class="social-share-box">
											<div class="social-share">
												<span class="icon-share"></span> share
												
												<div class="social-share-hov social-share-inner">
													
													<div class="share-discuss share-item share-item-inner">
														<a href="'.site_url() .'/groups/rarecourage/?shareid=' . $post_id . '" class="page-discuss-courage">
															<span class="icon-talk icon-left"></span>
															discuss in rareCourage
														</a>
														
													</div>
													
													<div class="post-facebook courage-social-cont share-item-inner">
														<a  href="javascript:void(0)" title="share on Facebook" data-detailurl="'. $post_link.'" id="btnfbShare">
															<div  class="head_fb header_facebook_share  icon-left" style="cursor:pointer;">
																<i class="fa fa-facebook"></i> 
															</div>
															Facebook
														</a> 
													</div>
													
													<div class="post-twitter courage-social-cont share-item-inner">
														<a data-href="https://twitter.com/intent/tweet?source=&text=:%20" title="share on Twitter" onclick="window.open(\'https://twitter.com/intent/tweet?text=\'+ encodeURIComponent(\''.trim( $post_title, 0,$twitter_text_limit ) .$truncated_title.'\') + \' on @'.$GLOBALS['onvoice_label']['lbltwtonevoice'].' \' + encodeURIComponent(\''.$post_link.'\')); return false;">
															<div  class="head_tweet header_twitter_share  icon-left" style="cursor:pointer;">
																<i class="fa fa-twitter"></i>  
															</div>Twitter
														</a>
													</div>

													<div  class="share-item-inner">
														<div  class="head_mailer_element" style="cursor:pointer;" title="share via email" data-detailurl="'.$post_link.'" data-check="'. $post_title.'">
															<div class="head_mailer icon-left">
																<i class="fa  fa-envelope"></i>
															</div>email
														</div>
													</div>

													<div class="share-team share-item share_with_rareteam share-item-inner" post=""' . $post_id. '"" >
														<span class="share-team-icon icon-left"></span>share to rareTeam
													</div>
												</div>
											</div>
										</div>	
									</div>
																
									<div class="clinical_share news-bind">
										<div class="page-myBinder share-binder share-item" data-tag="news-meeting" data-id="' . $post_id . '">
											<span class="icon-myBinder"></span> +myBinder
										</div>
									</div>
								</div>';	
							$msg .='
							</div>
						</div>
					</div>';
					
				
				}
			
			if ( $view_all_flag ) {
				$msg .= '</div>';
			}
			if ( $result_items_count > $limits ) {
				//$remaining_post_count = $result_items_count - $post_limits;
				$msg .= '<span class="curate-readmore-new" >view all ('.$result_items_count.') </span>';
			}
			$msg .= '</div></div></div></div>';
			
			
			$i++;
		}
		
		
		$msg .= '</div>';
		echo $msg;
	
		}
	if ('' == $searchname && '' != $post_search_name ) {
		$msg ='<div class="panel-group" id="curate-accordion" role="tablist" aria-multiselectable="true"></div>';
	}
	
}