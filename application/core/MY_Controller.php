<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of My_Controller
 *
 * @author Ganaa
 *  
 */
class MY_Controller extends CI_Controller{
    //put your code here
    public $data = array();

    function __construct() {
        parent::__construct();
        $this->data['errors']= array();
        $this->data['site_name']= config_item('site_name');
      
       
    }
    
}
