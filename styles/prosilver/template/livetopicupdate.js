var titletag = document.title;
function livetopicupdate() {
    $.ajax({
	url: ltu_checkurl,
	type: 'get',
	success: function(result) {
	    if (result['ltu_yes']) {
		// New posts found
		$('#liveupdatebar').text(result['ltu_yes']).show();
                document.title = '[' + result['ltu_nr'] + '] ' + titletag;
	    }
	}
    });
}
setInterval(livetopicupdate, ltu_interval);

$('#liveupdatebar').click(function() {
    var lastpost = $(".postbody h3 a").last().attr('href');
    window.location.href = ltu_refreshurl  + lastpost;
})