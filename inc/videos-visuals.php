<?php

function videos_visuals_elastic_search( $search_term ='', $category='' ){
	if ( $category ) {
		$categories = implode( ' , ', $category );
		
		//var_dump($categories );exit;
		$flag =  1;
		$subquery ='
			"must": [{
				"match": {
					"video_category_slug": {
						"query": ["'.$categories .'"],
						"operator": "or",
						"zero_terms_query": "all"
					}
				}
			}]';
			
			
	}
	if( $search_term ){
		if( $flag ) {
			$subquery .= ',';
		} else {
			$flag =1;
		}
		$subquery .= '
				"should": [
					{
						"query_string": {
							"default_field": "title",
							"query": "'.$search_term.'"
						}
					},
					{
						"query_string": {
							"default_field": "content",
							"query": "'.$search_term.'"
						}
					},
					{
						"query_string": {
							"default_field": "video_description",
							"query": "'.$search_term.'"
						}
					},
					{
						"query_string": {
							"default_field": "video_information_sourse",
							"query": "'.$search_term.'"
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
			"size": 100,
			"aggs": {}
		}

		';
	echo '<pre>';var_dump($query);echo '</pre>'; 	
	//echo '<pre>';var_dump($subquery);echo '</pre>'; 	
	$search_host = '10.132.22.123';
	$search_port = '9200';
	$index_name = 'onescdvoice-content';
	$type = 'video-visual';
	$results  = onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type );
	//echo '<pre>';var_dump($results);echo '</pre>'; 
	$total_resulsts = $results->hits->total;
	$results = $results->hits->hits;
	
	if( $total_resulsts ){
		
		foreach ( $results as $result_item ) {
			
			$videos_array =  array( "video", "videos", "Videos", "documentary", "interview" );
			$slides_array =  array( "Visuals/Slides", "visuals/slides", "visuals, slides", "slideshow" );
			$images_array =  array( "diagram", "infographic", "infographics", "Infographics", "Video playlist","Videos playlist","videos playlist", "video playlist","video channels","Videos/Channels", "Video Channels", "Flow Diagram","flow diagram", "Table", "table", "Image","Images", "images","image", "photo" );
			$twitter_text_limit  = ot_get_option( 'twitter_text_limit' );



			$postid 			=	$result_item->_source->id;
			$post_title 		=	$result_item->_source->title;
			$post_content 		=	$result_item->_source->content; 
			$post_url	 		=	$result_item->_source->post_url; 
			$visualdate 		=	$result_item->_source->video_date; 
			$video_description	=	$result_item->_source->video_description;
			$autocuration		= 	$result_item->_source->type_for_curation;
			$visualcategory		= 	$result_item->_source->content_format;
			
			$selecttype = get_post_meta( $postid,'Nominate type',true );
			if( $visualdate ) {
				$display_date = date( 'M j, Y ', $visualdate );
			} else {
				$display_date = get_the_date( 'M j, Y ', $post_id );
			}

			$truncated_title = "";
			if ( strlen( $post_title ) > $twitter_text_limit ) { 
				$truncated_title = "...";
			} 


			$thumbnail      = $result_item->_source->thumbnail_image;
			if( ! $thumbnail ) {
				 $thumbnail = esc_url( get_template_directory_uri() ).'/images/noimage-small.jpg';
			}



			$msg .= '<div class="grid">
				<div class="vidgrid " data-id="'.$post_id.'">';
					$msg .=  '
					<div class="imgholder">';
					if ( in_array($visualcategory, $videos_array) || 'video' == $selecttype ) { 
						$videosdata = strtolower( $post_content );
						if ( preg_match( '[.dailymotion|.youtube|vimeo.|youtu.be]',$videosdata ) ) {
							$videos_class = 'post_visual';
							$videos_subclass = '';
							$videos_target = 'myboxvideo_';
							
							$msg .=  video_modal_common( $postid, $videos_class, $videos_subclass, $videos_target );						
							$msg .= '
							<div class="shelf-box-cont visual-title">
								<a href = "#"  class="links">
									<h2>'.$post_title.'<h2>
								</a>
							</div>';
						} else { 
							$msg .= '
							<a class = "post_visual" href = "'.$post_content.'" target="_blank"  id ="'.$post_id .'">
								<div class="click-btn"></div>
								<img src="'.esc_url( $thumbnail ).'" border="0"/> 
							</a>
							<div class="shelf-box-cont visual-title">
								<a href = "#"  class="links"><h2>'.$post_title.'</h2></a>
							</div>';
						}
					
					} elseif ( in_array($visualcategory, $slides_array) || 'slide' == $selecttype ) { 
						$slidesdata = strtolower( $post_content );
						if ( preg_match( '[.slideshare]',$slidesdata ) ) {

							$slide_class = 'post_visual slide-main-banner';
							$slide_subclass = ' ';
							$slide_target = 'myboxvideo_';
							$msg .= video_modal_common( $post_id, $slide_class, $slide_subclass, $slide_target );
														
																		
							$msg .= '
							<div class="shelf-box-cont visual-title">
								<a href = "#"  class="links">
									<h2>'.$post_title.'</h2>
								</a>
							</div>';
						} else {
							$msg .= '
							<a class = "post_visual" href = "'.$post_content.'" target="_blank"  id ="'.esc_attr( $post_id ).'">
								<div class="click-btn"></div>
								<img src="'.esc_url( $thumbnail ).'" border="0"/> 
							</a>
							
							<div class="shelf-box-cont visual-title">
								<a href = "#"  class="links"><h2>'.$post_title.'</h2></a>
							</div>';
						}
					} elseif ( in_array( $visualcategory, $images_array ) || 'other' == $selecttype ||'image' == $selecttype ) { 
						$imagedata = strtolower( $post_content );
						if ( preg_match( '[.gif|.jpg|.jpeg|.png]',$imagedata ) ) {
							$image_class = 'post_visual';
							$image_subclass = 2;
							$image_data_target = 'mainother_';
							$msg.= video_modal_common( $post_id, $image_class, $image_subclass, $image_data_target );
						} else {
							$msg .= '
							<a class = "post_visual images" href = "'.$post_content.'" target="_blank"  id ="'.$post_id.'">
								<div class="click-btn images-curate"></div>
								<img src="'.esc_url( $thumbnail ).'" border="0"/>
							</a>';
						}
					} else {
						$msg .= '
						<a class = "post_visual images-curate" href = "'.$post_content.'" target="_blank"  id ="'.$post_id.'">
							<div class="click-btn"></div>
							<img src="'.esc_url( $thumbnail ).'" border="0"/> 
						</a>'; 
						}
					$msg .= '
					</div>';
					
					$msg .= '
					<div class="shelf-box-cont">';
						if ( in_array($visualcategory, $images_array)  || 'other' == $selecttype ||'image' == $selecttype ) {
							$msg .= '
							<a target="_blank" href = "'.$post_content.'"  class="links">
								<h2>'.$post_title.'</h2>
							</a>';
						}
						$msg .= '
						<span class="shelf-box-user">'.get_post_meta( $post_id,'Second Excerpt',true ).'</span><br>
						<span class="shelf-box-user">'.get_post_meta( $post_id,'wpcf-content-format',true ).'</span> <br>
						<span class="shelf-box-user">'.$display_date.'</span> <br>
					</div>'; 
					
					$msg .= '
					<div class="shelf-box-footer">
						<div class="courage-post-foot-share">';
							if ( is_plugin_active( 'mybinder/index.php' ) ) { 
								
								$msg .= '
								<div class="courage_post_foot_sub_talk video-bind">
									<a href="javascript:void(0)" class="page-myBinder" data-tag="video-visual" data-id="'.esc_attr( $post_id ).'">
									<span class="icon-myBinder"></span> +myBinder</a>
								</div>';
								
							} 
							$msg .= '
							<div class="social-share-box">
								<div class="social-share"> <span class="icon-share"></span> share
									<div class="social-share-hov social-share-inner">';
										
										$msg .= '
										<div class="share-discuss share-item share-item-inner">
											<a href="'.site_url().'/groups/rarecourage/?shareid='.esc_attr( $post_id ).'" class="page-discuss-courage">
												<span class="icon-talk icon-left"></span> discuss in rareCourage
											</a>
										</div>';
										
										$msg .= '
										<div class="post-facebook courage-social-cont share-item-inner fb_share_detail">
											<a  href="javascript:void(0)" title="share on Facebook" id="btnfbShare" data-detailurl="'.$post_link.'">
												<div  class="head_fb header_facebook_share  icon-left" style="cursor:pointer;">
													<i class="fa fa-facebook"></i> 
												</div>Facebook
											</a> 
										</div>';
										
										$msg .= '
										<div class="post-twitter courage-social-cont share-item-inner">
											<a data-href="https://twitter.com/intent/tweet?source=&text=:%20" title="share on Twitter" onclick="window.open(\'https://twitter.com/intent/tweet?text=\'+ encodeURIComponent(\''.trim( $post_title, 0,$twitter_text_limit ) . $truncated_title .'\') + \' on @'.$GLOBALS["onvoice_label"]["lbltwtonevoice"].' \' + encodeURIComponent(\''.$post_link.'\')); return false;">
												<div  class="head_tweet header_twitter_share  icon-left" style="cursor:pointer;">
													<i class="fa fa-twitter"></i>  
												</div>Twitter
											</a>
										</div>';
										$msg .= '
										<div  class="share-item-inner">
											<div  class="head_mailer_element" style="cursor:pointer;" title="share via email" data-detailurl="'.$post_link.'" data-check="video: '.$post_title.'">
												<div class="head_mailer icon-left"><i class="fa  fa-envelope"></i></div>
											email
											</div>
										</div>';
										
										$msg .= '
										<div class="share-team share-item share_with_rareteam share-item-inner" post="'.esc_attr( $post_id ).'" >
											<span class="share-team-icon icon-left"></span>share to rareTeam
										</div>';
									$msg .= '
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>';
		
		}
		
		
	} else {
		$msg =  '<label> no result found </label>';
	}
	
	
	return $msg;
	
}