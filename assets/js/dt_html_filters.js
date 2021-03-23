function dt_html_filters(thisTable, settings, options, thisTableWrapper, prepareDefaultDateRangeFilterParams, getWeekRange) {
    //build date wise filter selectors
    if (settings.dateRangeType) {
        var dateRangeFilterDom = '<div class="mr15 DTTT_container">'
            + '<button data-act="prev" class="btn btn-default date-range-selector"><i class="fa fa-chevron-left"></i></button>'
            + '<button data-act="datepicker" class="btn btn-default" style="margin: -1px"></button>'
            + '<button data-act="next"  class="btn btn-default date-range-selector"><i class="fa fa-chevron-right"></i></button>'
            + '</div>';
        thisTableWrapper.find(".custom-toolbar").append(dateRangeFilterDom);
        var datepicker = thisTableWrapper.find("[data-act='datepicker']"),
            dateRangeSelector = thisTableWrapper.find(".date-range-selector");
        //init single day selector
        if (settings.dateRangeType === "daily") {
            /**
             * build date picker days navigation text
             * @param selector
             */
            var initSingleDaySelectorText = function (selector) {
                //if start date equal to current date
                if (settings.filterParams.start_date === moment().format(settings._inputDateFormat)) {
                    //set word to today
                    selector.html(settings.customLanguage.today);
                    //if start date equal to current date - 1
                } else if (settings.filterParams.start_date ===
                    moment().subtract(1, 'days').format(settings._inputDateFormat)) {
                    //set word to yesterday
                    selector.html(settings.customLanguage.yesterday);
                    //if start date equal to current date + 1
                } else if (settings.filterParams.start_date ===
                    moment().add(1, 'days').format(settings._inputDateFormat)) {
                    //set word to tomorrow
                    selector.html(settings.customLanguage.tomorrow);
                } else {
                    selector.html(moment(settings.filterParams.start_date).format("Do, MMMM YYYY"));
                }
            };
            prepareDefaultDateRangeFilterParams();
            initSingleDaySelectorText(datepicker);
            //bind the click events
            datepicker.datepicker({
                format: settings._inputDateFormat,
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function (e) {
                var date = moment(e.date).format(settings._inputDateFormat);
                //set the start and the end date to the picked one
                settings.filterParams.start_date = date;
                settings.filterParams.end_date = date;
                //date picker days navigation buttons
                initSingleDaySelectorText(datepicker);
                //reload the table
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
            dateRangeSelector.click(function () {
                //get the type and update the date depends on it
                var type = $(this).attr("data-act"), date = "";
                if (type === "next") {
                    date = moment(settings.filterParams.start_date).add(1, 'days').format(settings._inputDateFormat);
                } else if (type === "prev") {
                    date = moment(settings.filterParams.start_date).subtract(1, 'days').format(settings._inputDateFormat)
                }
                //update dates
                settings.filterParams.start_date = date;
                settings.filterParams.end_date = date;
                //update date picker text
                initSingleDaySelectorText(datepicker);
                //reload the tables with the new filters
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
        }
        //init month selector
        if (settings.dateRangeType === "monthly") {
            prepareDefaultDateRangeFilterParams();
            datepicker.html(moment(settings.filterParams.start_date).format("MMMM YYYY"));
            //bind the click events
            datepicker.datepicker({
                format: "YYYY-MM",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true
            }).on('changeDate', function (e) {
                var date = moment(e.date).format(settings._inputDateFormat);
                var daysInMonth = moment(date).daysInMonth(),
                    yearMonth = moment(date).format("YYYY-MM");
                settings.filterParams.start_date = yearMonth + "-01";
                settings.filterParams.end_date = yearMonth + "-" + daysInMonth;
                datepicker.html(moment(settings.filterParams.start_date).format("MMMM YYYY"));
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
            dateRangeSelector.click(function () {
                var type = $(this).attr("data-act"),
                    startDate = moment(settings.filterParams.start_date),
                    endDate = moment(settings.filterParams.end_date);
                if (type === "next") {
                    startDate = startDate.add(1, 'months').format(settings._inputDateFormat);
                    endDate = endDate.add(1, 'months').format(settings._inputDateFormat);
                } else if (type === "prev") {
                    startDate = startDate.subtract(1, 'months').format(settings._inputDateFormat);
                    endDate = endDate.subtract(1, 'months').format(settings._inputDateFormat);
                }
                settings.filterParams.start_date = startDate;
                settings.filterParams.end_date = endDate;
                datepicker.html(moment(settings.filterParams.start_date).format("MMMM YYYY"));
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
        }
        //init year selector
        if (settings.dateRangeType === "yearly") {
            prepareDefaultDateRangeFilterParams();
            datepicker.html(moment(settings.filterParams.start_date).format("YYYY"));
            //bind the click events
            datepicker.datepicker({
                format: "YYYY-MM",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            }).on('changeDate', function (e) {
                var date = moment(e.date).format(settings._inputDateFormat),
                    year = moment(date).format("YYYY");
                settings.filterParams.start_date = year + "-01-01";
                settings.filterParams.end_date = year + "-12-31";
                datepicker.html(moment(settings.filterParams.start_date).format("YYYY"));
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
            dateRangeSelector.click(function () {
                var type = $(this).attr("data-act"),
                    startDate = moment(settings.filterParams.start_date),
                    endDate = moment(settings.filterParams.end_date);
                if (type === "next") {
                    startDate = startDate.add(1, 'years').format(settings._inputDateFormat);
                    endDate = endDate.add(1, 'years').format(settings._inputDateFormat);
                } else if (type === "prev") {
                    startDate = startDate.subtract(1, 'years').format(settings._inputDateFormat);
                    endDate = endDate.subtract(1, 'years').format(settings._inputDateFormat);
                }
                settings.filterParams.start_date = startDate;
                settings.filterParams.end_date = endDate;
                datepicker.html(moment(settings.filterParams.start_date).format("YYYY"));
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
        }
        //init week selector
        if (settings.dateRangeType === "weekly") {
            var initWeekSelectorText = function (selector) {
                var from = moment(settings.filterParams.start_date).format("Do MMM"),
                    to = moment(settings.filterParams.end_date).format("Do MMM, YYYY");
                datepicker.datepicker({
                    format: "YYYY-MM-DD",
                    autoclose: true,
                    calendarWeeks: true,
                    weekStart: AppHelper.settings.firstDayOfWeek
                });
                selector.html(from + " - " + to);
            };
            prepareDefaultDateRangeFilterParams();
            initWeekSelectorText(datepicker);
            //bind the click events
            dateRangeSelector.click(function () {
                var type = $(this).attr("data-act"),
                    startDate = moment(settings.filterParams.start_date),
                    endDate = moment(settings.filterParams.end_date);
                if (type === "next") {
                    startDate = startDate.add(7, 'days').format(settings._inputDateFormat);
                    endDate = endDate.add(7, 'days').format(settings._inputDateFormat);
                } else if (type === "prev") {
                    startDate = startDate.subtract(7, 'days').format(settings._inputDateFormat);
                    endDate = endDate.subtract(7, 'days').format(settings._inputDateFormat);
                }
                settings.filterParams.start_date = startDate;
                settings.filterParams.end_date = endDate;
                initWeekSelectorText(datepicker);
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
            datepicker.datepicker({
                format: settings._inputDateFormat,
                autoclose: true,
                calendarWeeks: true,
                weekStart: AppHelper.settings.firstDayOfWeek
            }).on("show", function () {
                $(".datepicker").addClass("week-view");
                $(".datepicker-days").find(".active").siblings(".day").addClass("active");
            }).on('changeDate', function (e) {
                var range = getWeekRange(e.date);
                settings.filterParams.start_date = range.firstDateOfWeek;
                settings.filterParams.end_date = range.lastDateOfWeek;
                initWeekSelectorText(datepicker);
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
        }
    }
    //build checkbox filter
    if (typeof settings.checkBoxes[0] !== 'undefined') {
        var checkboxes = "", values = [], name = "";
        $.each(settings.checkBoxes, function (index, option) {
            var checked = "", active = "";
            name = option.name;
            if (option.isChecked) {
                checked = " checked";
                active = " active";
                values.push(option.value);
            }
            checkboxes += '<label class="btn btn-default ' + active + '">';
            checkboxes += '<input type="checkbox" name="' + option.name + '" value="' + option.value + '" autocomplete="off" ' + checked + '>' + option.text;
            checkboxes += '</label>';
        });
        settings.filterParams[name] = values;
        var checkboxDom = '<div class="mr15 DTTT_container">'
            + '<div class="btn-group filter" data-act="checkbox" data-toggle="buttons">'
            + checkboxes
            + '</div>'
            + '</div>';
        thisTableWrapper.find(".custom-toolbar").append(checkboxDom);

        var $checkbox = thisTableWrapper.find("[data-act='checkbox']");
        $checkbox.click(function () {
            var selector = $(this);
            setTimeout(function () {
                var name = "";
                selector.parent().find("input:checkbox").each(function () {
                    name = $(this).attr("name");
                    if ($(this).is(":checked")) {
                        if (!(name in settings.filterParams)) {
                            settings.filterParams[name] = [];
                        }
                        settings.filterParams[name].push($(this).val());
                    }
                });
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
        });
    }
    //build radio button filter
    if (typeof settings.radioButtons[0] !== 'undefined') {
        var radioButtons = "";
        $.each(settings.radioButtons, function (index, option) {
            var checked = "", active = "";
            if (option.isChecked) {
                checked = " checked";
                active = " active";
                settings.filterParams[option.name] = option.value;
            }
            radioButtons += '<label class="btn btn-default ' + active + '">';
            radioButtons += '<input type="radio" id=" ' + options.id + '" name="' + option.name + '" value="' + option.value + '" autocomplete="off" ' + checked + '>' + option.text;
            radioButtons += '</label>';
        });
        var radioDom = '<div class="mr15 DTTT_container">'
            + '<div class="btn-group filter" data-act="radio" data-toggle="buttons">'
            + radioButtons
            + '</div>'
            + '</div>';
        thisTableWrapper.find(".custom-toolbar").append(radioDom);
        var $radioButtons = thisTableWrapper.find("[data-act='radio']");
        $radioButtons.click(function () {
            var $selector = $(this);
            setTimeout(function () {
                $selector.parent().find("input:radio").each(function () {
                    if ($(this).is(":checked")) {
                        settings.filterParams[$(this).attr("name")] = $(this).val();
                    }
                });
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
        });
    }
    //build dropdown filter
    if (typeof settings.filterDropdown[0] !== 'undefined') {
        $.each(settings.filterDropdown, function (index, option) {
            var selectOptions = "", selectedValue = "";
            $.each(option, function (index, selectOption) {
                var isSelected = "";
                /** @namespace option.isSelected */
                if (selectOption.isSelected) {
                    isSelected = "selected";
                    selectedValue = selectOption.id;
                }
                selectOptions += '<option ' + isSelected + ' value="' + selectOption.id + '">' + selectOption.text + '</option>';
            });
            if (option.name) {
                settings.filterParams[option.name] = selectedValue;
            }
            var selectDom = '<div class="mr15 DTTT_container">'
                + '<select class="' + option.css + '" name="' + option.name + '">'
                + selectOptions
                + '</select>'
                + '</div>';
            thisTableWrapper.find(".custom-toolbar").append(selectDom);
            var dropdown = thisTableWrapper.find("[name='" + option.name + "']");
            if (window.Select2 !== undefined) {
                dropdown.select2();
            }
            dropdown.change(function () {
                var selector = $(this);
                settings.filterParams[selector.attr("name")] = selector.val();
                thisTable.appTable({reload: true, filterParams: settings.filterParams});
            });
        });
    }
}