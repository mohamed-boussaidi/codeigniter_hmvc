/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

/** @namespace $.fn.slimscroll */

$(document).ready(function () {
    //set custom scrollbar
    setPageScrollable();
    setMenuScrollable();
    $(window).resize(function () {
        setPageScrollable();
        setMenuScrollable();
    });
});

//set slim scrollbar on page
setPageScrollable = function () {
    if ($.fn.slimscroll) {
        //don't apply slim scroll for mobile devices
        var height = $(window).height() - 45 + "px";
        if ($(window).width() <= 640) {
            $('html').css({"overflow": "auto"});
            $('body').css({"overflow": "auto"});
        } else {
            $('.scrollable-page').slimscroll({
                height: height,
                borderRadius: "0"
            });
        }
    }
};

//set slim scrollbar on left menu
setMenuScrollable = function () {
    if ($.fn.slimscroll) {
        $('#sidebar-scroll').slimscroll({
            height: $(window).height() - 45 + "px",
            borderRadius: "0"
        });
    }
};