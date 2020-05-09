<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->library('session');  
        $this->load->model('User_model');
        $this->load->database();
  	}

  	public function auth_user(){
  		try{
  			$type = $this->uri->segment(3);
  			switch($type){
  				case 1:
  				case 2:
  					$data = $this->input->post('data');
            		$data = json_decode($data);
  					$username = $data->username;
  					$password = $data->password;
  					$res = $this->User_model->auth_user($username,$password,$type);
  					if($res['status'] == 0){
  						throw new Exception($res['msg']);
  					}else if($res['status'] == 1){
  						$userData = array(
  							'user_id' => $res['uid'],
  							"type" => $res['type']
  						);
  						$this->session->set_userdata('userData',$userData);
  						echo $type == 1 ? 'creator' : 'bidder';
  						exit;
  					}else{
  						throw new Exception("Invalid Response");
  					}
  					break;
  				default:
  					throw new Exception("Invalid Type");
  			}
  		}catch(Exception $e){
  			echo "Error: ".$e->getMessage();
  		}
  	}

  	public function create_user(){
  		try{
  			$type = $this->uri->segment(3);
  			switch($type){
  				case 1:
  				case 2:
  					$data = $this->input->post('data');
            		$data = json_decode($data);
  					$username = $data->username;
  					$name = $data->name;
  					$password = $data->password;
  					$res = $this->User_model->create_user($username,$password,$name,$type);
  					if($res['status'] == 0){
  						throw new Exception($res['msg']);
  					}else if($res['status'] == 1){
  						echo $res['msg'];
  						exit;
  					}else{
  						throw new Exception("Invalid Response");
  					}
  					break;
  				default:
  					throw new Exception("Invalid Type");
  			}
  		}catch(Exception $e){
  			echo "Error: ".$e->getMessage();
  		}
  	}

  	public function logout(){
  		$this->session->unset_userdata('userData');
  		redirect('Welcome');
  	}
}
?>