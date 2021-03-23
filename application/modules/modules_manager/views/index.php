<div id="page-content" class="p20 row">
    <div class="col-sm-3 col-lg-2">
        <?php $this->load->view("settings/tabs", array("tab" => "modules_manager")) ?>
    </div>
    <div class="col-sm-9 col-lg-10">
        <div class="row">
            <div class="col-md-12">
                <div id="role-list-box" class="panel panel-default">
                    <div class="page-title clearfix">
                        <h1> <?php echo plang('element_settings', array("modules")); ?></h1>
                        <div class="title-button-group">
                            <?php echo modal_anchor(
                                get_uri("modules_manager/modal_form"),
                                "<i class='fa fa-plus-circle'></i> " . plang('add_element', array("module")),
                                array("class" => "btn btn-default", "title" => plang('add_element', array("module")))
                            ); ?>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="modules-manager-table" class="display" cellspacing="0" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#modules-manager-table").appTable({
            source: '<?php echo get_uri("modules_manager/list_data") ?>',
            columns: [
                {title: "<?php echo plang('module_name'); ?>", "class": "all"},
                {title: "<?php echo plang('author'); ?>", "class": ""},
                {title: "<?php echo plang('company'); ?>", "class": ""},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center all option w100"}
            ],
            order: [[1, "asc"]],
            displayLength: -1
        });
    });
</script>
