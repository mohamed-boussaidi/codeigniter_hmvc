<head>
	<?php
	$this->load->view( "template/meta" );
	echo load_js( array(
		"assets/js/jquery-3.2.0.min.js",
	) );
	$this->load->view( "template/helper" );
	echo load_css( array_merge( array(
		"assets/bootstrap/css/bootstrap.min.css",
		"assets/js/font-awesome/css/font-awesome.min.css",
		"assets/js/datatable/css/jquery.dataTables.min.css",
		"assets/js/datatable/css/responsive.dataTables.min.css",
		"assets/js/datatable/exportation/buttons.dataTables.css",
		"assets/js/datatable/TableTools/css/dataTables.tableTools.min.css",
		"assets/js/select2/select2.min.css",
		"assets/js/select2/select2-bootstrap.min.css",
		"assets/js/bootstrap-datepicker/css/datepicker3.min.css",
		"assets/js/bootstrap-toggle/css/bootstrap-toggle.min.css",
		"assets/js/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
		"assets/js/dropzone/dropzone.min.css",
		"assets/js/magnific-popup/magnific-popup.min.css",
		"assets/css/font.min.css",
		"assets/css/style.min.css",
		"assets/css/custom-style.min.css",
		"assets/js/sweetalert/dist/sweetalert.min.css"
	), build( "head_css" ) ) );
	echo load_js( array_merge( array(
		"assets/js/moment/moment.min.js",
		"assets/js/moment/locale/" . get_setting( "language" ) . ".js",
		"assets/bootstrap/js/bootstrap.min.js",
		"assets/js/jquery-validation/jquery.validate.min.js",
		"assets/js/jquery-validation/jquery.form.min.js",
		"assets/js/slimscroll/jquery.slimscroll.min.js",
		"assets/js/datatable/js/jquery.dataTables.min.js",
		"assets/js/datatable/js/dataTables.responsive.min.js",
		"assets/js/datatable/js/datetime-moment.min.js",
		"assets/js/datatable/exportation/dataTables.buttons.min.js",
		"assets/js/datatable/exportation/buttons.flash.min.js",
		"assets/js/datatable/exportation/jszip.min.js",
		"assets/js/datatable/exportation/pdfmake.min.js",
		"assets/js/datatable/exportation/vfs_fonts.min.js",
		"assets/js/datatable/exportation/buttons.html5.min.js",
		"assets/js/datatable/exportation/buttons.print.min.js",
		"assets/js/select2/select2.min.js",
		"assets/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
		"assets/js/bootstrap-datepicker/locale/" . get_setting( "language" ) . ".js",
		"assets/js/bootstrap-toggle/js/bootstrap-toggle.min.js",
		"assets/js/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
		"assets/js/dropzone/dropzone.min.js",
		"assets/js/magnific-popup/jquery.magnific-popup.min.js",
		"assets/js/sweetalert/dist/sweetalert.min.js",
		"assets/js/general_helper.min.js",
		"assets/js/html_helper.min.js",
		"assets/js/side_bar.min.js",
		"assets/js/scroll.min.js",
		"assets/js/app_loader.min.js",
		"assets/js/app_alert.min.js",
		"assets/js/app_form.min.js",
		"assets/js/dt_html_filters.min.js",
		"assets/js/app_table.js",
		"assets/js/ajax_helper.min.js",
		"assets/js/app.js",
		"assets/js/websocket.js",
		"assets/js/notify.js/dist/notify.js"
	), build( "head_js" ) ) );
	?>
</head>
