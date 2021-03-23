<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("template/head") ?>
</head>
<body>
<div class="signin-box" style="text-align:center !important">
    <img style="margin-bottom:40px" src="<?php echo files_url("system",get_setting("site_logo")) ?>">
    <?php if (isset($form_type) && $form_type == "request_reset_password")
        $this->load->view("signin/reset_password");
    elseif (isset($form_type) && $form_type == "new_password")
        $this->load->view("signin/new_password");
    else $this->load->view("signin/signin");
    ?>
</div>
</body>
</html>