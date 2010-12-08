$(document).ready(function(){
	MobileSkin.init();
	MobileSkin.initTracking();
});

var MobileSkin = {
	uacct: "UA-2871474-1",
	username: (wgUserName == null) ? 'anon' : 'user',
	ct: {},
	c: null,
	h: null,
	b: null,
	
	initTracking: function(){
		MobileSkin.trackEvent(MobileSkin.username + '/view');
		
		$('#mobile-search-btn').bind('click', function(event){
			MobileSkin.trackClick('search');
		});
		
		$('a').bind('click', function(event){
			var elm = $(this);
			var href = $(this).attr('href');
			
			if(href && href.indexOf(CategoryNamespaceMessage) !== -1) MobileSkin.trackClick('categorylink');
			else if(href && href.indexOf(SpecialNamespaceMessage) === -1) MobileSkin.trackClick('contentlink');
			else if(elm.attr('data-id') === 'randompage') MobileSkin.trackClick('randompage');
			else if(elm.hasClass('showbutton')) MobileSkin.trackClick('showhide');
		});
	},
	
	trackClick: function(eventName){
		MobileSkin.trackEvent(MobileSkin.username + '/click/' + eventName);
	},
	
	trackEvent: function(eventName) {
		var eventToTrack = '/1_mobile/' + eventName;
		
		if(typeof urchinTracker !== 'undefined') {
			_uff = 0;
			_uacct = MobileSkin.uacct;
			urchinTracker(eventToTrack);
			if(wgPrivateTracker) {
				eventToTrack = '/1_mobile/' + wgDB + '/' + eventName;
				urchinTracker(eventToTrack);
			}
		}
	},
	
	init: function(){
		MobileSkin.c = $("#bodyContent");
		MobileSkin.h = MobileSkin.c.find(">h2");
		
		var cindex = -1;
		MobileSkin.c.contents().each(function(i, el) {
			if (this) {
				if (this.nodeName == 'H2') {
					$(this).append('<a class="showbutton">Show</a>');
					cindex++;
					MobileSkin.ct["c"+cindex] = [];
				} else if (this.id != 'catlinks' && this.id != 'mw-data-after-content' && cindex > -1) {
					MobileSkin.ct["c"+cindex].push(this);
					$(this).remove();
				}
			}
		});
		
		MobileSkin.b = MobileSkin.h.find(".showbutton");
		
		MobileSkin.b.each(function(i, el) {
			$(el).data("c", "c" + i);
		});
		
		MobileSkin.b.click(MobileSkin.toggle);
		
		$("#fullsite a").click(function(e){
			e.preventDefault();
			document.cookie = 'mobilefullsite=true';
			location.reload();
		});
	},
	
	toggle: function(e) {
		e.preventDefault();
		
		if($(this).data("s")) {
			$(MobileSkin.ct[$(this).data("c")]).remove();
			$(this).data("s", false);
			$(this).text("Show");
		} else {
			$(this).closest("h2").after($(MobileSkin.ct[$(this).data("c")]));
			$(this).data("s", true);
			$(this).text("Hide");
		}
	}
};