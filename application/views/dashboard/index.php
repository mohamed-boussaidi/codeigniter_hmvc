<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="clearfix">
            <?php foreach ($widgets as $widget) { ?>
                <?php if(!empty(get_array_value($widget, "url"))) { ?>
                    <a href="<?php echo base_url(get_array_value($widget, "url")) ?>">
                <?php } ?>
                <?php $panel = !empty(get_array_value($widget, "panel")) ? get_array_value($widget, "panel") : "info" ?>
                <?php $color = !empty(get_array_value($widget, "color")) ? "background-color:" . get_array_value($widget, "color") . ";" : "info" ?>
                <div class="<?php echo get_array_value($widget, "classes") ?>  widget-container">
                    <div class="panel panel-<?php echo $panel ?>" style="<?php echo $color ?>">
                        <div class="panel-body ">
                            <div class="widget-icon">
                                <i class="fa <?php echo get_array_value($widget, "icon") ?>"></i>
                            </div>
                            <div class="widget-details">
                                <h3>
                                    <?php echo get_array_value($widget, "value") ?>
                                </h3>
                                <?php echo get_array_value($widget, "text") ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty(get_array_value($widget, "url"))) { ?>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <?php foreach ($elements as $element) { echo $element; } ?>
    <?php foreach ($views as $view) { $this->load->view($view['name'], $view['params']); } ?>
</div>