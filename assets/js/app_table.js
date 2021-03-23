/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

$(document).ready(function () {
    $("body").click(function (e) {
        var child = $("td.child");
        child.removeClass("option");
        child.addClass("option");
        if (e.target.id === "dt-hide-show") {
            $(e.target).toggleClass("fa-plus-circle fa-minus-circle");
        } else if (e.target.firstChild) {
            if (e.target.firstChild.id === "dt-hide-show") {
                $(e.target.firstChild).toggleClass("fa-plus-circle fa-minus-circle");
            }
        }
    });
});

(function ($) {
    //extend jquery and add custom data tables under the name of appTable
    $.fn.appTable = function (options) {
        //set default display length
        var displayLength = AppHelper.settings.displayLength || 10;
        //set default settings (reload : reload datatable, newData : add new line)
        var defaults = {
            source: "", //data url
            responsive: true, //data url
            dateFormat: "", //date format
            columns: [], //column title and options
            print: [],
            excel: [],
            pdf: [],
            exportationTitle : AppHelper.language.exportationTitle,
            order: [[0, "asc"]], //default sort value
            hideTools: false, //show/hide tools section
            displayLength: displayLength, //default rows per page
            dateRangeType: "", // type: daily, weekly, monthly, yearly. output params: start_date and end_date
            checkBoxes: [], // [{text: "Caption", name: "status", value: "in_progress", isChecked: true}]
            radioButtons: [], // [{text: "Caption", name: "status", value: "in_progress", isChecked: true}]
            filterDropdown: [], /* [
             {name: "project_id", css: "w200", options: <?php echo $projects_dropdown; ?>},
             {name: "member_id", css: "w200", options: <?php echo $members_dropdown; ?>}
             ]*/
            filterParams: {datatable: true}, //will post this vales on source url
            summation: "", /* {column: 5, dataType: 'currency'}  dataType:currency, time */
            onDeleteSuccess: function () {
            },
            onInitComplete: function () {
            },
            //set custom language from php language file
            customLanguage: {
                printButtonToolTip: AppHelper.language.printButtonToolTip,
                today: AppHelper.language.today,
                yesterday: AppHelper.language.yesterday,
                tomorrow: AppHelper.language.tomorrow
            },
            footerCallback: function (row, data, start, end, display) {
            },
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            },
            fnDrawCallback: function (oSettings) {
            }
        };
        //get the current instance
        var thisTable = $(this);
        //check if this binding with a table or not
        if (!thisTable.is("table")) {
            //break the object and output an error
            console.log("Element must have to be a table", this);
            return false;
        }
        //merge the passed options with the defaults into settings object
        var settings = $.extend({}, defaults, options);
        if(settings.responsive) {
            //add responsive trigger col as first one
            settings.columns.unshift({title: '', "class": "w30 text-center control"});
        }
        if(settings.dateFormat === "") {
            settings.dateFormat = AppHelper.settings.dateFormat;
        }
        //if reload enabled (reload)
        if (settings.reload) {
            //reload the table
            $.fn.dataTable.moment( settings.dateFormat.toUpperCase() );
            thisTable.dataTable().fnReloadAjax(settings.filterParams);
            return false;
        }

        //if newData enabled (add/edit)
        if (settings.newData) {
            $.fn.dataTable.moment( settings.dateFormat.toUpperCase() );
            var dataTable = thisTable.dataTable();
            if (settings.dataId) {
                //check for existing row; if found, delete the row;
                var post_id = thisTable.find("[data-post-id='" + settings.dataId + "']");
                //if row founded
                if (post_id.length) {
                    //delete the row from the table
                    dataTable.fnDeleteRow(post_id.closest('tr'));
                    //else
                } else {
                    //check with the data index id attribute
                    var index_id = $(this).find("[data-index-id='" + settings.dataId + "']");
                    //if founded
                    if (index_id.length) {
                        //remove the first tr from the table
                        thisTable.fnDeleteRow(index_id.closest('tr'));
                    }
                }
            }
            //after deleting the row add the new one
            thisTable.fnAddRow(settings.newData);
            return false;
        }
        //to print columns container
        settings._visible_columns = [];
        //for each column
        $.each(settings.columns, function (index, column) {
            //if the column is visible
            if (column.visible !== false) {
                //add it to columns container
                settings._visible_columns.push(index);
            }
        });
        //get the first day of the week (0-6)
        settings._firstDayOfWeek = AppHelper.settings.firstDayOfWeek || 0;
        //get the date format
        settings._inputDateFormat = "YYYY-MM-DD";

        /**
         * get the first and last date of week of a specific date
         * @param date
         * @returns {{}}
         */
        var getWeekRange = function (date) {
            //if there is no date set $date to now
            if (!date) date = moment().format(settings._inputDateFormat);
            //get the day of week as int (0-6)
            var dayOfWeek = moment(date).format("E");
            //difference between the first day of week and $date
            var diff = dayOfWeek - AppHelper.settings.firstDayOfWeek;
            //define the range container
            var range = {};
            //the first day is passed
            if (diff < 7) {
                //the first date == date of (($date - $date day number (0-6)) - first day of the week number (0-6))
                range.firstDateOfWeek = moment(date).subtract(diff, 'days').format("YYYY-MM-DD");
                // diff == 7 the date is the first day
            } else {
                //the date is the first day of the week
                range.firstDateOfWeek = moment(date).format("YYYY-MM-DD");
            }
            //the date < the first day
            if (diff < 0) {
                //the first date == date of (the first date - 7)
                range.firstDateOfWeek = moment(range.firstDateOfWeek).subtract(7, 'days').format("YYYY-MM-DD");
            }
            //add 6 day to the first date of the week to get the last date
            range.lastDateOfWeek = moment(range.firstDateOfWeek).add(6, 'days').format("YYYY-MM-DD");
            return range;
        };

        /**
         * make rows filter based on the current day, week, month or year
         */
        var prepareDefaultDateRangeFilterParams = function () {
            //get rows per day
            if (settings.dateRangeType === "daily") {
                //get the current date and put it as the start date
                settings.filterParams.start_date = moment().format(settings._inputDateFormat);
                //make the end same as the start to get rows of that specific date
                settings.filterParams.end_date = settings.filterParams.start_date;
                //get rows per month
            } else if (settings.dateRangeType === "monthly") {
                //get all the current month days
                var daysInMonth = moment().daysInMonth();
                //get the current mount and year
                var yearMonth = moment().format("YYYY-MM");
                //set the start date to the first day of the month
                settings.filterParams.start_date = yearMonth + "-01";
                //send end date to the last day of the month
                settings.filterParams.end_date = yearMonth + "-" + daysInMonth;
                //get rows per year
            } else if (settings.dateRangeType === "yearly") {
                //get the current year
                var year = moment().format("YYYY");
                //set the start date to the first day of the first month of the year
                settings.filterParams.start_date = year + "-01-01";
                //set the end date to the last day of the last month of the year
                settings.filterParams.end_date = year + "-12-31";
                //get rows per week
            } else if (settings.dateRangeType === "weekly") {
                //get the current week start and end dates
                var range = getWeekRange();
                //set the first date of the week
                settings.filterParams.start_date = range.firstDateOfWeek;
                //Set the last date of the week
                settings.filterParams.end_date = range.lastDateOfWeek;
            }
        };

        /**
         * make rows filter filter based on checkbox values
         */
        var prepareDefaultCheckBoxFilterParams = function () {
            //for each checkbox
            $.each(settings.checkBoxes, function (index, option) {
                if (!("name" in settings.filterParams)) {
                    settings.filterParams[name] = [];
                }
                //get the check box name
                if (option.isChecked) {
                    //get the value
                    settings.filterParams[name].push(option.value);
                }
            });
        };

        /**
         * make rows filter filter based on radios values
         */
        var prepareDefaultRadioFilterParams = function () {
            //for each radio
            $.each(settings.radioButtons, function (index, option) {
                if (option.isChecked) {
                    //push the radio name and value to the filter parameters
                    settings.filterParams[option.name] = option.value;
                }
            });
        };

        prepareDefaultDateRangeFilterParams();
        prepareDefaultCheckBoxFilterParams();
        prepareDefaultRadioFilterParams();

        var exportationButtons = {buttons: []};

        if (settings.pdf.length) {
            exportationButtons.buttons.push({
                extend: 'pdfHtml5',
                text: AppHelper.language.exportButtonPdf,
                className: 'DTTT_button',
                filename: replaceAll(" ", "_", settings.exportationTitle),
                title: settings.exportationTitle,
                exportOptions: {
                    columns: settings.pdf
                },
                customize: function (doc) {
                    doc.content[1].table.widths =
                        new Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    doc.styles.tableHeader.alignment = "left";
                    doc.styles.tableHeader.bold = true;
                    doc['footer'] = (function (page, pages) {
                        return {
                            columns: [
                                {
                                    alignment: 'center',
                                    text: [
                                        {text: page.toString()},
                                        ' / ',
                                        {text: pages.toString()}
                                    ]
                                }
                            ],
                            margin: [10, 0]
                        }
                    });
                }
            });
        }

        if (settings.print.length) {
            exportationButtons.buttons.push({
                extend: 'print',
                text: AppHelper.language.exportButtonPrint,
                className: 'DTTT_button',
                exportOptions: {
                    columns: settings.print
                },
                title: settings.exportationTitle,
                customize: function ( win ) {
                    $(win.document.body).find( 'h1' )
                        .css('text-align','center' );
                    $(win.document.body).find( 'h1' ).css('margin-top','30px' );
                    $(win.document.body).find( 'h1' ).css('margin-bottom','30px' );
                    $(win.document.body)
                        .css( 'font-size', '10pt' );
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )

                }
            });
        }

        if (settings.excel.length) {
            exportationButtons.buttons.push({
                extend: 'excelHtml5',
                text: AppHelper.language.exportButtonExcel,
                className: 'DTTT_button',
                filename: replaceAll(" ", "_", settings.exportationTitle),
                title: settings.exportationTitle,
                exportOptions: {
                    columns: settings.excel
                }
            });
        }

        //define dataTable custom options
        var datatableOptions = {
            //ajax request settings
            ajax: {
                url: settings.source,
                type: "POST",
                data: settings.filterParams
            },
            responsive: {
                details: {
                    type: 'column'
                }
            },
            buttons: exportationButtons,
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0
            }],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, AppHelper.language.all]],
            columns: settings.columns,
            bProcessing: true,
            iDisplayLength: settings.displayLength,
            bAutoWidth: false,
            bSortClasses: false,
            order: settings.order,
            //run before the ajax request
            fnInitComplete: function () {
                settings.onInitComplete(this);
            },
            language: {
                lengthMenu: AppHelper.language.lengthMenu,
                zeroRecords: AppHelper.language.zeroRecords,
                search: AppHelper.language.search,
                searchPlaceholder: AppHelper.language.searchPlaceholder,
                sProcessing: "<div class='table-loader'><span class='loading'></span></div>",
                "oPaginate": {
                    "sPrevious": "<i class='fa fa-angle-double-left'></i>",
                    "sNext": "<i class='fa fa-angle-double-right'></i>"
                }
            },
            //build default table
            sDom: "",
            //footer drawing
            footerCallback: function (row, data, start, end, display) {
                var thisTable = this;
                //if there is summation request
                if (settings.summation) {
                    //provides information about the table's paging state
                    var pageInfo = thisTable.api().page.info();
                    //if there is records
                    if (pageInfo.recordsTotal) {
                        //show the footer
                        $(this).find("tfoot").show();
                    } else {
                        $(thisTable).find("tfoot").hide();
                        return false;
                    }
                    //foreach summation
                    $.each(settings.summation, function (index, option) {
                        // total value of current page
                        var pageTotal = calculateDatatableTotal(thisTable, option, true);
                        pageTotal = getDatatableFormattedTotal(pageTotal, option);
                        //inject the total to the cell that have data-current-page col num
                        $(thisTable).find("[data-current-page=" + option.column + "]").html(pageTotal);
                        //total value of all pages
                        if (pageInfo.pages > 1) {
                            //if there is more then one page show the total section
                            $(thisTable).find("[data-section='all_pages']").show();
                            //total of all the pages
                            var total = calculateDatatableTotal(thisTable, option);
                            total = getDatatableFormattedTotal(total, option);
                            $(thisTable).find("[data-all-page=" + option.column + "]").html(total);
                        } else {
                            //if data data-section not found hide the row
                            $(thisTable).find("[data-section='all_pages']").hide();
                        }
                    });
                }
                //fire up the custom footer callback
                settings.footerCallback(row, data, start, end, display, thisTable);
            },
            //fire up the custom row callback before row drawing
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                settings.rowCallback(nRow, aData, iDisplayIndex, iDisplayIndexFull);
            },
            "fnDrawCallback": function (oSettings) {
                //hide the thead
                if (settings.hideTools === true) {
                    $(oSettings.nTHead).hide();
                }
                var exportButtons = $(".DTTT_button");
                exportButtons.removeClass("dt-button");
                exportButtons.removeClass("buttons-copy");
                exportButtons.removeClass("buttons-html5");
                exportButtons.removeClass("buttons-excel");
                exportButtons.removeClass("buttons-pdf");
                exportButtons.css("margin", "3px 0");
                $(".dataTables_wrapper").find(".btn").css("margin", "3px 0");
                $(".dt-buttons").css("float", "right");
                settings.fnDrawCallback(oSettings);
            }
        };
        if (!settings.hideTools) {
            datatableOptions.sDom = "<'datatable-tools'<'col-md-3'l><'col-md-9 custom-toolbar'fB>r>t<'datatable-tools clearfix'<'col-md-3'i><'col-md-9'p>>";
        }
        //create the datatables object based on the constructed options
        $.fn.dataTable.moment( settings.dateFormat.toUpperCase() );
        var oTable = thisTable.dataTable(datatableOptions),
            thisTableWrapper = thisTable.closest(".dataTables_wrapper");
        //make every select a select2 object
        thisTableWrapper.find("select").select2({
            //show search box even if there is no results
            minimumResultsForSearch: -1
        });
        dt_html_filters(thisTable, settings, options, thisTableWrapper, prepareDefaultDateRangeFilterParams, getWeekRange);
        /**
         * ajax query to delete a specific element
         * @param e
         */
        function deleteElement(e) {
            appLoader.show();
            var $target = $(e.currentTarget),
                url = $target.attr('data-action-url'),
                id = $target.attr('data-id');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (result) {
                    if (result.success) {
                        $.fn.dataTable.moment( settings.dateFormat.toUpperCase() );
                        var tr = $target.closest('tr'),
                            table = thisTable.DataTable();
                        oTable.fnDeleteRow(table.row(tr).index());
                        appAlert.success(result.message, {duration: 10000});
                        //fire success callback
                        settings.onDeleteSuccess(result);
                    } else {
                        appAlert.error(result.message);
                    }
                    appLoader.hide();
                }
            });
        }

        /**
         * delete element handler
         * @param e
         */
        var deleteHandler = function (e) {
            swal({
                    title: AppHelper.language.delete_box_title,
                    text: AppHelper.language.delete_box_text + "\n" +
                    AppHelper.language.delete_box_timer,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: AppHelper.language.delete_box_confirm,
                    cancelButtonText: AppHelper.language.delete_box_cancel,
                    closeOnConfirm: true,
                    timer: 5000
                },
                function (confirm) {
                    if (confirm) {
                        deleteElement(e);
                    }
                    swal.close();
                });

        };

        window.InstanceCollection = window.InstanceCollection || {};
        window.InstanceCollection["#" + this.id] = settings;

        //delete handler trigger
        $('body').find(thisTable).on('click', '[data-action=delete]', deleteHandler);

        /**
         * return datatable settings
         * @param oSettings
         * @returns {*}
         */
        $.fn.dataTableExt.oApi.getSettings = function (oSettings) {
            return oSettings;
        };

        /**
         * reload the data table
         * @param oSettings
         * @param filterParams
         */
        $.fn.dataTableExt.oApi.fnReloadAjax = function (oSettings, filterParams) {
            this.fnClearTable();
            this.oApi._fnProcessingDisplay(oSettings, true);
            var that = this;
            $.ajax({
                url: oSettings.ajax.url,
                type: "POST",
                dataType: "json",
                data: filterParams,
                success: function (json) {
                    /* Got the data - add it to the table */
                    for (var i = 0; i < json.data.length; i++) {
                        that.oApi._fnAddData(oSettings, json.data[i]);
                    }

                    oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
                    that.fnDraw();
                    that.oApi._fnProcessingDisplay(oSettings, false);
                }
            });
        };

        $.fn.dataTableExt.oApi.fnAddRow = function (oSettings, data) {
            this.oApi._fnAddData(oSettings, data);
            this.fnDraw();
        };

    };
})(jQuery);
