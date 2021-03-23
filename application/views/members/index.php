<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo plang('members'); ?></h1>
            <div class="title-button-group">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default btn-sm active mr-1"
                            title="<?php echo plang('list_view'); ?>">
                        <i class="fa fa-bars"></i>
                    </button>
                    <?php echo anchor(
                        get_uri("members/view"),
                        "<i class='fa fa-th-large'></i>",
                        array(
                            "class" => "btn btn-default btn-sm",
                            "title" => plang('grid_view')
                        )
                    ); ?>
                </div>
                <?php if (logged_have_permission("add_member")) { ?>
                    <?php echo modal_anchor(
                        get_uri("members/send_invitation_modal_form"),
                        "<i class='fa fa-envelope-o'></i> " . plang('send_element', array("invitation")),
                        array("class" => "btn btn-default", "title" => plang('send_element', array("invitation")))
                    );
                    echo modal_anchor(
                        get_uri("members/modal_form"),
                        "<i class='fa fa-plus-circle'></i> " . plang('add_element', array("member")),
                        array("class" => "btn btn-default", "title" => plang('add_element', array("member")))
                    ); ?>
                <?php } ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="members-table" class="display nowrap" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#members-table").appTable({
            source: '<?php echo get_uri("members/list_data/") ?>',
            order: [[2, "asc"]],
            radioButtons: [
                {
                    text: "<?php echo plang('active_members') ?>",
                    name: "status",
                    value: "active",
                    isChecked: true
                },
                {
                    text: "<?php echo plang('inactive_members') ?>",
                    name: "status",
                    value: "inactive",
                    isChecked: false
                }
            ],
            columns: [
                {title: '', "class": "w50 text-center"},
                {title: "<?php echo plang('name') ?>"},
                {title: "<?php echo plang('email') ?>", "class": "w20p"},
                {title: "<?php echo plang('phone') ?>", "class": "w15p"},
                {title: "<?php echo plang('dob') ?>", "class": "w15p"},
            ],
            exportationTitle: "<?php echo plang("members") ?>",
            pdf: [2, 3, 4],
            excel: [2, 3, 4],
            print: [2, 3, 4]
        });
    });
</script>
