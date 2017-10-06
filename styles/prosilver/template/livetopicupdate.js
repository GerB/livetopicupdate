function livetopicupdate() {
    $.ajax({
	url: ltu_checkurl,
	type: 'get',
	success: function(result) {
	    if (result['ltu_yes']) {
		// New posts found
		$('#liveupdatebar').text(result['ltu_yes']).show();
	    }
	}
    });
}
setInterval(livetopicupdate, 5000);

$('#liveupdatebar').click(function() {
    var lastpost = $(".postbody h3 a").last().attr('href');
    window.location.href = ltu_refreshurl  + lastpost;
})