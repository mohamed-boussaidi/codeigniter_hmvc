<?php exit('silence_is_gold') ?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH . "modules/Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

class Crudtags extends Init implements Mind_controller {

    public function __construct() {
        parent::__construct();
        $this->only_admin();
    }

    public function index() {
        view( "crudmodulename/crudtags/index" );
    }

    public function view() {
        show_404();
    }

    public function modal_form() {
        $id       = $this->input->post( 'id' );
        $crudtag = $id ? $this->Crudtags_model->get_one( $id ) : new stdClass();
        $data['crudtag'] = $crudtag;

        //crudtag_select2_controller_sources

        $this->load->view( "crudmodulename/crudtags/modal_form", $data );
    }

    public function save() {
        validate_submitted_data( array(
            "id"   => "numeric",
            //crudtag_server_side_validation
        ) );
        $id = $this->input->post( 'id' );
        $data         = array(
            //crudtag_data_to_save
        );
        saving_result(
            $this,
            $this->Crudtags_model->save( $data, $id )
        );
    }

    public function delete() {
        validate_submitted_data( array(
            "id" => "numeric|required"
        ) );
        $id = $this->input->post( 'id' );
        deleting_result( $this, $this->Crudtags_model->delete_one( $id ) );
    }

    public function list_data() {
        $rows = $this->Crudtags_model->get_all();
        $data = array();
        foreach ( $rows as $row ) {
            array_push( $data, $this->make_row( $row ) );
        }
        echo json_encode( array( "data" => $data ) );
    }

    public function make_row( $data = stdClass::class, $id = false ) {
        if ( $id ) {
            $data = $this->Crudtags_model->get_one( $id );
        }
        //crudtag_select2_controller_fields
        return array(
            dt_row_trigger(),
           //crudtag_controller_fields
            modal_anchor(
                get_uri( "crudmodulename/crudtags/modal_form" ),
                "<i class='fa fa-pencil'></i>",
                array(
                    "class"        => "edit",
                    "title"        => plang( 'edit_element', array( "key" ) ),
                    "data-post-id" => get_property( $data, "id" )
                )
            )
            . js_anchor(
                "<i class='fa fa-times fa-fw'></i>",
                array(
                    'title'           => plang( 'delete_element', array( "key" ) ),
                    "class"           => "delete",
                    "data-id"         => get_property( $data, "id" ),
                    "data-action-url" => get_uri( "crudmodulename/crudtags/delete" ),
                    "data-action"     => "delete"
                )
            )
        );
    }
}
