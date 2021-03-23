<?php exit('silence_is_gold') ?>
<div id="page-content" class="p20 clearfix">
    <div id="role-list-box" class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo plang( "crudtags" ); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(
                    get_uri( "crudmodulename/crudtags/modal_form" ),
                    "<i class='fa fa-plus-circle'></i> " . plang( 'add_element', array( "crudtag" ) ),
                    array( "class" => "btn btn-default", "title" => plang( 'add_element', array( "crudtag" ) ) )
                ); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="crudtags-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#crudtags-table").appTable({
            source: '<?php echo get_uri( "crudmodulename/crudtags/list_data" ) ?>',
            columns: [
                //crudtag_datatables_fields
                {title: '<i class="fa fa-bars"></i>', "class": "text-center all option w120"}
            ],
            order: [[1, "asc"]],
            displayLength: -1
        });
    });
</script>
