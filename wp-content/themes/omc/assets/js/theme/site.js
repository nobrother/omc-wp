(function(global, doc, $){

	$(function(){
		$('.chat-app-wrap').one('touchstart click mouseenter', function(e){
			// Send ga event
			ga('send', 'event', 'tawk', 'access', 'Maybe start a chat');
			//console.log('tawk');
		});
		
		$('.gl-form-wrap').one('touchstart click mouseenter', function(e){
			// Send ga event
			ga('send', 'event', 'accessment', 'access', 'Maybe start to do accessment');
			//console.log('form');
		});		
	});
})(window, document, jQuery);