<?php
$overflow =  !empty($notifications_list) ? "overflow-y: scroll;" : 'overflow-y: hidden;'
?>
<div class="list-group" id="notificaion-popup-list" style="max-height: 400px;
<?php echo $overflow ?>">
    <?php $this->load->view(
        "notifications/list_data",
        array("notifications_list" => $notifications_list)
    ) ?>
</div>
