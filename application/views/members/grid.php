<div class="page-title clearfix">
    <h1><?php echo plang('members') ?></h1>
    <div class="title-button-group">
        <?php echo anchor(
            get_uri("members"),
            "<i class='fa fa-bars'></i>",
            array("class" => "btn btn-default btn-sm mr-1", "title" => plang('list_view'))
        );
        echo js_anchor(
            "<i class='fa fa-th-large'></i>",
            array("class" => "btn btn-default btn-sm active ml-1", "title" => plang('grid_view'))
        );
        echo modal_anchor(
            get_uri("members/send_invitation_modal_form"),
            "<i class='fa fa-envelope-o'></i> " . plang('send_element', array("invitation")),
            array("class" => "btn btn-default", "title" => plang('send_element', array("invitation")))
        );
        echo modal_anchor(
            get_uri("members/modal_form"),
            "<i class='fa fa-plus-circle'></i> " . plang('add_element', array("member")),
            array("class" => "btn btn-default", "title" => plang('add_element', array("member")))
        ); ?>
    </div>
</div>
<div id="page-content" class="p20 clearfix">
    <div class="row">
        <?php foreach ($members as $member) { ?>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-default  text-center ">
                    <div class="panel-body">
                        <span class="avatar avatar-md mt15">
                            <img src="
                            <?php echo get_avatar(get_property($member, "image")); ?>
                            " alt="...">
                        </span>
                        <h4><?php echo get_property($member, "first_name") . " " . get_property($member, "last_name"); ?></h4>
                    </div>
                    <div class="panel-footer bg-info p15 no-border">
                        <?php echo anchor("members/view/" . get_property($member, "id"),
                            plang("view_element", array("details")),
                            array("class" => "btn btn-xs btn-info")
                        ); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>