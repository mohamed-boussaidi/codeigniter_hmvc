<div id="page-content" class="p20 row">
    <div class="col-sm-3 col-lg-2">
        <?php $this->load->view("settings/tabs", array("tab" => "roles")) ?>
    </div>
    <div class="col-sm-9 col-lg-10">
        <div class="row">
            <div class="col-md-12">
                <div id="role-list-box" class="panel panel-default">
                    <div class="page-title clearfix">
                        <h1> <?php echo plang('element_settings', array("roles")); ?></h1>
                        <div class="title-button-group">
                            <?php echo modal_anchor(
                                get_uri("roles/modal_form"),
                                "<i class='fa fa-plus-circle'></i> " . plang('add_element', array("role")),
                                array("class" => "btn btn-default", "title" => plang('add_element', array("role")))
                            ); ?>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="roles-table" class="display" cellspacing="0" width="100%">
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="role-details-section">
                    <div id="empty-role" class="text-center p15 box panel panel-default ">
                        <div class="box-content" style="vertical-align: middle; height: 100%">
                            <div><?php echo plang("select_a_role"); ?></div>
                            <span class="fa fa-cogs" style="font-size: 1450%; color:#f6f8f8"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#roles-table").appTable({
            source: '<?php echo get_uri("roles/list_data") ?>',
            columns: [
                {title: "<?php echo plang('name'); ?>", "class" : "all"},
                {title: '', "class": 'text-center all option w80'}
            ],
            hideTools: true,
            onInitComplete: function () {
                //set permissions box height equal to the roles box
                var $role_list = $("#role-list-box"),
                    $empty_role = $("#empty-role");
                if ($empty_role.length && $role_list.length) {
                    $empty_role.height($role_list.height() - 30);
                }
            },
            onDeleteSuccess : function () {
                $("#roles-table").find("tr").trigger("click");
            }
        });
        $("body").on("click", "tr", function () {
            if (!$(this).hasClass("active")) {
                appLoader.show();
                var role_id = $(this).find(".role-row").attr("data-id");
                if (role_id) {
                    $("tr.active").removeClass("active");
                    $(this).addClass("active");
                    $.ajax({
                        url: "<?php echo get_uri("roles/permissions"); ?>/",
                        method: "POST",
                        data: {id:role_id},
                        success: function (result) {
                            appLoader.hide();
                            //replace the empty role box by the html result
                            $("#role-details-section").html(result);
                        }
                    });
                }
            }
        });
    });
</script>
