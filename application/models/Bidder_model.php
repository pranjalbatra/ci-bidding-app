<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bidder_model extends CI_Model{

    public function get_bid_invites($user_id,$status){ 
    	// status = 0 -> Pending, status = 1 => Accepted, 2 = Rejected
    	$this->load->model('Bid_model');
    	$response = array();
    	$this->db->select('b.*')->from('bid_invites bi')->join('bids b','b.id = bi.bid_id')->where('bi.bidder_id',$user_id)->where('bi.status',$status);
    	$q = $this->db->get();
    	if($q->num_rows()){
    		$res = $q->result();
    		foreach ($res as $r) {
    			$items = $this->Bid_model->get_bid_items($r->id);
    			$r->items = $items;
    			$response[] = $r;
    		}
    	}
    	return $response;
    }

    public function manage_bid_invite($user_id,$bid_id,$status){
    	$this->load->model('Bid_model');
    	if($this->Bid_model->has_bid_ended($bid_id)){
    		return 'This bid has already ended.';
    	}
    	$data = array(
    		'status' => $status
    	);
    	$this->db->where('bidder_id',$user_id)->where('status',0)->where('bid_id',$bid_id)
    	->update('bid_invites',$data);
    	return $this->db->affected_rows();
    }

    public function update_bid_amount($user_id,$bid_id,$amount){
    	$this->load->model('Bid_model');
    	if($this->Bid_model->has_bid_ended($bid_id)){
    		return 'This bid has already ended.';
    	}
    	// check if bid invite accepted, only then be able to add/update amount
    	$this->db->from('bid_invites')->where('bid_id',$bid_id)->where('bidder_id',$user_id)->where('status',1);
    	$q = $this->db->get();
    	if($q->num_rows() != 1){
    		return 'Please accept bid invite before adding the amount.';
    	}
    	// Check if bid has started or not
    	if($this->Bid_model->has_bid_started($bid_id) == 0){
    		return "Bidding has not started yet.";
    	}
    	// Get the current max bid amount to check if the amount being entered is greater
    	$amt = 0;
    	$this->db->select('MAX(amount) as amount')->from('bid_amounts')->where('bid_id',$bid_id);
    	$q = $this->db->get();
    	if($q->num_rows() == 1){
    		$res = $q->result();
    		if($res[0]->amount != NULL){
    			$amt = $res[0]->amount;
    		}
    	}
    	if($amount <= $amt){
    		return "Please enter an amount greater than ".$amt;
    	}
    	// check if an amount is already added. 
    	$data = array(
    		'amount' => $amount
    	);
    	$this->db->from('bid_amounts')->where('bid_id',$bid_id)->where('bidder_id',$user_id);
    	$q = $this->db->get();
    	if($q->num_rows() == 1){
    		$this->db->where('bidder_id',$user_id)->where('bid_id',$bid_id)->update('bid_amounts',$data);
    	}else{
    		$data['bidder_id'] = $user_id;
    		$data['bid_id'] = $bid_id;
    		$this->db->insert('bid_amounts',$data);
    	}
    	return 1;
    }
}
?>
