<?php

echo form_open(
	get_uri( "rest_keys/save" ),
	array(
		"id"    => "rest-keys-form",
		"class" => "general-form",
		"role"  => "form",
	)
);
?>

<div class="modal-body clearfix">

    <input type="hidden" name="id" value="<?php echo get_property( $rest_key, "id" ); ?>"/>

    <div class="form-group">
        <label for="name" class=" col-md-3"><?php echo plang( 'name' ); ?></label>
        <div class=" col-md-9">
			<?php
			echo form_input(
				array(
					"id"                 => "name",
					"name"               => "name",
					"value"              => get_property( $rest_key, "name" ),
					"class"              => "form-control",
					"placeholder"        => plang( 'name' ),
					"autofocus"          => true,
					"data-rule-required" => true,
					"data-msg-required"  => plang( "field_required" ),
				)
			);
			?>
        </div>
    </div>


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
        $("#rest-keys-form").appForm({
            onSuccess: function (result) {
                $("#rest-keys-table").appTable(
                    {newData: result.data, dataId: result.id}
                );
            }
        });
    });
</script>
