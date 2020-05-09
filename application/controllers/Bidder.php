<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bidder extends CI_Controller {

	public function __construct() {
        date_default_timezone_set('Asia/Kolkata');
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Bidder_model');
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
  		$pendingBids = $this->Bidder_model->get_bid_invites($this->userData['user_id'],0);
      $acceptedBids = $this->Bidder_model->get_bid_invites($this->userData['user_id'],1);
  		$data = array(
  			'name' => $this->userName,
  			'pendingBids' => $pendingBids,
        'acceptedBids' => $acceptedBids
  		);
  		$this->load->view('bidder/bidder_main',$data);
  	}

    public function manage_bid_invite(){
      try{
        $data = $this->input->post('data');
        $data = json_decode($data);
        $res = $this->Bidder_model->manage_bid_invite($this->userData['user_id'],$data->bid_id,$data->status);
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
        redirect('Bidder');
      }
      $data = $data[0];
      $data->user_id = $this->userData['user_id'];
      $this->load->view('bidder/bid_page',$data);
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

    public function update_bid_amount(){
      try{
        $data = $this->input->post('data');
        $data = json_decode($data);
        $res = $this->Bidder_model->update_bid_amount($this->userData['user_id'],$data->bid_id,$data->amount);
        if($res == 1){
          echo "success";
        }else{
          throw new Exception($res);
        }
      }catch(Exception $e){
        echo "Error: ".$e->getMessage();
      }
    }

}
?>