(function(){var t=this;this.w=$(window),this.d=$(document),this.content=$("#site-content"),this.mid=$("#mid"),this.backgound=$("#background"),this.filter=$("#filter"),this.star=$("#star-album"),this.star_contaner=t.star.children(".item-container")}).call(define("dom")),function(){window.view_nav_left.fold=function(){return dom.mid.css("left",60),dom.content.css("padding-left",310),dom.backgound.css("padding-left",310),dom.filter.addClass("trans").removeClass("unfold")},window.view_nav_left.unfold=function(){return dom.mid.css("left",140),dom.content.css("padding-left",390),dom.backgound.css("padding-left",390),dom.filter.addClass("trans").addClass("unfold")}}.call(define("view_page_response")),function(){var t=this;this.filter_fixed=function(){var t=!!dom.filter.length&&dom.filter.offset().top;return function(){return dom.d.scrollTop()>t?dom.filter.addClass("fixed").removeClass("trans"):dom.filter.removeClass("fixed")}},this.scroll_event=function(){dom.w.scroll(t.filter_fixed())},!!dom.star&&t.scroll_event()}.call(define("view_home_page")),function(){global&&global.hasOwnProperty("uid")&&""==global.uid&&request.jsonp(global.platform.home+"/account/status",function(t){t.data&&t.data.login&&(location.href=global.platform.home+"/account/login?redirect="+encodeURIComponent(location.href))})}.call(define("controller_login_syn")),function(){var t=this,o=jQuery("#site-content"),e=jQuery(".loading-cover"),n=jQuery(".special-background");this.imgLoader=function(t,o){var e=new Image;e.onload=o,e.onerror=o,e.src=t},this.background=data.image,this.timer=setTimeout(function(){t.imgLoader(t.background,function(){n.css("background-image","url("+t.background+")"),setTimeout(function(){o.addClass("active"),e.addClass("disable")},800)})},0)}.call(define("data"));