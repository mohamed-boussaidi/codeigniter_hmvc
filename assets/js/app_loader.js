/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

//build the object and save it the window
window['appLoader'] = (function ($) {
    //return appLoader object
    return (function () {
        //define container
        var appLoader = {
            //show method
            show: show,
            //hide method
            hide: hide,
            //args
            options: {
                container: 'body',
                zIndex: "auto",
                css: ""
            }
        };
        //return the object
        return appLoader;
        /**
         * show an application spinner
         * @param options
         */
        function show(options) {
            //spinner container selector
            var $template = $("#app-loader");
            //build the appLoader settings
            this._settings = _prepare_settings(options);
            if (!$template.length) {
                var $container = $(this._settings.container);
                if ($container.length) {
                    $container.append('<div id="app-loader" class="app-loader" style="z-index:' + this._settings.zIndex + ';' + this._settings.css + '"><div class="loading"></div></div>');
                } else {
                    console.log("appLoader: container must be an html selector!");
                }

            }
        }

        /**
         * hide the application spinner
         */
        function hide() {
            //get the container selector
            var $template = $("#app-loader");
            //if the selector exists
            if ($template.length) {
                //remove it
                $template.remove();
            }
        }

        /**
         * build the object settings
         * @param options
         * @returns {void|*}
         * @private
         */
        function _prepare_settings(options) {
            //if there is no custom options init an empty object
            if (!options) options = {};
            //return the result of merging the default and custom settings
            return this._settings = $.extend({}, appLoader.options, options);
        }
    })();
})(jQuery);