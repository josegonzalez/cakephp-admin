$(document).ready(function() {
	$('.paging a').live('click', function (event) {
		var that = $(this);
		$.ajax({
			beforeSend:function (XMLHttpRequest) {
			  $('#content').fadeOut();
			},
			dataType: 'html',
			evalScripts:true,
			success:function (data, textStatus) {
				history.pushState({link: that.attr('href'), name: that.html()}, "", that.attr('href'));
				$('#content').html(data);
				$('#content').fadeIn();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('#content').fadeIn();
			},
			url: that.attr('href')
		});
		return false;
	});
});