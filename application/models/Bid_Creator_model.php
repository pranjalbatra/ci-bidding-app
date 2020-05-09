<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bid_Creator_model extends CI_Model{

    public function create_bid($user_id,$title,$start_time,$end_time,$bid_items){
    	if($start_time > $end_time){
    		return "Start time cannot be greater than the end time";
    	}
    	$data = array(
    		"creator_id" => $user_id,
    		"title" => $title,
    		"start_time" => strtotime($start_time),
    		"end_time" => strtotime($end_time)
    	);
    	$this->db->insert('bids',$data);
    	$bid_id = $this->db->insert_id();
    	// insert all bid items
    	foreach($bid_items as $item){
    		$itm = array(
    			'bid_id' => $bid_id,
    			'title' => $item->title,	
    			'description' => $item->description
    		);
    		$this->db->insert('bid_items',$itm);
    	}
    	// Send bid invites to all bidders
    	$this->db->select('id')->from('users')->where('type',2);
    	$q = $this->db->get();
    	if($q->num_rows()){
    		$bidders = $q->result();
    		foreach($bidders as $b){
    			$invite = array(
    				'bid_id' => $bid_id,
    				'bidder_id' => $b->id
    			);
    			$this->db->insert('bid_invites',$invite);
    		}
    	}
    	return 1;
    }

    public function get_all_bids($user_id){
    	$this->load->model('Bid_model');
    	$response = array();
    	$this->db->select('*')->from('bids')->where('creator_id',$user_id)->order_by('id','desc');
    	$q = $this->db->get();
    	if($q->num_rows() > 0){
    		$res = $q->result();
    		foreach ($res as $r) {
    			$items = $this->Bid_model->get_bid_items($r->id);
    			$r->items = $items;
    			$response[] = $r;
    		}
    	}
    	return $response;
    }
}
?>
