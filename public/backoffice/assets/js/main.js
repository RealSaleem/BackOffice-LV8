

 // Left Side Menu

/* Set the width of the side navigation to 250px */

function openNav() {

document.getElementById("mySidenav").style.width = "270px";

}



/* Set the width of the side navigation to 0 */

function closeNav() {

document.getElementById("mySidenav").style.width = "0";

}



(function($) {

	$.fn.vmenuModule = function(option) {

		var obj,

			item;

		var options = $.extend({

				Speed: 220,

				autostart: true,

				autohide: 1

			},

			option);

		obj = $(this);



		item = obj.find("ul").parent("li").children("a");

		item.attr("data-option", "off");



		item.unbind('click').on("click", function() {

			var a = $(this);

			if (options.autohide) {

				a.parent().parent().find("a[data-option='on']").parent("li").children("ul").slideUp(options.Speed / 1.2,

					function() {

						$(this).parent("li").children("a").attr("data-option", "off");

					})

			}

			if (a.attr("data-option") == "off") {

				a.parent("li").children("ul").slideDown(options.Speed,

					function() {

						a.attr("data-option", "on");

					});

			}

			if (a.attr("data-option") == "on") {

				a.attr("data-option", "off");

				a.parent("li").children("ul").slideUp(options.Speed)

			}

		});

		if (options.autostart) {

			obj.find("a").each(function() {



				$(this).parent("li").parent("ul").slideDown(options.Speed,

					function() {

						$(this).parent("li").children("a").attr("data-option", "on");

					})

			})

		}



	}

})(window.jQuery || window.Zepto);









$(document).ready(function() {

				$(".u-vmenu").vmenuModule({

					Speed: 500,

					autostart: false,

					autohide: false

				});

});





 

//popuphover



 $(function(){

  $('[data-toggle="popover"]').popover()

});



 $('.popover-dismiss').popover({

  trigger: 'focus'

})







 // icons 

  //feather.replace()

$("#table_products").hide();

$("#show_table").click(function(){

$("#table_products").toggle();

});



$("#selected_products_detail").hide();

$("#selected_products").click(function(){

$("#selected_products_detail").toggle();

});



$(".infohide").hide();

$(".infoopen").click(function(){

$(".infohide").toggle();

});



$(".infohide22").hide();

$(".infoopen2").click(function(){

$(".infohide22").toggle();

});





$("#search_customer").hide();

$("#search_customer_con").click(function(){

$("#search_customer").toggle();

$(".closer").hide();

});





$(".closer1").click(function(){

$("#search_customer").hide();

});





document.getElementById('main').addEventListener('click', closepushNav);



function openpushNav() {

  document.getElementById("mySidenavSetting").style.width = "250px";

  document.getElementById("main").style.marginLeft = "250px";

  //document.body.style.backgroundColor = "rgba(0,0,0,0.4)";

}



function closepushNav() {

  //document.getElementById("mySidenavSetting").style.width = "0";

  document.getElementById("main").style.marginLeft= "0";

  //document.body.style.backgroundColor = "white";

}



