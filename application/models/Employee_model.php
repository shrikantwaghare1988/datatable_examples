<?php

class Employee_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Core_model','cm');
    }
    function getDatatableAjax(){
        echo "Welcome";
    }  

}