/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

/**
 * build a bootstrap date picker
 * @param element
 * @param options
 */
setDatePicker = function (element, options) {
    //if no custom options passed define an empty object
    if (!options) options = {};
    //merge the custom options with the defaults into settings
    var settings = $.extend({}, {
        autoclose: true,
        todayHighlight: true,
        weekStart: AppHelper.settings.firstDayOfWeek,
        format: AppHelper.settings.dateFormat,
        locale: AppHelper.settings.locale,
        language: AppHelper.settings.language
    }, options);
    //trigger the bootstrap date picker
    $(element).datepicker(settings);
};

/**
 * build a bootstrap time picker
 * @param element
 * @param options
 */
setTimePicker = function (element, options) {
    //if no custom options passed define an empty object
    if (!options) options = {};
    //12hr or 24hr mode
    var showMeridian = "24_hours";
    //merge the custom options with the defaults into settings
    var settings = $.extend({}, {
        minuteStep: 5,
        defaultTime: "",
        showMeridian: showMeridian
    }, options);
    //trigger the bootstrap time picker
    $(element).timepicker(settings);
};

memberSelect2Format = function (option) {
    return "<i class='fa fa-user'></i> " + option.text;
};

fileSelect2Format = function (option) {
    var icon = option.text.toLowerCase();
    switch (icon) {
        case 'png' : {icon = 'photo'; break;}
        case 'jpeg' : {icon = 'photo'; break;}
        case 'gif' : {icon = 'photo'; break;}
        case 'jpg' : {icon = 'photo'; break;}
        case 'svg' : {icon = 'photo'; break;}
        case 'bmp' : {icon = 'photo'; break;}
        case 'rar' : {icon = 'archive'; break;}
        case 'tar' : {icon = 'archive'; break;}
        case 'gz' : {icon = 'archive'; break;}
        case 'zip' : {icon = 'archive'; break;}
        case 'doc' : {icon = 'word'; break;}
        case 'docx' : {icon = 'word'; break;}
        case 'ppt' : {icon = 'powerpoint'; break;}
        case 'pptx' : {icon = 'powerpoint'; break;}
        case 'txt' : {icon = 'text'; break;}
        case 'js' : {icon = 'code'; break;}
        case 'php' : {icon = 'code'; break;}
        case 'c' : {icon = 'code'; break;}
        case 'cpp' : {icon = 'code'; break;}
        case 'py' : {icon = 'code'; break;}
        case 'rb' : {icon = 'code'; break;}
        case 'css' : {icon = 'code'; break;}
        case 'mp3' : {icon = 'audio'; break;}
        case 'mpeg-3' : {icon = 'audio'; break;}
        case 'aac' : {icon = 'audio'; break;}
        case '3gp' : {icon = 'audio'; break;}
        case 'amr' : {icon = 'audio'; break;}
        case 'wav' : {icon = 'audio'; break;}
        case 'xls' : {icon = 'excel'; break;}
        case 'csv' : {icon = 'excel'; break;}
        case 'mov' : {icon = 'video'; break;}
        case 'mp4' : {icon = 'video'; break;}
        case 'mpeg' : {icon = 'video'; break;}
        case 'wmv' : {icon = 'video'; break;}
        default : {icon = '-'; break;}
    }
    icon = icon === '-' ? "fa fa-file-o" : "fa fa-file-" + icon + "-o";
    return "<i class='" + icon + "'></i> " + option.text;
};

roleSelect2Format = function (option) {
    return "<i class='fa fa-group'></i> " + option.text;
};

typeSelect2Format = function (option) {
    return "<i class='fa fa-cog'></i> " + option.text;
};