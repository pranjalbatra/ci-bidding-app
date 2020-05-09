<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bid_model extends CI_Model{


    public function get_bid_ranking($bid_id){
        $response = array();
        $this->db->select('u.id,u.name,ba.amount,ba.bidder_id')->from('bid_amounts ba')->join('users u','u.id = ba.bidder_id')->where('ba.bid_id',$bid_id)->order_by('ba.amount','desc');
        $q = $this->db->get();
        if($q->num_rows()){
            $response = $q->result();
        }
        return $response;
    }   

    public function get_bid_data($bid_id){
        $response = array();
        $this->db->select('*')->from('bids')->where('id',$bid_id);
        $q = $this->db->get();
        if($q->num_rows()){
            $response = $q->result();
        }
        return $response;
    }

    public function get_bid_items($bid_id){
        $response = array();
        $this->db->select('*')->from('bid_items')->where('bid_id',$bid_id);
        $q = $this->db->get();
        if($q->num_rows()){
            $response = $q->result();
        }
        return $response;
    }

    public function has_bid_ended($bid_id){
    	// check if bid already ended or not
    	$this->db->select('end_time')->from('bids')->where('id',$bid_id);
    	$q = $this->db->get();
    	$res = $q->result();
    	$end_time = $res[0]->end_time;
    	if($end_time > time()){
    		return 0;
    	}else{
    		return 1;
    	}
    }

    public function has_bid_started($bid_id){
    	// check if bid already ended or not
    	$this->db->select('start_time')->from('bids')->where('id',$bid_id);
    	$q = $this->db->get();
    	$res = $q->result();
    	$start_time = $res[0]->start_time;
    	if($start_time > time()){
    		return 0;
    	}else{
    		return 1;
    	}
    }
}
?>
