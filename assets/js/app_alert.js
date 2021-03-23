/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

//build the object and save it the window
window["appAlert"] = (function ($) {
    //return appAlert object
    return (function () {
        //define container
        var appAlert = {
            //info method
            info: info,
            //success method
            success: success,
            //warning method
            warning: warning,
            //error method
            error: error,
            //args
            options: {
                container: "body", // append alert on the selector
                duration: 0, // don't close automatically,
                showProgressBar: true, // duration must be set
                clearAll: true, //clear all previous alerts
                animate: true //show animation
            }
        };
        //return the object
        return appAlert;
        //blue alert
        function info(message, options) {
            this._settings = _prepare_settings(options);
            this._settings.alertType = "info";
            _show(message);
            return "#" + this._settings.alertId;
        }

        //green alert
        function success(message, options) {
            this._settings = _prepare_settings(options);
            this._settings.alertType = "success";
            _show(message);
            return "#" + this._settings.alertId;
        }

        //orange alert
        function warning(message, options) {
            this._settings = _prepare_settings(options);
            this._settings.alertType = "warning";
            _show(message);
            return "#" + this._settings.alertId;
        }

        //red alert
        function error(message, options) {
            this._settings = _prepare_settings(options);
            this._settings.alertType = "error";
            _show(message);
            return "#" + this._settings.alertId;
        }

        /**
         * build the alert html
         * @param message
         * @returns {string}
         * @private
         */
        function _template(message) {
            //set the default type to info
            var className = "info";
            if (this._settings.alertType === "error") {
                className = "danger";
            } else if (this._settings.alertType === "success") {
                className = "success";
            } else if (this._settings.alertType === "warning") {
                className = "warning";
            }
            //set the animation
            if (this._settings.animate) {
                className += " animate";
            }
            //build the html
            return '<div id="'
                + this._settings.alertId
                + '" class="app-alert alert alert-'
                + className
                + ' alert-dismissible " role="alert">'
                + '<button type="button" class="close" '
                + 'data-dismiss="alert" aria-label="Close">'
                + '<span aria-hidden="true">&times;</span></button>'
                + '<div class="app-alert-message">' + message + '</div>'
                + '<div class="progress">'
                + '<div class="progress-bar progress-bar-'
                + className
                + ' hide" role="progressbar" aria-valuenow="60" '
                + 'aria-valuemin="0" aria-valuemax="100" style="width: 100%">'
                + '</div>'
                + '</div>'
                + '</div>';
        }

        /**
         * prepare the object settings
         * @param options
         * @returns {void|*}
         * @private
         */
        function _prepare_settings(options) {
            if (!options) options = {};
            //make an unique id to the alert box to control it
            options.alertId = "app-alert-" + getRandomString(5, "alphaNumeric");
            //return a merged object contain the appAlert options and the function options (arg)
            return this._settings = $.extend({}, appAlert.options, options);
        }

        /**
         * remove all the alerts from the screen
         * @private
         */
        function _clear() {
            if (this._settings.clearAll) {
                //any alert created buy this object will have the
                //attribute role='alert'
                $("[role='alert']").remove();
            }
        }

        /**
         * inject the alert html tag to the container
         * @param message
         * @private
         */
        function _show(message) {
            //check if clearAll enabled remove all previous alerts
            _clear();
            //set the container selector
            var container = $(this._settings.container);
            //get the container elements numbers
            if (container.length) {
                //if the animation option enabled
                if (this._settings.animate) {
                    //show animation
                    setTimeout(function () {
                        $(".app-alert").animate({
                            opacity: 1,
                            right: "40px"
                        }, 500, function () {
                            $(".app-alert").animate({
                                right: "15px"
                            }, 300);
                        });
                    }, 20);
                }
                //inject the alert html to head of the container
                $(this._settings.container).prepend(_template(message));
                _progressBarHandler();
            } else {
                console.log("appAlert: container must be an html selector!");
            }
        }

        /**
         * build the alert progress bar
         * @private
         */
        function _progressBarHandler() {
            //if settings have a duration and showProgressBar enabled
            if (this._settings.duration && this._settings.showProgressBar) {
                //set the selector
                var alertId = "#" + this._settings.alertId;
                //get the alert progress bar tag
                var $progressBar = $(alertId).find('.progress-bar');
                //remove hide class
                $progressBar.removeClass('hide').width(0);
                //make a transition css rule
                var css = "width " + this._settings.duration + "ms ease";
                $progressBar.css({
                    WebkitTransition: css,
                    MozTransition: css,
                    MsTransition: css,
                    OTransition: css,
                    transition: css
                });
                //remove the alert when the duration ended
                setTimeout(function () {
                    if ($(alertId).length > 0) {
                        $(alertId).remove();
                    }
                }, this._settings.duration);
            }
        }
    })();
})(jQuery);