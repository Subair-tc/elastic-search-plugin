<?php

get_header(); 


$search_host = '10.132.22.123';
$search_port = '9200';
$index = 'onescdvoice-content';
$doc_type = 'onescdvoice_posts';

global $wpdb;

$type = $_GET['content_type'];

onevoice_elastic_search_data_push( $search_host,$search_port,$index,$doc_type,$type );
 get_footer(); ?>