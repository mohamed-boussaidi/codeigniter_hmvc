<div class="tab-content">
    <?php echo form_open(
        get_uri("members/save_social_links/"),
        array(
            "id" => "social-links-form",
            "class" => "general-form dashed-row white",
            "role" => "form"
        )
    ); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo plang('social_links'); ?></h4>
        </div>
        <?php echo form_hidden("id", $id) ?>
        <div class="panel-body">
            <div class="form-group">
                <label for="facebook" class=" col-md-2">Facebook</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "facebook",
                                "name" => "facebook",
                                "type" => "url",
                                "value" => get_property($social_links, "facebook"),
                                "class" => "form-control",
                                "placeholder" => "https://www.facebook.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Facebook")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "facebook", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="twitter" class=" col-md-2">Twitter</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "twitter",
                                "name" => "twitter",
                                "type" => "url",
                                "value" => get_property($social_links, "twitter"),
                                "class" => "form-control",
                                "placeholder" => "https://twitter.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Twitter")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "twitter", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="linkedin" class=" col-md-2">Linkedin</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "linkedin",
                                "name" => "linkedin",
                                "type" => "url",
                                "value" => get_property($social_links, "linkedin"),
                                "class" => "form-control",
                                "placeholder" => "https://www.linkedin.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Linkedin")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "linkedin", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="googleplus" class=" col-md-2">Google plus</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "googleplus",
                                "name" => "googleplus",
                                "type" => "url",
                                "value" => get_property($social_links, "googleplus"),
                                "class" => "form-control",
                                "placeholder" => "https://plus.google.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Google plus")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "googleplus", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="digg" class=" col-md-2">Digg</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "digg",
                                "name" => "digg",
                                "type" => "url",
                                "value" => get_property($social_links, "digg"),
                                "class" => "form-control",
                                "placeholder" => "http://digg.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Digg")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "digg", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="youtube" class=" col-md-2">Youtube</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "youtube",
                                "name" => "youtube",
                                "type" => "url",
                                "value" => get_property($social_links, "youtube"),
                                "class" => "form-control",
                                "placeholder" => "https://www.youtube.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Youtube")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "youtube", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="pinterest" class=" col-md-2">Pinterest</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "pinterest",
                                "name" => "pinterest",
                                "type" => "url",
                                "value" => get_property($social_links, "pinterest"),
                                "class" => "form-control",
                                "placeholder" => "https://www.pinterest.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Pinterest")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "pinterest", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="instagram" class=" col-md-2">Instagram</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "instagram",
                                "name" => "instagram",
                                "type" => "url",
                                "value" => get_property($social_links, "instagram"),
                                "class" => "form-control",
                                "placeholder" => "https://instagram.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Instagram")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "instagram", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="github" class=" col-md-2">Github</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "github",
                                "name" => "github",
                                "type" => "url",
                                "value" => get_property($social_links, "github"),
                                "class" => "form-control",
                                "placeholder" => "https://github.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Github")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "github", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="tumblr" class=" col-md-2">Tumblr</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "tumblr",
                                "name" => "tumblr",
                                "type" => "url",
                                "value" => get_property($social_links, "tumblr"),
                                "class" => "form-control",
                                "placeholder" => "https://www.tumblr.com/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Tumblr")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "tumblr", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="vine" class=" col-md-2">Vine</label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) {
                        echo form_input(
                            array(
                                "id" => "vine",
                                "name" => "vine",
                                "type" => "url",
                                "value" => get_property($social_links, "vine"),
                                "class" => "form-control",
                                "placeholder" => "https://vine.co/",
                                "data-rule-url" => true,
                                "data-msg-url" => plang("field_valid_url", array(), "Vine")
                            )
                        );
                    } else {
                        echo form_label(get_property($social_links, "vine", "-"));
                    } ?>
                </div>
            </div>
        </div>
        <?php if (logged_can_edit_admin(array("view_member","edit_member"), $id)) { ?>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary">
                <span class="fa fa-check-circle"></span>
                <?php echo plang('save'); ?>
            </button>
        </div>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#social-links-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
    });
</script>