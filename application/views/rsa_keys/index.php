<div id="page-content" class="p20 row">
    <div class="col-sm-3 col-lg-2">
		<?php $this->load->view( "settings/tabs", array( "tab" => "rsa_keys" ) ) ?>
    </div>
    <div class="col-sm-9 col-lg-10">
        <div class="panel">
            <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
                <li class="title-tab"><h4 class="pl15 pt5 pr15"><?php echo plang( "rsa_keys" ); ?></h4></li>
                <li><a id="public_key-button" role="presentation" class="active" href="javascript:;"
                       data-target="#public_key"><?php echo plang( "public_key" ); ?></a></li>
                <li><a role="presentation" href="javascript:;"
                       data-target="#private_key"><?php echo plang( 'private_key' ); ?></a></li>
                <div class="tab-title clearfix no-border">
                    <div class="title-button-group">
                        <button title="<?php echo plang( 'generate_rsa_keys' ) ?>"
                                class="btn btn-default"
                                id="generate">
                            <i class='fa fa-plus-circle'></i>&nbsp;
							<?php echo plang( 'generate_rsa_keys' ) ?>
                        </button>
                    </div>
                </div>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade" id="public_key"
                     style="text-align: center;padding: 15px 0 0 0">
                    <label style="width: 100%; min-height: 70vh; border: 0; outline-style: none">
                        <textarea
                                style="width: 100%; min-height: 70vh; border: 0; outline-style: none; padding: 20px"><?php echo get_property( $rsa_keys, "public" ) ?></textarea>
                    </label>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="private_key"
                     style="text-align: center;padding: 15px 0 0 0">
                    <label style="width: 100%; min-height: 70vh; border: 0; outline-style: none">
                        <textarea
                                style="width: 100%; min-height: 70vh; border: 0; outline-style: none; padding: 20px"><?php echo get_property( $rsa_keys, "private" ) ?></textarea>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#generate").click(function () {
            $("body").append();
            $.post('<?php echo get_uri( "rsa_keys/generate" )?>', function (data) {
                console.log(data);
                if (data.reload) {
                    location.reload();
                }
            }, "json");

        });
    });
</script>