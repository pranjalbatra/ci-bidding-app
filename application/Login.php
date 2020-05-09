<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
    
    public function __construct() {

        parent::__construct();
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->model('Survey_users');
        $this->load->database();
        
    }

    public function index()
    {   
        if($this->session->userdata("uid")){
            redirect("Dashboard");
        }

        
        if($this->input->cookie("OPINIACensus")){

            $hash = $this->input->cookie("OPINIACensus");
            $hash = explode("-",$hash);
            if(sizeof($hash)!=3){
                $domain = base_url();
                $path = '/';
                delete_cookie("OPINIACensus",$domain,$path);
                redirect("Login");
            }
            $email = $hash[1];

            $emailChk = md5("yousonofabitch".$email);
            if($emailChk != $hash[0]){
                $domain = base_url();
                $path = '/';
                delete_cookie("OPINIACensus",$domain,$path);
                redirect("Login");
            }

            $this->db->select("ID,subscription_expiry,subscription_type")->from("client_login")->where("email_id",$email);
            $q = $this->db->get();
            $result = $q->result();
            $res = $result[0];
            $hashChk = md5("censusbitchassclient".md5($res->ID));

            if($hashChk == $hash[2]){
            	// check if subscription expired , log out user
            	$time = time();
            	if($res->subscription_expiry < $time){
            		$domain = base_url();
	                $path = '/';
	                delete_cookie("OPINIACensus",$domain,$path);
	                redirect("Login");
            	}
            	$this->session->set_userdata("subscription_type",$res->subscription_type);
                $this->session->set_userdata("uid",$res->ID);
                redirect("Dashboard");

            }

            

        }

        $this->load->view('login');
    }

    public function synced_account_login(){
        $uid = $this->session->userdata("uid");
        if($uid){
            $sync_id = $this->input->post("sync_id");
            //check if synced
            $this->db->from("client_account_sync")->where("client_id",$uid)->where("sync_client_id",$sync_id);
            $q = $this->db->get();
            if($q->num_rows() != 1){
                echo "An error occoured";
                exit;
            }
            $this->db->select("ID,subscription_expiry,subscription_type")->from("client_login")->where("ID",$sync_id);
            $q = $this->db->get();
            $result = $q->result();
            $res = $result[0];
            $time = time();
            if($res->subscription_expiry < $time){
                echo "Your subscription has expired. Please renew your subscription";
                exit;
            }
            $this->session->unset_userdata("survey_edit");
            $this->session->unset_userdata("survey_analytics");
            $this->session->set_userdata("subscription_type",$res->subscription_type);
            $this->session->set_userdata("uid",$res->ID);
            $this->db->where('id', $res->ID);
            $this->db->update('client_login', array('api_key'=> hash('ripemd160', time())));
            echo "success";
            exit;
        }else{
            echo "Please Login again";
            exit;
        }
    }

    public function client_login(){

        if($this->input->post("email_id") && $this->input->post("password")){

            $email = $this->input->post("email_id");
            $password = $this->input->post("password");
            $password = md5($password);

            $this->db->select("ID,subscription_expiry,subscription_type")->from("client_login")->where("email_id",$email)->where("password",$password);

            $q = $this->db->get();
            if($q->num_rows() == 1){


                $res = $q->result();
                $time = time();
            	if($res[0]->subscription_expiry < $time){
            		echo "Your subscription has expired. Please renew your subscription";
            		exit;
                }
                
                $this->db->where('id', $res[0]->ID);
                $this->db->update('client_login', array('api_key'=> hash('ripemd160', time())));

                $this->session->set_userdata("uid",$res[0]->ID);
                $this->session->set_userdata("subscription_type",$res[0]->subscription_type);
                $this->Survey_users->setDefaultFilters($res[0]->ID);
                $rem = $this->input->post("rem_me");
                
                if($rem == "true"){
                    
                    $cookie = array(
                        
                        'name'   => 'OPINIACensus',
                        'value'  => md5("yousonofabitch".$email)."-".$email."-".md5("censusbitchassclient".md5($res[0]->ID)),
                        'expire' => 31557600,
                        'domain' => base_url(),
                        'path' => '/', 
                        'secure' => TRUE
                        );
                      
                    $this->input->set_cookie($cookie);	
                }
 
                echo "success";
                exit;

            }else{
                echo "Invalid Email Id or Password";
                exit;
            }

        }else{
            echo "Invalid Access";
            exit;

        }

   }

   public function forgotPassword(){

    if($email = $this->input->post("forgotEmail")){

        $this->db->select("*")->from("client_login")->where("email_id",$email);
        $q = $this->db->get();

        if($q->num_rows()==1){

            $res = $q->result();
            //send mail with secret link
            $id = $res[0]->ID;
            $secret = $id."user".time();
            $secret = md5($secret);

            $msg = "Click on this link to reset password ".base_url()."Login/reset_password/".$secret;
            $headers = 'From: no-reply@opiniacensus.com' . "\r\n" .
          'X-Mailer: PHP/' . phpversion();
          
          if(mail($email,"Reset OPINIACensus Password",$msg,$headers) == 1){
            $data = array(
                "client_id" => $res[0]->ID,
                "secret" => $secret
            ); 

            $this->db->insert("reset_password",$data);

            echo "success";
            exit;

          }else{
            echo "Invalid Email";
            exit;
          }


        }else{

            echo "Incorrect email";
            exit;
        }


    }else{
        echo "Please provide a valid email";
        exit;
    }


   }

   public function reset_password(){

    $secret = $this->uri->segment(3);
    $data = array();
    if(!$secret){
        echo "Invalid Link";
        exit;
    }

    $this->db->select("*")->from("reset_password")->where("secret",$secret);
    $q = $this->db->get(); 

    if($q->num_rows()==1){

        $reset_info = $q->result();

        if($reset_info[0]->secret == $secret){

            $data = array(
                "secret" => $secret,
                "client_id" => $reset_info[0]->client_id

                );

        }else{
            echo "Invalid link";
            exit;
        }

    }else{
        echo "An error has occoured";
        exit;
    }

    $this->session->set_userdata("reset_info",$data);

    $this->load->view("create-pwd",$data);

   }

   public function resetPassword(){

    if($this->input->post()){

        if($reset_info = $this->session->userdata("reset_info")){

            $secret = $this->input->post("secret");
            $client_id = $this->input->post("client_id");

            if($reset_info["secret"] == $secret && $reset_info["client_id"] == $client_id){

                
                $new_pass = $this->input->post("new_password");

                $data = array(

                    "password" => md5($new_pass),
                    "api_key" => hash('ripemd160', time())
                    );


                $this->db->where("ID",$client_id);
                $this->db->update("client_login",$data);

                if($this->db->affected_rows()==1)
                {   

                    $this->db->where("secret",$secret);
                    $this->db->delete("reset_password");
                    $this->session->unset_userdata("reset_info");

                    echo "success";
                    exit;
                }else{
                    echo "Cannot Update password!";
                    exit;
                }

            }else{
                echo "Invalid link";
                exit;
            }

        }else{
            echo "An error has occoured";
            exit;
        }
    

    }else{
        echo "Invalid Access";
        exit;
    }

   }

   public function logout(){

    if($this->input->cookie("OPINIACensus")){
        $domain = base_url();
        $path = '/';
        delete_cookie("OPINIACensus",$domain,$path);
    }
    $uid = $this->session->userdata("uid");
    $this->db->where('id', $uid);
    $this->db->update('client_login', array('api_key' => ''));
    $this->session->unset_userdata("survey_edit");
    $this->session->unset_userdata("survey_analytics");
    $this->session->unset_userdata("uid");
    redirect("Login");

   }

   public function verifyEmail(){
        if($email = $this->input->post("email")){
            $this->db->from("client_login")->where("email_id",$email);
            $q = $this->db->get();
            if($q->num_rows() == 0){
                echo "success";
                exit;
            }else{
                echo "This Email already exists";
                exit;
            }
        }else{
            redirct("404");
        }       
   }
   
   public function signup(){
        if($this->input->post()){
            $email = $this->input->post("email");
            $pass = $this->input->post("pass");
            $name = $this->input->post("name");
            $bname = $this->input->post("bname");
            $itype = $this->input->post("itype");
            if($email && $pass && $name && $bname && $itype){
                $this->db->from("client_login")->where("email_id",$email);
                $q = $this->db->get();
                if($q->num_rows() == 0){
                    $subs = time();
                    $subs += 2592000;
                    $data = array(
                        "email_id" => $email,
                        "password" => md5($pass),
                        "client_name" => $name,
                        "industry_type" => $itype,
                        "subscription_expiry" => $subs,
                        "api_key" => hash('ripemd160', time())
                        );
                    $this->db->insert("client_login",$data);
                    if($this->db->affected_rows() == 1){
                        $clid = $this->db->insert_id();
                        $data = array(
                            "client_id" => $clid,
                            "brand_name" => $bname
                            );
                        $this->db->insert("branding",$data);
                        //get subscription type
                        $stype = 0; // trial period
                        $this->session->set_userdata("subscription_type",$stype);
                        $this->session->set_userdata("uid",$clid);
                        $this->Survey_users->setDefaultFilters($clid);
                        echo "success";
                        exit;
                    }
                }else{
                    echo "This Email already exists";
                    exit;
                }   
                // echo "success";
                // exit;
            }else{
                echo "All fields are required";
                exit;
            }
        }       
   } 
    
}

?>