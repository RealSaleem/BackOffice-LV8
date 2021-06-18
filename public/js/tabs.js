jQuery(document).ready(function() {
	// Standard
	jQuery('.tabs.standard .tab-links a').on('click', function(e)  {
		var currentAttrValue = jQuery(this).attr('href');

		// Show/Hide Tabs
		jQuery('.tabs ' + currentAttrValue).show().siblings().hide();

		// Change/remove current tab to active
		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

		e.preventDefault();
	});

	// Animated Fade
	jQuery('.tabs.animated-fade .tab-links a').on('click', function(e)  {
		var currentAttrValue = jQuery(this).attr('href');

		// Show/Hide Tabs
		jQuery('.tabs ' + currentAttrValue).fadeIn(400).siblings().hide();

		// Change/remove current tab to active
		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

		e.preventDefault();
	});

	// Animated Slide 1
	jQuery('.tabs.animated-slide-1 .tab-links a').on('click', function(e)  {
		var currentAttrValue = jQuery(this).attr('href');

		// Show/Hide Tabs
		jQuery('.tabs ' + currentAttrValue).siblings().slideUp(400);
		jQuery('.tabs ' + currentAttrValue).delay(400).slideDown(400);

		// Change/remove current tab to active
		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

		e.preventDefault();
	});

	// Animated Slide 2
	jQuery('.tabs.animated-slide-2 .tab-links a').on('click', function(e)  {
		var currentAttrValue = jQuery(this).attr('href');

		// Show/Hide Tabs
		jQuery('.tabs' + currentAttrValue).slideDown(400).siblings().slideUp(400);

		// Change/remove current tab to active
		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

		e.preventDefault();
	});

	var currentController = window.location.pathname.split('/'); 
	// console.log(currentController);

	if( currentController[2] == 'sale' ){
		jQuery(".navbar-btn").click(function(){
			jQuery("#aside").toggleClass("hideSidebar");
			jQuery("#content").toggleClass("content-margin");
			jQuery(".navbar-brand").toggleClass("logo-hide");
			jQuery(".navbar-collapse").toggleClass("content-margin");
			window.setTimeout(function() {
				// jQuery("#sidebarspecialdiv").removeClass("app-aside-folded");
				jQuery(".app").removeClass("app-aside-folded");
			}, 10);
		});
		jQuery(window).load(function(){
			jQuery("#aside").toggleClass("hideSidebar");
			jQuery("#content").toggleClass("content-margin");
			jQuery(".navbar-brand").toggleClass("logo-hide");
			jQuery(".navbar-collapse").toggleClass("content-margin");
		})
	}
	
});