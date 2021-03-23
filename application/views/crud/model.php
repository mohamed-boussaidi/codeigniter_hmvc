<?php exit('silence_is_gold') ?>
<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

require_once APPPATH . 'models/Crud_model.php';

class Crudtags_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = parent::__construct('crudtags');
    }

}
