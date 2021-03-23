<div id="page-content" class="p20 row">
    <div id="settings_tabs" class="col-sm-3">
		<?php $this->load->view( "settings/tabs", array( "tab" => "general" ) ) ?>
    </div>
    <div id="settings_container" class="col-sm-9">
		<?php echo form_open(
			get_uri( "settings/save" ),
			array(
				"id"    => "general-settings-form",
				"class" => "general-form dashed-row",
				"role"  => "form"
			)
		); ?>
        <div class="panel">
            <div class="panel-default panel-heading">
                <h4><?php echo plang( "element_settings", array( "general" ) ) ?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="site_favicon_file" class=" col-md-2">
						<?php echo plang( "site_favicon" ) ?>
                    </label>
                    <div class=" col-md-10">
                        <div class="pull-left mr15">
                            <img id="site_favicon_preview" src="
                            <?php echo get_uri( get_setting( "system_file_path" ) . get_setting( "site_favicon" ) ) ?>
                            " alt="..."/>
                        </div>
                        <div class="pull-left file-upload btn btn-default btn-xs">
                            <span>...</span>
							<?php echo cropbox_file( "site_favicon,24,24" ) ?>
                        </div>
                        <input type="hidden" id="site_favicon" name="site_favicon" value=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_logo_file" class=" col-md-2">
						<?php echo plang( "site_logo" ) ?>
                    </label>
                    <div class=" col-md-10">
                        <div class="pull-left mr15">
                            <img id="site_logo_preview" src="
                            <?php echo get_uri( get_setting( "system_file_path" ) . get_setting( "site_logo" ) ) ?>
                            " alt="..."/>
                        </div>
                        <div class="pull-left file-upload btn btn-default btn-xs">
                            <span>...</span>
							<?php echo cropbox_file( "site_logo,175,40" ) ?>
                        </div>
                        <input type="hidden" id="site_logo" name="site_logo" value=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_title" class=" col-md-2">
						<?php echo plang( "site_title" ) ?>
                    </label>
                    <div class=" col-md-10">
						<?php echo form_input(
							array(
								"id"                 => "site_title",
								"name"               => "site_title",
								"value"              => get_setting( "site_title" ),
								"class"              => "form-control",
								"data-rule-required" => true,
								"data-msg-required"  => plang( "field_required", array( "site_title" ) ),
							)
						); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="media_replace" class=" col-md-2">
						<?php echo plang( "media_replace" ) ?>
                    </label>
                    <div class=" col-md-10">
						<?php echo form_checkbox(
							array(
								"id"           => "media_replace",
								"name"         => "media_replace",
								"data-toggle"  => "toggle",
								"data-on"      => plang( "enabled" ),
								"data-off"     => plang( "disabled" ),
								"data-onstyle" => "success",
								"checked"      => get_setting( 'media_replace' ) === "enabled" ? true : false,
								"value"        => "enabled"
							)
						); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="profile_image_max_size" class=" col-md-2">
						<?php echo plang( "profile_image_max_size" ) ?>
                    </label>
                    <div class=" col-md-10">
						<?php echo form_input(
							array(
								"id"                 => "profile_image_max_size",
								"type"               => "number",
								"name"               => "profile_image_max_size",
								"value"              => get_setting( "profile_image_max_size" ),
								"class"              => "form-control mini",
								"data-rule-required" => true,
								"data-rule-number"   => true,
								"data-msg-required"  => plang(
									"field_required",
									array( "profile_image_max_size" )
								),
							)
						); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_format" class=" col-md-2">
						<?php echo plang( 'date_format' ); ?>
                    </label>
                    <div class="col-md-10">
						<?php echo form_dropdown(
							"date_format",
							array(
								"dd-mm-yyyy" => "dd-mm-yyyy",
								"mm-dd-yyyy" => "mm-dd-yyyy",
								"yyyy-mm-dd" => "yyyy-mm-dd",
								"dd/mm/yyyy" => "dd/mm/yyyy",
								"mm/dd/yyyy" => "mm/dd/yyyy",
								"yyyy/mm/dd" => "yyyy/mm/dd",
								"dd.mm.yyyy" => "dd.mm.yyyy",
								"mm.dd.yyyy" => "mm.dd.yyyy",
								"yyyy.mm.dd" => "yyyy.mm.dd",
							), get_setting( 'date_format' ), "class='select2 mini'"
						); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="timezone" class=" col-md-2">
						<?php echo plang( 'timezone' ); ?>
                    </label>
                    <div class="col-md-10">
						<?php echo form_dropdown(
							"timezone",
							$timezone_dropdown,
							get_setting( 'timezone' ),
							"class='select2 mini'"
						); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="first_day_of_week" class=" col-md-2">
						<?php echo plang( 'first_day_of_week' ); ?>
                    </label>
                    <div class="col-md-10">
						<?php
						echo form_dropdown(
							"first_day_of_week", array(
							"0" => plang( "sunday" ),
							"1" => plang( "monday" ),
							"2" => plang( "tuesday" ),
							"3" => plang( "wednesday" ),
							"4" => plang( "thursday" ),
							"5" => plang( "friday" ),
							"6" => plang( "saturday" )
						), get_setting( 'first_day_of_week' ), "class='select2 mini'"
						);
						?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="rows_per_page" class=" col-md-2">
						<?php echo plang( 'rows_per_page' ); ?>
                    </label>
                    <div class="col-md-10">
						<?php
						echo form_dropdown(
							"rows_per_page", array(
							"10"  => "10",
							"25"  => "25",
							"50"  => "50",
							"100" => "100",
							"-1"  => plang( "all" ),
						), get_setting( 'rows_per_page' ), "class='select2 mini'"
						);
						?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="accepted_file_formats" class="col-md-2">
						<?php echo plang( 'accepted_file_formats' ); ?>
                    </label>
                    <div class="col-md-10">
						<?php
						echo form_input( array(
							"id"              => "accepted_file_formats",
							"name"            => "accepted_file_formats",
							"value"           => get_setting( "accepted_file_formats" ),
							"class"           => "form-control mini validate-hidden",
							"placeholder"     => plang( 'accepted_file_formats' ),
							"select2Required" => true
						) );
						?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language" class=" col-md-2">
						<?php echo plang( "language" ) ?>
                    </label>
                    <div class="col-md-10">
						<?php echo $language ?>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary">
                    <span class="fa fa-check-circle"></span>
					<?php echo plang( "save" ) ?>
                </button>
            </div>
        </div>
		<?php echo form_close() ?>
    </div>

</div>
<?php $this->load->view( "template/cropbox" ) ?>
<script type="text/javascript">
    $(document).ready(function () {
		<?php if (! isset( $_GET['show_all_settings_tabs'] )) { ?>
        var settings_tabs = $('#settings_tabs'),
            settings_container = $('#settings_container');
        if (settings_tabs.find('li').length < 2) {
            settings_tabs.remove();
            settings_container.removeClass('col-sm-9');
            settings_container.addClass('col-sm-12');
        }
		<?php } ?>
        $("#general-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                if ($("#site_logo").val()) location.reload();
                if ($("#site_favicon").val()) location.reload();
            }
        });
        $(".select2").select2();
        $(".upload").change(function () {
            showCropBox(this);
        });
        $("#accepted_file_formats").select2({
            multiple: true,
            allowclear: true,
            tags: [],
            tokenSeparators: [',', ' '],
            formatResult: fileSelect2Format,
            formatSelection: fileSelect2Format
        });
    });
</script>
