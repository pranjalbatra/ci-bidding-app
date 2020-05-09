<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

    public function create_user($username,$password,$name,$type){
    	$response = array();
    	//Check if a user with this username and type already exists.
    	$this->db->from('users')->where('username',$username)->where('type',$type);
    	$q = $this->db->get();
    	if($q->num_rows() == 1){
    		$response['status'] = 0;
    		$response['msg'] = "This username already exists.";
    		return $response;
    	}
    	//Create a new user.
    	$password = md5("ThIs_Is_@_SAlt".$password);
    	$data = array(
    		"name" => $name,
    		"username" => $username,
    		"password" => $password,
    		"type" => $type
    	);
    	$this->db->insert("users",$data);
    	$response['status'] = 1;
    	$response['msg'] = "User created successfully";
    	return $response;
    }

    public function auth_user($username,$password,$type){
    	// Authenticate user and return data to be stored in session variables
    	$response = array();
    	$password = md5("ThIs_Is_@_SAlt".$password);
    	$this->db->select('id')->from('users')->where('username',$username)->where('password',$password)->where('type',$type);
    	$q = $this->db->get();
    	if($q->num_rows() == 1){
    		$res = $q->result();
    		$response['status'] = 1;
    		$response['msg'] = "Success.";
    		$response['uid'] = $res[0]->id;
    		$response['type'] = $type;
    	}else{
    		$response['status'] = 0;
    		$response['msg'] = "Invalid username or password.";
    	}
    	return $response;
    }

    public function validate_user($user_id,$type){
    	//Check if user data is valid or not
    	$this->db->select('id,name')->from('users')->where('id',$user_id)->where('type',$type);
    	$q = $this->db->get();
    	if($q->num_rows() == 1){
    		$res = $q->result();
    		return $res[0];	
    	}else{
    		return 0;
    	}
    }

}
?>
