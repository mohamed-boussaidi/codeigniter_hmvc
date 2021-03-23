<?php

/**
 * Interface Mind_controller
 * this interface should be implemented
 * by all the controllers of the framework
 * to force theme to respect the shape
 * of the application
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
interface Mind_controller {

    /**
     * Mind_controller constructor.
     * Any constructor should call the INIT class
     * constructor which will call the CI Controller constructor
     */
    public function __construct();

    /**
     * The index function will be
     * used to load the main view of the
     * controller
     * @load view
     */
    public function index();

    /**
     * this function should be used
     * to load a specific element details
     * @post int id
     * @load view
     */
    public function view();

    /**
     * this function will open a
     * bootstrap popup which will be
     * used to add or edit an element
     * @post int id
     * @load view
     */
    public function modal_form();

    /**
     * this function will be used
     * to save an element to the database
     * @post int id
     * @echo json
     */
    public function save();

    /**
     * this function will be used
     * to delete or specific row
     * from the database
     * @post int id
     * @echo json
     */
    public function delete();

    /**
     * this function should be used
     * to list a specific set of rows
     * from the database to use it
     * with the jquery datatables plugin
     * this function will call a private function
     * called make row
     * @echo json
     */
    public function list_data();

    /**
     * this function used
     * to build the list data
     * if an id passed this function
     * should fetch the data by the id
     * @param $id
     * @param $data
     * @return array
     */
    public function make_row($data = stdClass::class, $id = false);
}