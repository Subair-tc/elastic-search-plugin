$('document').ready( function(){
	
	$('.curate-readmore-new').live('click',function(){
	  $(this).prev('.evidence_content_view_all').css('display','block');
	  $(this).remove();
	});
	
});