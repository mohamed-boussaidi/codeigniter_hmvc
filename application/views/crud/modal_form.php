<?php exit('silence_is_gold') ?>
<?php

echo form_open(
	get_uri( "crudmodulename/crudtags/save" ),
	array(
		"id"    => "crudtags-form",
		"class" => "general-form",
		"role"  => "form",
	)
);
?>

<div class="modal-body clearfix">

    <input type="hidden" name="id" value="<?php echo get_property( $crudtag, "id" ); ?>"/>

   crudtag_form_fields

</div>

<div class="modal-footer">

    <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="fa fa-close"></span>
		<?php echo plang( 'close' ); ?>
    </button>

    <button class="btn btn-primary">
        <span class="fa fa-check-circle"></span>
		<?php echo plang( 'save' ); ?>
    </button>

</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#crudtags-form").appForm({
            onSuccess: function (result) {
                $("#crudtags-table").appTable(
                    {newData: result.data, dataId: result.id}
                );
            }
        });
        setDatePicker('.datepicker');
        setTimePicker('.timepicker');
        $('.select').select2();

        //crudtag_select2_sources

    });
</script>
