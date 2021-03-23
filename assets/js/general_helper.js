/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

/**
 * general application spinner
 */
$(window).on("load", function () {
    //remove the spinner after 250 ms
    $('#pre-loader').delay(250).fadeOut(function () {
        $('#pre-loader').remove();
    });
});

/**
 * format date to moment js valid date
 * @param date
 * @returns {*}
 */
function formatDate(date) {
    // put your own logic here, this is just a (crappy) example
    date = replaceAll("/", "-", date);
    date = replaceAll(".", "-", date);
    var format = AppHelper.settings.dateFormat.toUpperCase();
    format = replaceAll("/", "-", format);
    format = replaceAll(".", "-", format);
    return moment(date, format);
}

/**
 * check if a date is valid using moment js
 * @param date
 * @returns {*}
 */
function isValidDate(date) {
    return formatDate(date).isValid();
}

/**
 * generate random string
 * @param length
 * @param type
 * @returns {string}
 */
getRandomString = function (length, type) {
    var result = '';
    var chars = '';
    var alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var numeric = "0123456789";
    var symbols = "!-().*-/+$%#~|[]{}";
    switch (type) {
        case "alpha" : {
            chars = alpha;
            break;
        }
        case "numeric" : {
            chars = numeric;
            break;
        }
        case "symbols" : {
            chars = symbols;
            break;
        }
        case "alphaNumeric" : {
            chars = alpha + numeric;
            break;
        }
        case "alphaNumericSymbols" : {
            chars = alpha + numeric + symbols;
            break;
        }
        case "alphabet" : {
            chars = "abcdefghijklmnopqrstuvwxyz";
            break;
        }
        default : {
            chars = "!-().0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            break;
        }
    }
    for (var i = length; i > 0; --i)
        result += chars[Math.round(Math.random() * (chars.length - 1))];
    return result;
};

/**
 * execute check_linked_checkbox
 * on load and on change of the
 * main element
 * @param main
 * @param related
 * @param toExecute
 */
function linked_checkbox(main, related, toExecute) {
    var mainElement = $("#" + main);
    check_linked_checkbox(mainElement, related, toExecute);
    mainElement.change(function () {
        check_linked_checkbox(mainElement, related, toExecute);
    });
}

/**
 * if the main element is unchecked
 * disable each related element
 * toExecute is an array of functions
 * to execute after looping the related elements
 * @param mainElement
 * @param related
 * @param toExecute
 */
function check_linked_checkbox(mainElement, related, toExecute) {
    related.forEach(function (target) {
        var targetCheckBox = $("#" + target);
        if (mainElement.is(":checked")) {
            targetCheckBox.prop("disabled", false);
        } else {
            targetCheckBox.prop("disabled", true);
        }
    });
    toExecute.forEach(function (elementToExecute) {
        elementToExecute();
    });
}

/**
 * get formatted value depends on the dataType
 * @param option
 * @param currentValue
 * @returns {*}
 */
var formatCurrentValue = function (option, currentValue) {
    if (option.dataType === "currency") {
        return unformatCurrency(currentValue);
    } else if (option.dataType === "time") {
        return moment.duration(currentValue).asSeconds();
    } else {
        return currentValue;
    }
};

/**
 * calculate datatable column total
 * @param instance
 * @param option
 * @param currentPage
 * @returns {int|*}
 */
calculateDatatableTotal = function (instance, option, currentPage) {
    var api = instance.api(),
        columnNumber = option.column,
        columnOption = {};
    if (currentPage) {
        columnOption = {page: 'current'};
    }
    //if currentPage == true get only the rows of the current page
    //else get all the datatable columns
    return api.column(columnNumber, columnOption).data()
    //foreach row in the column
        .reduce(function (previousValue, currentValue) {
            if (formatCurrentValue) {
                return previousValue + formatCurrentValue(option, currentValue);
            } else {
                return previousValue + currentValue;
            }
        }, 0);
};



/**
 * remove the formatting to get integer data
 * @param currency
 * @returns {*}
 */
unformatCurrency = function (currency) {
    currency = currency.toString();
    if (currency) {
        currency = currency.replace(/[^0-9.,]/g, '');
        if (currency.indexOf(".") === 0 || currency.indexOf(",") === 0) {
            currency = currency.slice(1);
        }
        if (AppHelper.settings.decimalSeparator === ",") {
            currency = replaceAll(".", "", currency);
            currency = replaceAll(",", ".", currency);
        } else {
            currency = replaceAll(",", ".", currency);
        }
        currency = currency * 1;
    }
    if (currency) {
        return currency;
    }
    return 0;
};

/**
 * Regular expressions contain special (meta) characters,
 * and as such it is dangerous to blindly pass an argument
 * in the find function above without pre-processing it to escape those characters.
 * @param str
 * @returns {string|void|XML|Node|*}
 */
function escapeRegExp(str) {
    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

/**
 * replace all occurrences of a string
 * @param find
 * @param replace
 * @param str
 * @returns {void|XML|Node|string|*}
 */
function replaceAll(find, replace, str) {
    var re = new RegExp(escapeRegExp(find), 'g');
    return str.replace(re, replace);
}

/**
 * get formatted value depends on the dataType
 * @param total
 * @param option
 * @returns {string}
 */
function getDatatableFormattedTotal(total, option) {
    if (option.dataType === "currency") {
        return toCurrency(total, option.currencySymbol);
    } else if (option.dataType === "time") {
        return secondsToTimeFormat(total);
    }
}

/**
 * convert a number to currency format
 * @param number
 * @param currencySymbol
 * @returns {string}
 */
toCurrency = function (number, currencySymbol) {
    number = parseFloat(number).toFixed(2);
    if (!currencySymbol) {
        currencySymbol = AppHelper.settings.currencySymbol;
    }
    var result = number.replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    if (AppHelper.settings.decimalSeparator === ",") {
        result = replaceAll(".", "_", result);
        result = replaceAll(",", ".", result);
        result = replaceAll("_", ",", result);
    }
    if (currencySymbol === "none") {
        currencySymbol = "";
    }
    return currencySymbol + "" + result;
};

/**
 * convert seconds to hours:minutes:seconds format
 * @param sec
 * @returns {string}
 */
secondsToTimeFormat = function (sec) {
    var sec_num = parseInt(sec, 10),
        hours = Math.floor(sec_num / 3600),
        minutes = Math.floor((sec_num - (hours * 3600)) / 60),
        seconds = sec_num - (hours * 3600) - (minutes * 60);
    if (hours < 10) {
        hours = "0" + hours;
    }
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
    return hours + ':' + minutes + ':' + seconds;
};

/**
 * prepare html form data for suitable ajax submit
 * @param html
 * @returns {void|XML|Node|string|*|*}
 */
function encodeAjaxPostData(html) {
    html = replaceAll("=", "~", html);
    html = replaceAll("&", "^", html);
    return html;
}

checkNotifications = function (params, updateStatus, loop) {
    if (params && params.notificationUrl) {
        $.ajax({
            url: params.notificationUrl,
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    if (result.total_notifications && result.total_notifications * 1) {
                        params.notificationSelector.html("<i class='fa " + params.icon + "'></i> <span class='badge bg-danger up'>" + result.total_notifications + "</span>");
                    }

                    params.notificationSelector.parent().find(".dropdown-details").html(result.notification_list);

                    if (updateStatus) {
                        //update last notification checking time
                        $.ajax({
                            url: params.notificationStatusUpdateUrl,
                            success: function () {
                                params.notificationSelector.html("<i class='fa " + params.icon + "'></i>");
                            }
                        });
                    }
                }
                if (!updateStatus) {
                    //check notification again after sometime
                    var check_notification_after_every = params.checkNotificationAfterEvery;
                    check_notification_after_every = check_notification_after_every * 1000;
                    if (check_notification_after_every < 10000) {
                        check_notification_after_every = 10000; //don't allow to call this requiest before 10 seconds
                    }

                    if(loop) {
                        setTimeout(function () {
                            checkNotifications(params, false, true);
                        }, check_notification_after_every);
                    }
                }
            }
        });
    }
};
