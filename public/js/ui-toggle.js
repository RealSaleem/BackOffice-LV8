+function ($) {

  $(function(){

      $(document).on('click', '[ui-toggle-class]', function (e) {
        //return;
        e.preventDefault();
        var $this = $(e.target);
        $this.attr('ui-toggle-class') || ($this = $this.closest('[ui-toggle-class]'));
        
		var classes = $this.attr('ui-toggle-class').split(','),
			targets = ($this.attr('target') && $this.attr('target').split(',')) || Array($this),
			key = 0;
		$.each(classes, function( index, value ) {
			var target = targets[(targets.length && key)];
			$( target ).toggleClass(classes[index]);
			key ++;
		});
		$this.toggleClass('active');

      });
  });
}(jQuery);


// $('.top1').on('click', function() {
// 	$parent_box = $(this).closest('.box1');
// 	$parent_box.siblings().find('.bottom1').slideUp();
// 	$parent_box.find('.bottom1').slideToggle(400, 'swing');
// });


$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 0;  // How many characters are shown by default
    var ellipsestext = "";
    var moretext = "More Filters >";
    var lesstext = "Less Filter";
    

    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});










$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 0;  // How many characters are shown by default
    var ellipsestext = "";
    var moretext = "View cash movements breakdown";
    var lesstext = "Hide cash movements breakdown ";
    

    $('.moreC').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelinkC">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelinkC").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});



$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 0;  // How many characters are shown by default
    var ellipsestext = "";
    var moretext = "View Original Sale";
    var lesstext = "Hide Original Sale";
    

    $('.moretable').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="orginalsaletable">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".orginalsaletable").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});




$(".tagsremoval").click(function(){
    $(".removetags").hide();
});

 $(".discountpopup").hide();
$(".closer").click(function(){
    $(".discountpopup").hide();
});

$(".discountpro").click(function(){
    $(".discountpopup").show();
});


$(function() {

  var timeout;

  $("#search").keyup(function() {
    clearTimeout(timeout);
    
    timeout = setTimeout(function() {
      $("#result").text('You searched for ' + $("#search").val());
    }, 500);

  });


});




$(".exit").click(function(){
    $(".modal-backdrop ").hide();
});


 $(".gotitD").hide();
$(".exit").click(function(){
    $(".gotitD").hide();
});



$(".gotit").click(function(){
    $(".gotitD").show();
});
$(".gotit").click(function(){
    $("#myModalTraining").hide();
});

 $(".copycontent").hide();
$(".copy-success").click(function(){
    $(".copycontent").show();
	setTimeout(function() {
  $('.copycontent').fadeOut('fast');
}, 1000); // <-- time in milliseconds
});

setTimeout(function() {
  $('.copycontent').fadeOut('fast');
}, 1000); // <-- time in milliseconds


$(".unabletocopy").hide();
$(".delete_layout").click(function(){
    $(".unabletocopy").show();
	setTimeout(function() {
  $('.unabletocopy').fadeOut('fast');
}, 1000); // <-- time in milliseconds
});

setTimeout(function() {
  $('.unabletocopy').fadeOut('fast');
}, 1000); // <-- time in milliseconds


$(".closerX").click(function(){
    $("#filterdiv").hide();
});


$("#filterdiv").hide();
$('#filterbtn').on('click', function(e){

    $("#filterdiv").toggle();
    $(this).toggleClass('class1')
});

$(document).ready(function(){
        $("#").click(function(){
            $(this).text($(this).text() == 'Remove Filter' ? ' Filter' : 'Remove Filter');
        });
    });
	
	
	$('html').click(function() {
    $('.checkboxlinks').hide();
 })

 $('#footleft').click(function(e){
     e.stopPropagation();
	
 });

  $('#dropdown-menu020').click(function(e){
     e.stopPropagation();
	
 });


 
$('.checkboxopener').click(function(e) {
 $('.checkboxlinks').toggle();
 });

 $('.checkboxlinks').hide();

 
 $('.broswebutton').click(function(){
    $('.noneinput').click();
});





$(".delete-attr").click(function(){
    $(".attr2").hide();
});

$(".delete-attr4").click(function(){
    $(".vdetail").hide();
});

$(".delete-attr5").click(function(){
    $(".vdetail2").hide();
});



$(".remove-added").click(function(){
    $(".added").hide();
});


$(".select-div").hide();
$(".addsalestaxbox").click(function(){
    $(".select-div").show();
	 $(".addsalestaxbox").hide();
});


$(".closethis").click(function(){
   $(".itemlist").hide();
});


$(".salecloser").click(function(){
    $(".select-div").hide();
	$(".addsalestaxbox").show();
});

//var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels2");

$(function(){

    $('.spinner .btn:first-of-type').on('click', function() {
      var btn = $(this);
      var input = btn.closest('.spinner').find('input');
      if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {    
        input.val(parseInt(input.val(), 10) + 1);
      } else {
        btn.next("disabled", true);
      }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
      var btn = $(this);
      var input = btn.closest('.spinner').find('input');
      if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {    
        input.val(parseInt(input.val(), 10) - 1);
      } else {
        btn.prev("disabled", true);
      }
    });

})

var myCalendar;
		function doOnLoad() {
			myCalendar = new dhtmlXCalendarObject("calendarHere");
			myCalendar.setDate(new Date(2014,5,1,16,0));
			myCalendar.show();
			myCalendar = new dhtmlXCalendarObject("calendarHere2");
			myCalendar.setDate(new Date(2014,5,1,16,0));
			myCalendar.show();
		}
		
		
  // $( function() {
  //  $( "#datepicker" ).datepicker();
  // });
		
		function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent2");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

