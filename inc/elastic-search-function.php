<?php

function onevoice_elastic_search_function( $search_host,$search_port,$index_name,$query,$type ='' ){
	
	$baseUri = 'http://'.$search_host.':'.$search_port.'/'.$index_name;
	
	if ( $type ) {
		$baseUri .= '/'.$type;
	}
	
	$baseUri .='/_search';
	
	
	$ci = curl_init();
	curl_setopt($ci, CURLOPT_URL, $baseUri);
	curl_setopt($ci, CURLOPT_PORT, $search_port);
	curl_setopt($ci, CURLOPT_TIMEOUT, 20000);
	curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ci, CURLOPT_FORBID_REUSE, 0);
	curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ci, CURLOPT_POSTFIELDS, $query );
	$response = curl_exec($ci);
	//echo '<pre>';var_dump( $response );echo '</pre>';
	
	
	if( curl_error( $ci ) ){
		echo curl_error($ci );
		return;
	}
	curl_close( $ci );
	$response = json_decode( $response );
	
	return $response ;
	
	$return['count'] = $response->hits->total;
	$return['results'] = $response->aggregations->by_category->buckets;
	
	return $return;
	exit;
}