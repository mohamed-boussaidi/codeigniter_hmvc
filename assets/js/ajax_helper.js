/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

$(document).ready(function () {
    //disable ajax caching
    $.ajaxSetup({cache: false});
    //make an ajax modal request container
    var ajaxModalXhr;
    //make an ajax request container
    var ajaxRequestXhr;
    //set the body selector to catch clicks
    var bodySelector = $('body');
    //catch the ajax modal clicks
    bodySelector.on('click', '[data-act=ajax-modal]', function () {
        //set this element selector
        var thisElement = $(this);
        //container for the posted data
        var data = {};
        //get the request url
        var url = thisElement.attr('data-action-url');
        //check if the modal should be large one
        var isLargeModal = $(this).attr('data-modal-lg');
        //get the modal title
        var title = thisElement.attr('data-title');
        //modal title selector
        var ajaxModalTitle = $("#ajaxModalTitle");
        //if there is no title, get the application name
        title ? ajaxModalTitle.html(title) :
            ajaxModalTitle.html(ajaxModalTitle).attr('data-title');
        //remove hidden from the #ajaxModal
        $("#ajaxModal").modal('show');
        //for each attribute grabbed from the trigger
        thisElement.each(function () {
            $.each(this.attributes, function () {
                //push the data-post element to the data array to post them
                if (this.specified && this.name.match("^data-post-")) {
                    var dataName = this.name.replace("data-post-", "");
                    data[dataName] = this.value;
                }
            });
        });
        //execute the ajax post request
        ajaxModalXhr = $.ajax({
            //set the request url
            url: url,
            //set the post data
            data: data,
            //set the request type to POST
            type: 'POST',
            //if the request executed successfully
            success: function (response) {
                var modalSelector = $("#ajaxModal");
                //modal opening animation
                modalSelector.find(".modal-dialog").removeClass("mini-modal");
                if (isLargeModal === "1") {
                    modalSelector.find(".modal-dialog").addClass("modal-lg");
                }
                //ajax modal content selector
                var ajaxModalContent = $("#ajaxModalContent");
                //send the response to modal body
                ajaxModalContent.html(response);
                //get the modal body selector to set the scroll inside the modal body
                var $scroll = ajaxModalContent.find(".modal-body");
                //get the modal height
                var height = $scroll.height();
                //set the modal max height to modal height - header height (200px)
                var maxHeight = $(window).height() - 200;
                //if the height > max height shrink it
                if (height > maxHeight) {
                    height = maxHeight;
                    //run the slim scroll plugin
                    $scroll.slimscroll({
                        //make the scroll bar always visible
                        alwaysVisible: true,
                        //set the height format
                        height: height + "px",
                        //set the scroll bar color
                        color: "#98a6ad",
                        //set border radius to 0
                        borderRadius: "0"
                    });
                }
            },
            //catch the request result status code
            statusCode: {
                //if the code is 404 then the action url not fount
                404: function () {
                    //remove the modal content
                    $("#ajaxModalContent").find('.modal-body').html("");
                    //show an alert inside the body of the modal
                    appAlert.error("404: Page not found.", {container: '.modal-body', animate: false});
                }
            },
            //if the request ended with an error
            error: function () {
                //remove the modal content
                $("#ajaxModalContent").find('.modal-body').html("");
                //show an alert inside the body of the modal
                appAlert.error("500: Internal Server Error.", {container: '.modal-body', animate: false});
            }
        });
        return false;
    });
    //abort ajax request on modal close.
    $('#ajaxModal').on('hidden.bs.modal', function () {
        ajaxModalXhr.abort();
        var modalSelector = $("#ajaxModal");
        modalSelector.find(".modal-dialog").removeClass("modal-lg");
        modalSelector.find(".modal-dialog").addClass("mini-modal");
        $("#ajaxModalContent").html("");
    });
    //catch the ajax request clicks
    bodySelector.on('click', '[data-act=ajax-request]', function () {
        //init the data container
        var data = {};
        //set this element selector
        var thisElement = $(this);
        //set the action url
        var url = thisElement.attr('data-action-url');
        //elements to remove on success
        var removeOnSuccess = thisElement.attr('data-remove-on-success');
        //elements to remove on click
        var removeOnClick = thisElement.attr('data-remove-on-click');
        //loader container
        var inlineLoader = thisElement.attr('data-inline-loader');
        //reload the page on success
        var reloadOnSuccess = thisElement.attr('data-reload-on-success');
        //init the target container
        var $target = "";
        //if there is a data real target make it the result container
        if (thisElement.attr('data-real-target')) {
            $target = $(thisElement.attr('data-real-target'));
            //else take the closest element with data closes target attribute
        } else if (thisElement.attr('data-closest-target')) {
            $target = thisElement.closest(thisElement.attr('data-closest-target'));
        }
        //remove elements marked to remove on click
        if (removeOnClick) {
            $(removeOnClick).remove();
        }
        //for each attribute grabbed from the trigger
        thisElement.each(function () {
            $.each(this.attributes, function () {
                //push the data-post element to the data array to post them
                if (this.specified && this.name.match("^data-post-")) {
                    var dataName = this.name.replace("data-post-", "");
                    data[dataName] = this.value;
                }
            });
        });
        //if the inline loader enabled
        if (inlineLoader === "1") {
            //add the inline-loader class to the trigger
            thisElement.addClass("inline-loader");
        } else {
            //else show page loader
            appLoader.show();
        }
        //execute the ajax post request
        ajaxRequestXhr = $.ajax({
            //set action url
            url: url,
            //set posted data
            data: data,
            //set the request type to POST
            type: 'POST',
            //if the request executed successfully
            success: function (response) {
                //if reload on success enabled
                if (reloadOnSuccess) {
                    //reload the current page
                    location.reload();
                }
                //if remove on success enabled
                if (removeOnSuccess) {
                    //remove the marked elements
                    $(removeOnSuccess).remove();
                }
                //hide the page loader
                appLoader.hide();
                //if the target is a valid html tag
                if ($target.length) {
                    //append the result to it
                    $target.html(response);
                }
            },
            //catch the request status code
            statusCode: {
                //if the code is 404 then the action url is not found
                404: function () {
                    //hide the page loader
                    appLoader.hide();
                    //alert an error message
                    appAlert.error("404: Page not found.");
                }
            },
            //if the request ended with an error
            error: function () {
                //hide the page loader
                appLoader.hide();
                //alert an error message
                appAlert.error("500: Internal Server Error.");
            }
        });
    });
    //catch ajax tab click
    bodySelector.on('click', '[data-toggle="ajax-tab"] a', function () {
        //set the trigger selector
        var thisElement = $(this);
        //set the tab source url
        var url = thisElement.attr('href');
        //set the target element
        var target = thisElement.attr('data-target');
        //if the target tag empty
        if ($(target).html() === "") {
            //show the page loader
            appLoader.show({container: target, css: "right:50%; bottom:auto;"});
            //get the url content
            $.get(url, function (data) {
                //append the result to the tag
                $(target).html(data);
            });
        }
        //show the tab
        thisElement.tab('show');
        return false;
    });
    //auto load first tab
    $('[data-toggle="ajax-tab"] a').first().trigger("click");
});
