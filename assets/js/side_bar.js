/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

$(document).ready(function () {
    //get sidebar selector
    var sidebar = $("#sidebar");
    //expand or collapse sidebar menu
    $("#sidebar-toggle-md").click(function () {
        sidebar.toggleClass('collapsed');
        if (sidebar.hasClass("collapsed")) {
            $(this).find(".fa").removeClass("fa-dedent");
            $(this).find(".fa").addClass("fa-indent");
        } else {
            $(this).find(".fa").addClass("fa-dedent");
            $(this).find(".fa").removeClass("fa-indent");
        }
    });
    //collapse the sidebar
    $("#sidebar-collapse").click(function () {
        $("#sidebar").addClass('collapsed');
    });
    //expand or collapse sidebar menu items
    $("#sidebar-menu").closest(".expand").closest("a").click(function () {
        var $target = $(this).parent();
        if ($target.hasClass('main')) {
            if ($target.hasClass('open')) {
                $target.removeClass('open');
            } else {
                $("#sidebar-menu").closest(".expand").removeClass('open');
                $target.addClass('open');
            }
            if (!$(this).closest(".collapsed").length) {
                return false;
            }
        }
    });
    $("#sidebar-toggle").click(function () {
        $("body").toggleClass("off-screen");
        $("#sidebar").removeClass("collapsed");
    });
});