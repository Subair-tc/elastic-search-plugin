<?php

function onevoice_elastic_create_index( $search_host,$search_port,$index_name,$type,$mapping ){
	
	$baseUri = 'http://'.$search_host.':'.$search_port.'/'.$index_name.'/'.$type.'/_mapping';
	
	$ci = curl_init();
	curl_setopt($ci, CURLOPT_URL, $baseUri);
	curl_setopt($ci, CURLOPT_PORT, $search_port);
	curl_setopt($ci, CURLOPT_TIMEOUT, 20000);
	curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ci, CURLOPT_FORBID_REUSE, 0);
	curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ci, CURLOPT_POSTFIELDS, $mapping);
	$response = curl_exec($ci);
	echo '<pre>';var_dump( $response );echo '</pre>';
	if( curl_error( $ci ) ){
		echo curl_error($ci );
	}
	
	curl_close( $ci );
	exit;


}