<div class="box">
    <div class=" col-md-6 col-xs-12 text-center profile-image" style="max-width: 200px">
        <?php
        echo form_open(
            get_uri("members/save_profile_image/"),
            array(
                "id" => "profile-image-form",
                "class" => "general-form",
                "role" => "form"
            )
        );
        echo form_hidden("id", get_property($member, "id")) ?>
        <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) { ?>
            <div class="file-upload btn mt0 p0" style="vertical-align: top;  margin-left: -50px; ">
                <span><i class="btn fa fa-camera"></i></span>
                <?php echo cropbox_file("profile_image,200,200") ?>
            </div>
        <?php } ?>
        <?php echo form_input(
            array(
                "id" => "profile_image",
                "name" => "profile_image",
                "type" => "hidden",
                "value" => ""
            )
        ); ?>
        <span class="avatar avatar-lg">
            <?php build("members_profile_image_container_start"); ?>
            <img id="profile-image-preview" src="
            <?php echo get_avatar(get_property($member, "image")); ?>
            " alt="...">
            <?php build("members_profile_image_container_end"); ?>
        </span>
        <h4 class="">
            <?php $members_profile_name = build("members_profile_name", array(), array(get_property($member, "first_name") . " " . get_property($member, "last_name")));
            if (!count($members_profile_name)) {
                echo get_property($member, "first_name") . " " . get_property($member, "last_name");
            } else {
                foreach ($members_profile_name as $members_profile_name_value) {
                    echo $members_profile_name_value;
                }
            }
            ?>
        </h4>
        <?php echo form_close() ?>
    </div>
    <div class=" col-xs-12 col-md-6">
        <p class="p10 m0">
            <label class="label label-info large">
                <strong>
                    <?php echo $role ?>
                </strong>
            </label>
        </p>
        <p class="p10 m0">
            <a style="color: white" href="mailto:<?php echo get_property($member, "email") ?>">
                <i class="fa fa-envelope-o"></i>
                <?php echo get_property($member, "email") ?>
            </a>
        </p>
        <p class="p10 m0">
            <a style="color: white" href="tel:<?php echo get_property($member, "phone") ?>">
                <i class="fa fa-phone"></i>
                <?php echo get_property($member, "phone") ?>
            </a>
            <span class="mr15"></span>
            <?php if(!empty(get_property($member, "skype"))) { ?>
                <a style="color: white" href="skype:<?php echo get_property($member, "skype") ?>?call">
                    <i class="fa fa-skype"></i> <?php echo get_property($member, "skype") ?>
                </a>
            <?php } ?>
        </p>
        <div class="p10 m0 clearfix">
            <div class="pull-left">
                <?php echo social_links_widget($social_link) ?>
            </div>
        </div>
    </div>
</div>