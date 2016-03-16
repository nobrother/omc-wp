(function(global, doc, $){
	/**
	 * EVENT: Like
	 */
	$(function(){
		$('body').on('click.post', '.js-post-like', function(e){
			e.preventDefault();
			
			var self = $(this),
					post = self.closest('.js-post'),
					pid = post.data('id'),
					likeCount = post.find('.js-post-like-count'),
					likeCountVal = parseInt(likeCount.html()) || 0;

			if(!pid)
				return false;
			
			var data = {
				'action': 'omc_post_toggle_like',
				'pid': pid
			}
			
			// Request
			$.post(info.ajaxurl, data, function(response){
				//console.log(response);
			});
			
			// Toggle class
			if(self.toggleClass('active').is('.active'))
				likeCount.html(++likeCountVal);
			
			else{
				likeCount.html(--likeCountVal);
				/*
				if(--likeCountVal == 0)
					likeCount.html('');
				else
					likeCount.html(likeCountVal);
				*/
			}
		});
	})
})(window, document, jQuery);