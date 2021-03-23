<?php $this->load->view("template/cropbox") ?>
<div id="page-content" class="clearfix">
    <div class="bg-success clearfix">
        <div class="col-md-6 col-xs-12">
            <div class="row p20">
                <?php $this->load->view("members/profile_image") ?>
            </div>
        </div>
    </div>
    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li>
            <a role="presentation" href="<?php echo get_uri("members/general_info/" . get_property($member, "id")) ?>"
               data-target="#tab-general-info" id="general-info-but">
                <?php echo plang("general_info") ?>
            </a>
        </li>
        <li>
            <a role="presentation" href="<?php echo get_uri("members/social_links/" . get_property($member, "id")) ?>"
               data-target="#tab-social-links" id="social-links-but">
                <?php echo plang("social_links") ?>
            </a>
        </li>
        <?php if (logged_can_edit_admin(array("view_member", "edit_member"), get_property($member, "id"))) { ?>
        <li>
            <a role="presentation" href="<?php echo get_uri("members/account_settings/" . get_property($member, "id")) ?>"
               data-target="#tab-account-settings" id="account-settings-but">
                <?php echo plang('element_settings', array("account")) ?>
            </a>
        </li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="tab-general-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-social-links"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-account-settings"></div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".upload").change(function () {
            showCropBox(this);
        });
        $("#profile_image").change(function () {
            $("#profile-image-form").submit();
        });
        $("#profile-image-form").appForm({
            isModal: false,
            onSuccess: function () {
                location.reload();
            }
        });
        var tab = "<?php echo $tab; ?>";
        if (tab === "social") {
            $("#social-links-but").trigger("click");
        } else if (tab === "account") {
            $("#account-settings-but").trigger("click");
        } else {
            $("#general-info-but").trigger("click");
        }
    });
</script>