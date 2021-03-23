<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FB_Authentication extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('facebook');
    }

    public function logged() {
        if($this->facebook->is_authenticated()){
            $fbUserProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,locale,cover,picture');
            print_r($fbUserProfile);
        }else{
            echo 'Error';
        }
    }

    public function index()
    {
        redirect($this->facebook->login_url());
    }

    public function logout()
    {
        // Remove local Facebook session
        $this->facebook->destroy_session();
        // Redirect to login page
        redirect('facebook');
    }
}