<div class="tab-content">
	<?php $permissions = role_permissions( get_property( $role, "id" ) );
	echo form_open(
		get_uri( "roles/save_permissions/" ),
		array(
			"id"    => "permissions-form",
			"class" => "general-form dashed-row",
			"role"  => "form"
		)
	); ?>
	<?php echo form_hidden( "id", get_property( $role, "id" ) ) ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4>
				<?php
				echo plang( 'permissions' ) . " : " .
				     get_property( $role, "title" )
				?>
            </h4>
        </div>
        <div class="panel-body">
			<?php
			foreach ( $permissions_list as $key => $elements ) {
				echo '<ul class="permission-list"><li>';
				echo '<h5>' . plang( "element_permissions", array( $key ) ) . '</h5>';
				foreach ( $elements as $element ) {
					echo permission_html_line( $element, $permissions );
				}
				echo '</li></ul>';
			}
			?>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary mr10">
                <span class="fa fa-check-circle"></span>
				<?php echo plang( 'save' ); ?>
            </button>
        </div>
    </div>
	<?php echo form_close(); ?>
</div>
<script language="JavaScript">
    $(document).ready(function () {
        $("#permissions-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
		<?php echo make_linked_checkbox( $linked_checkbox_list ) ?>
    });
</script>