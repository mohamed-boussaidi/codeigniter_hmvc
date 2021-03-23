/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

(function ($) {
    //add appForm to jquery object
    $.fn.appForm = function (options) {
        //build the appForm object
        var defaults = {
            ajaxSubmit: true,
            isModal: true,
            dataType: "json",
            onModalClose: function () {
            },
            onSuccess: function () {
            },
            onError: function () {
                return true;
            },
            onSubmit: function () {
            },
            onAjaxSuccess: function () {
            },
            beforeAjaxSubmit: function (data, self, options) {
            }
        };
        //merge the passed options with default options
        var settings = $.extend({}, defaults, options);
        //for each instance of the appForm
        this.each(function () {
            //if this is an ajax submit
            if (settings.ajaxSubmit) {
                validateForm($(this), function (form) {
                    //execute onSubmit function
                    settings.onSubmit();
                    if (settings.isModal) {
                        maskModal($("#ajaxModalContent").find(".modal-body"));
                    }
                    //execute the form ajax submission
                    $(form).ajaxSubmit({
                        //default data type is json
                        dataType: settings.dataType,
                        //execute beforeAjaxSubmit function
                        beforeSubmit: function (data, self, options) {
                            settings.beforeAjaxSubmit(data, self, options);
                        },
                        //in case of request success
                        success: function (result) {
                            //execute onAjaxSuccess function
                            settings.onAjaxSuccess(result);
                            //if the success attribute == true
                            if (result.success) {
                                //send result to onSuccess
                                settings.onSuccess(result);
                                //close the modal
                                if (settings.isModal) {
                                    closeAjaxModal(true);
                                }
                                //else
                            } else {
                                //send the result to onError
                                if (settings.onError(result)) {
                                    //if the container is a modal
                                    if (settings.isModal) {
                                        //remove the loading spinner
                                        unmaskModal();
                                        //if the result contain message attribute
                                        if (result.message) {
                                            //trigger an appAlert with it inside the modal form
                                            appAlert.error(
                                                result.message,
                                                {container: '.modal-body', animate: false}
                                            );
                                        }
                                        //else
                                    } else if (result.message) {
                                        //trigger on the fly appAlert with the error message
                                        appAlert.error(result.message);
                                    }
                                }
                            }
                        }
                    });
                });
            } else {
                validateForm($(this));
            }
        });
        /**
         * execute the jquery form validation on the target form
         * @param form the form we want to validate
         * @param customSubmit execute custom js function instead of form submission
         * don't pass the 2nd parameter for regular form submission
         */
        function validateForm(form, customSubmit) {
            //add custom method
            $.validator.addMethod("greaterThanOrEqual",
                /**
                 * add greater then or equal to
                 * rule to the form validation plugin
                 * @param value
                 * @param element
                 * @param params
                 * @returns {boolean}
                 */
                function (value, element, params) {
                    $('#' + element.id).change(function () {
                        $('#' + element.id).blur();
                    });
                    var paramsVal = $(params).val();
                    if (isValidDate(value)) {
                        return formatDate(value) >= formatDate(paramsVal);
                    }
                    return false;
                }, 'Must be greater than {0}.');

            $.validator.addMethod('IPOrEmpty',
                /**
                 * check if the field is a valid IP address
                 * @param value
                 */
                function (value) {
                    if(value === "") return true;
                    return value.match(/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/);
            }, AppHelper.language.validIP);

            $.validator.addMethod('IP',
                /**
                 * check if the field is a valid IP address
                 * @param value
                 */
                function (value) {
                    return value.match(/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/);
                }, AppHelper.language.validIP);

            $.validator.addMethod(
                "formattedDate",
                function (date, element) {
                    return isValidDate(date);
                },
                AppHelper.language.validDate
            );
            $.validator.addMethod("alphanumericExtendedRequired", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9_\-]+$/.test(value);
            }, AppHelper.language.alphanumericExtendedRequired);
            $.validator.addMethod(
                "select2Required",
                function (value, element) {
                    return value.length > 0;
                },
                AppHelper.language.select2Required
            );
            //execute the validation
            $(form).validate({
                submitHandler: function (form) {
                    if (customSubmit) {
                        //execute submit function if exists
                        customSubmit(form);
                    } else {
                        //allow the form submission
                        return true;
                    }
                },
                //add error class
                highlight: function (element) {
                    //to the first .form-group
                    $(element).closest('.form-group').addClass('has-error');
                },
                //remove error class
                unhighlight: function (element) {
                    //from the first .form-group
                    $(element).closest('.form-group').removeClass('has-error');
                },
                //error container
                errorElement: 'span',
                //error class
                errorClass: 'help-block',
                //ignore hidden elements without validate-hidden class
                ignore: ":hidden:not(.validate-hidden)",
                //set error position
                errorPlacement: function (error, element) {
                    //if the element is inside .input-group
                    if (element.parent('.input-group').length) {
                        //inject the error after the .input-group
                        error.insertAfter(element.parent());
                    } else {
                        //else inject the error after the element
                        error.insertAfter(element);
                    }
                }
            });
            //handling the hidden field validation like select2
            $(".validate-hidden").click(function () {
                //do not show errors on an hidden input
                $(this).closest('.form-group').removeClass('has-error').find(".help-block").hide();
            });
        }

        /**
         * show loading mask on modal before form submission
         * @param $maskTarget
         */
        function maskModal($maskTarget) {
            var padding = $maskTarget.height() - 80;
            if (padding > 0) {
                padding = Math.floor(padding / 2);
            }
            //append spinner html to the container
            $maskTarget.append("<div class='modal-mask'><div class='circle-loader'></div></div>");
            //check scrollbar
            var height = $maskTarget.height();
            var $slimScrollDiv = $maskTarget.closest(".modal-content").find(".slimScrollDiv");
            if ($slimScrollDiv.length && $slimScrollDiv.find(".modal-body").length) {
                height = 20000;
                $slimScrollDiv.removeClass("slimScrollDiv").addClass("slimScrollDiv-deleted");
                $maskTarget.closest(".modal-content").find(".slimScrollBar").css({"z-index": "-1"});
            }
            $('.modal-mask').css({
                "width": $maskTarget.width() + 30 + "px",
                "height": height + "px",
                "padding-top": padding + "px"
            });
            //disable the submit button while loading
            $maskTarget.closest('.modal-dialog').find('[type="submit"]').attr('disabled', 'disabled');
        }

        /**
         * remove loading mask from modal
         */
        function unmaskModal() {
            var $maskTarget = $(".modal-body");
            $maskTarget.closest('.modal-dialog').find('[type="submit"]').removeAttr('disabled');
            $maskTarget.closest(".modal-content").find(".slimScrollDiv-deleted").removeClass("slimScrollDiv-deleted").addClass("slimScrollDiv");
            $maskTarget.closest(".modal-content").find(".slimScrollBar").css({"z-index": "auto"});
            $(".modal-mask").remove();
        }

        /**
         * close ajax modal and show success check mark
         * @param success
         */
        function closeAjaxModal(success) {
            if (success) {
                $(".modal-mask").html("<div class='circle-done'><i class='fa fa-check'></i></div>");
                setTimeout(function () {
                    $(".modal-mask").find('.circle-done').addClass('ok');
                }, 30);
            }
            setTimeout(function () {
                $(".modal-mask").remove();
                $("#ajaxModal").modal('toggle');
                settings.onModalClose();
            }, 1000);
        }
    };
})(jQuery);