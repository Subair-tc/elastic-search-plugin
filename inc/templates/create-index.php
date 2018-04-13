<?php
get_header(); 
$search_host = '10.132.22.123';
$search_port = '9200';
$index_name = 'onescdvoice-content';

$type = $_GET['content_type'];


if( $type == 'UGC_contents' ) {
	$mapping  = '{
		"UGC_contents": {
			"properties": {
				"activity_tags": {
					"type": "string",
					"index": "analyzed",
					"fields": {
						"tag_id": {
						"type": "string",
						"index": "not_analyzed"
						}
					}
				},
				"user_id": {
					"type": "string",
					"index": "analyzed",
					"fields": {
						"user": {
						"type": "long",
						"index": "not_analyzed"
						}
					}
				}
				
			}
		}
	}';
	
}  elseif( $type == 'evidance-education' ) {
	$mapping  = '{
		"evidance-education": {
			"properties": {
				"evidance_category_slug": {
					"type": "string",
					"index": "analyzed",
					"fields": {
						"slug": {
							"type": "string",
							"index": "not_analyzed"
						}
					}
				}
			}
		}
	}';
	
	
}  elseif( $type == 'fundraising' ) {
	$mapping  = '{
	"fundraising": {
		"properties": {
			"fundraising_category_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			},
			"fundraising_category_parent_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
} elseif( $type == 'media-social' ) {
	$mapping  = '{
	"media-social": {
		"properties": {
			"social_category_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			},
			"social_category_parent_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
} elseif( $type == 'news-meeting' ) {
	$mapping  = '{
	"news-meeting": {
		"properties": {
			"news_place_type_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
} elseif( $type == 'people' ) {
	$mapping  = '{
	"people": {
		"properties": {
			"person_type_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
} elseif( $type == 'place' ) {
	$mapping  = '{
	"place": {
		"properties": {
			"place_type_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
}  elseif( $type == 'photo' ) {
	$mapping  = '{
	"photo": {
		"properties": {
			"photo_category_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			},
			"photo_category_parent_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
			
		}
    }
	}';
}	elseif( $type == 'website' ) {
	$mapping  = '{
	"website": {
		"properties": {
			"website_category_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			},
			"website_category_parent_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
} 	elseif( $type == 'video-visual' ) {
	$mapping  = '{
	"video-visual": {
		"properties": {
			"video_category_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
} elseif( $type == 'rarehub' ) {
	$mapping  = '{
	"rarehub": {
		"properties": {
			"clinical_category_slug": {
				"type": "string",
				"index": "analyzed",
				"fields": {
					"slug": {
					"type": "string",
					"index": "not_analyzed"
					}
				}
			}
		}
    }
	}';
} else{
		echo 'Error 404';
		exit;
}
onevoice_elastic_create_index( $search_host,$search_port,$index_name,$type,$mapping );

 get_footer(); 
?>