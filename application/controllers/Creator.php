<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creator extends CI_Controller {

	public function __construct() {
		date_default_timezone_set('Asia/Kolkata');
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Bid_Creator_model');
        $this->load->model('Bid_model');
        $this->load->model('User_model');
        $this->load->database();  
  		$userData = $this->session->userdata('userData');
  		if(!$userData){
  			echo "Error: Invalid Session Data.";
  			exit;
  		}
  		if($res = $this->User_model->validate_user($userData['user_id'],$userData['type'])){
  			$this->userData = $userData;
  			$this->userName = $res->name;
  		}else{
  			echo "Error: Invalid Session Data.";
  			exit;
  		}
  	}

  	public function index(){
  		$allBids = $this->Bid_Creator_model->get_all_bids($this->userData['user_id']);
  		$data = array(
  			'name' => $this->userName,
  			'allBids' => $allBids
  		);
  		$this->load->view('creator/creator_main',$data);
  	}

  	public function create_bid(){
  		$this->load->view('creator/create_bid');
  	}

  	public function create_new_bid(){
  		try{
  			$data = $this->input->post('data');
            $data = json_decode($data);
            $res = $this->Bid_Creator_model->create_bid($this->userData['user_id'],$data->title,$data->start_time,$data->end_time,$data->items);
            if($res == 1){
            	echo "success";
            }else{
            	throw new Exception($res);
            }
  		}catch(Exception $e){
  			echo "Error: ".$e->getMessage();
  		}
  	}

  	public function bid_page(){
      $bid_id = $this->uri->segment(3);
      $data = $this->Bid_model->get_bid_data($bid_id);
      if(!$data){
        redirect('Creator');
      }
      $this->load->view('creator/bid_page',$data[0]);
    }

    public function get_bid_ranking(){
      try{
        $data = $this->input->post('data');
        $data = json_decode($data);
        $res = $this->Bid_model->get_bid_ranking($data->bid_id);
        echo json_encode($res);
        exit;
      }catch(Exception $e){
        echo "Error: ".$e->getMessage();
      }
    }

}
?>