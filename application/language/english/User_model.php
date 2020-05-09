<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

    public function sendOtp($phone){
        // fetch country code by location when going international
        $cc = "+91";
        $this->db->select('otp')->from('otp')->where('phone',$phone);
        $q = $this->db->get();
        if($q->num_rows() == 0){
            $otp = rand(1000,9999);
            $data = array(
                'phone' => $phone,
                'otp' => $otp
            );
            $this->db->insert('otp',$data);
        }else{
            $res = $q->result();
            $otp = $res[0]->otp;
        }
        
        // if send sms successful
        return 1;
    }
    public function getUserProfile($user_id,$api_key){  
        $this->db->select('id,name,phone')->from('users')->where('id',$user_id)->where('api_key',$api_key);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            $res = $q->result();
            return $res;
        }else{
            return 0;
        }
    }   
    public function verifyOtp($phone,$otp){
        // delete from otp table
        $this->db->select('id')->from('otp')->where('phone',$phone)->where('otp',$otp);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            $res = $q->result();
            $id = $res[0]->id;
            $this->db->where('id',$id)->delete('otp');
            ////
            $this->db->select('id,name')->from('users')->where('phone',$phone);
            $q = $this->db->get();
            if($q->num_rows() == 1){
                // user exists
                $res = $q->result();
                $user_id = $res[0]->id;
                $user_name = $res[0]->name;
            }else{
                // create new user
                $data = array(
                    "phone" => $phone,
                    "name" => '',
                );
                $this->db->insert('users',$data);
                $user_id = $this->db->insert_id();
                $user_name = '';
            }
            $api_key = md5(time() . 'suck_my_balls@69006069');
            $key = array(
                'api_key' => $api_key
            );
            $this->db->where('id',$user_id)->update('users',$key);
            // send user_id,user_name and api_key 
            $arr = array(
                'user_id' => $user_id,
                'user_name' => $user_name,
                'api_key' => $api_key
            );
            return $arr;
        }else{
            return 0;
        }
    }
    public function logout($user_id,$api_key){
        $this->db->from('users')->where('id',$user_id)->where('api_key',$api_key);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            $key = array(
                'api_key' => ''
            );
            $this->db->where('id',$user_id)->update('users',$key);
            return 1;
        }
    }
    public function validateUser($user_id,$api_key){
        $this->db->from('users')->where('id',$user_id)->where('api_key',$api_key);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            return 1;
        }else{
            return 0;
        }
    }

    public function referFriend($user_id,$friend_phone,$client_id){
        $this->db->from('client_user_synergy')->where('user_id',$user_id)->where('client_id',$client_id);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            $data = array(
                'client_id' => $client_id,
                'user_id' => $user_id,
                'friend_phone_number' => $friend_phone
            );
            $this->db->insert('referrals',$data);
            // find friend_user_id. If it does not exist, sign the user up
            $this->db->select('id')->from('users')
            ->where('phone',$friend_phone);
            $q = $this->db->get();
            if($q->num_rows() == 1){
                $res = $q->result();
                $friend_user_id = $res[0]->id;
            }else{
                $data = array(
                    "phone" => $friend_phone,
                    "name" => '',
                );
                $this->db->insert('users',$data);
                $friend_user_id = $this->db->insert_id();
            }
            // Insert in notifications table + send an sms
            $this->db->select('profile_name,referral_points')
            ->from('client_login')->where('id',$client_id);
            $cn = $this->db->get();
            $clientName = $cn->result();
            $refPoints = $clientName[0]->referral_points;
            $clientName = $clientName[0]->profile_name;
            $this->db->select('name,phone')->from('users')->where('id',$user_id);
            $un = $this->db->get();
            $userName = $un->result();
            $userPhone = $userName[0]->phone;
            $userName = $userName[0]->name;
            if($userName == ""){
                $userName = "(".$userPhone.")";
            }
            $msg = "Visit ".$clientName." and get extra ".$refPoints." points. Referred by ".$userName;
            $data = array(
                'for_type' => 1,
                'for_id' => $friend_user_id,
                'from_type' => 1,
                'from_id' => 2,
                'from_id' => $client_id,
                'msg' => $msg
            );
            $this->db->insert('notifications',$data);
            //send sms
            return 1;
        }else{
            return 0;
        }
    }

    public function sendFeedback($user_id,$client_id,$visit_id,$rating,$comment){
        $this->db->from('user_history')->where('id',$visit_id)
        ->where('user_id',$user_id)->where('client_id',$client_id);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            $data = array(
                'feedback_rating' => $rating,
                'feedback_comment' => $comment
            );
            $this->db->where('id',$visit_id)->update('user_history',$data);
            //check if rating is below 9, send an sms to client's recievers
            if($rating < 9){
                $this->db->select('profile_name,feedback_contacts')->from('client_login')->where('id',$client_id);
                $q = $this->db->get();
                $res = $q->result();
                $contacts = $res[0]->feedback_contacts;
                $bizname = $res[0]->profile_name;
                $contacts = json_encode($contacts);
                if($contacts){
                    $phones = array();
                    foreach($contacts as $contact){
                        $phones[] = $contact->phone;
                    }
                    //send sms to all these contacts
                    $this->db->select("(CONCAT(name,'/',phone) as userDetail")->from('users')
                    ->where('id',$user_id);
                    $q = $this->db->get();
                    $userDetail = $q->result();
                    $userDetail = $userDetail[0]->userDetail;
                    $msg = $userDetail.' has rated '.$rating.' at '.$bizname;
                }
            }
            return 1;
        }else{
            return 0;
        }
    }

    public function recentVisits($user_id){
        $data = array();
        $inserted = array();
        $this->db->select('c.id,c.profile_name,c.logo')->from('user_history uh')
        ->join('client_login c','uh.client_id = c.id')->where('uh.user_id',$user_id)
        ->order_by('uh.id','desc');
        $q = $this->db->get();
        if($q->num_rows()){
            $result = $q->result();
            foreach($result as $res){
                if(in_array($res->id,$inserted)){
                    continue;
                }
                $inserted[] = $res->id;
                $data[] = $res;
            }
        }
        return $data;
    }

    public function getUserHistory($user_id){
        $userHistory = array();
        $this->db->select('c.profile_name,c.logo,uh.id,uh.timestamp,uh.points_given,o.title')->from('uh.user_history')
        ->join('client_login c','uh.client_id = c.id')
        ->join('events_and_offers o','uh.offer_id = o.id','left')
        ->where('uh.user_id',$user_id)
        ->order_by('uh.id','desc');
        $q = $this->db->get();
        if($q->num_rows()){
            $userHistory = $q->result();
        }
        return $userHistory;
    }
    
    public function getUserFeedbacks($user_id){
        $userFeedbacks = array();
        $this->db->select('c.profile_name,c.image,c.logo,uh.id')->from('uh.user_history')
        ->join('client_login c','uh.client_id = c.id')
        ->where('uh.feedback_rating',NULL)->where('uh.user_id',$user_id)
        ->order_by('uh.id','desc');
        $q = $this->db->get();
        if($q->num_rows()){
            $userFeedbacks = $q->result();
        }
        return $userFeedbacks;
    }

    public function getUserNotifications($user_id){
        $notifs = array();
        $this->db->select('*')->from('notifications')->where('for_type',1)
        ->where('for_id'.$user_id)->order_by('id','desc')->limit(20);
        $q = $this->db->get();
        if($q->num_rows()){
            $notifs = $q->result();
        }
        return $notifs;
    }

    public function getClientPage($user_id,$api_key,$client_id,$lat,$lon){
        $data = array();
        $lat = (float)$lat;
        $lon = (float)$lon;
        $user_claimed_offer_array = array();
        $this->load->model('client/ClientProfile_model');
        $loggedIn = 0;
        if($this->validateUser($user_id,$api_key)){
            $loggedIn = 1;
            //get user claimed offer array
            $this->db->select('claimed_offer_array')->from('users')->where('id',$user_id);
            $q = $this->db->get();
            if($q->num_rows() == 1){
                $claimed = $q->result();
                $claimed = $claimed[0]->claimed_offer_array;
                if($arr = json_decode($claimed)){
                    $user_claimed_offer_array = $arr;
                }
            }
        }   
        $this->db->select("id,profile_name,image,logo,links,lat,lon,categories, 
        111.045*haversine(lat,lon,".$lat.", ".$lon.") AS distance_in_km")
        ->from('client_login')->where('id',$client_id);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            $result = $q->result();
            $result = $result[0];
            // get category names:
            $cats = $result->categories;
            if($arr = json_decode($cats)){
                $this->db->select('title')->from('categories')->where_in('id',$arr);
                $q = $this->db->get();
                $cats = $q->result();
                foreach($cats as $cat)
                    $catnames[] = $cat->title;
                $result->categories = $catnames;
            }
            $data['clientData'] = $result;
            $multiple_clients = $this->ClientProfile_model->getMultipleClients($client_id);
            $userPoints = 0;
            if($loggedIn){
                $this->db->select('points')->from('client_user_synergy')
                ->where('client_id',$client_id)->where('user_id',$user_id);
                $q = $this->db->get();
                if($q->num_rows() == 1){
                    $up = $q->result();
                    $userPoints = $up[0]->points;
                }
            }
            $data['userPoints'] = $userPoints;
            // fetch upcoming events
            $events = array();
            $this->db->select('title,json_data,image')->from('events_and_offers')
            ->where('client_id',$client_id)
            ->where('enabled',1)->where('deleted',0)->where('type',0)
            ->where('end_timestamp >',time())
            ->order_by('start_timestamp','asc');
            $q = $this->db->get();
            if($q->num_rows()){
                $evs = $q->result();
                foreach($evs as $ev){
                    $str_date = ''; $end_date = ''; $str_time = ''; $end_time = '';$desc = '';
                    $json_data = $ev->json_data;
                    if($obj = json_decode($json_data)){
                        $str_date = (isset($obj->str_date)) ? $obj->str_date : '';
                        $end_date = (isset($obj->end_date)) ? $obj->end_date : '';
                        $str_time = (isset($obj->str_time)) ? $obj->str_time : '';
                        $end_time = (isset($obj->end_time)) ? $obj->end_time : '';
                        $desc = (isset($obj->desc)) ? $obj->desc : '';
                    }
                    $arr = array(
                        'title' => $ev->title,
                        'image' => $ev->image,
                        'str_date' => $str_date,
                        'end_date' => $end_date,
                        'str_time' => $str_time,
                        'end_time' => $end_time,
                        'desc' => $desc
                    );
                    $events[] = $arr;
                }
            }
            $data["events"] = $events;
            // fetch offers - check branches and stuff
            $multiple_clients = $this->ClientProfile_model->getMultipleClients($client_id);
            $offers = array();
            $this->db->select('id,title,json_data,image,useMultiple')->from('events_and_offers')
            ->where('enabled',1)->where('deleted',0)->where('type',1)
            ->where('end_timestamp >',time())
            ->where('start_timestamp <',time());
            if(!empty($multiple_clients)){
                $this->db->where_in('client_id',$multiple_clients);
            }else{
                $this->db->where('client_id',$client_id);
            }
            $q = $this->db->get();
            if($q->num_rows()){
                $ofs = $q->result();
                foreach($ofs as $ev){
                    if($ev->useMultiple == 0){
                        if(in_array($ev->id,$user_claimed_offer_array)){
                            continue;
                        }
                    }
                    $str_date = ''; $end_date = ''; $str_time = ''; $end_time = '';
                    $forever = 0; $desc = ''; $unlock_points = 0; $offer_points = 0;
                    $json_data = $ev->json_data;
                    if($obj = json_decode($json_data)){
                        $str_date = (isset($obj->str_date)) ? $obj->str_date : '';
                        $end_date = (isset($obj->end_date)) ? $obj->end_date : '';
                        $str_time = (isset($obj->str_time)) ? $obj->str_time : '';
                        $end_time = (isset($obj->end_time)) ? $obj->end_time : '';
                        $forever = (isset($obj->forever)) ? $obj->forever : 0;
                        $unlock_points = (isset($obj->unlock_points)) ? $obj->unlock_points : 0;
                        $offer_points = (isset($obj->offer_points)) ? $obj->offer_points : 0;
                        $desc = (isset($obj->desc)) ? $obj->desc : '';
                    }
                    $arr = array(
                        'title' => $ev->title,
                        'image' => $ev->image,
                        'str_date' => $str_date,
                        'end_date' => $end_date,
                        'str_time' => $str_time,
                        'end_time' => $end_time,
                        'forever' => $forever,
                        'unlock_points' => $unlock_points,
                        'offer_points' => $offer_points,
                        'desc' => $desc
                    );
                    $offers[] = $arr;
                }
            }
            $data["offers"] = $offers;
        }   
        return $data;
    }

    public function getCategoryList(){
        $data = array();
        $this->db->select('*')->from('categories');
        $q = $this->db->get();
        if($q->num_rows()){
            $data = $q->result();
        }
        return $data;
    }

    public function fetchUserHomePage($user_id,$api_key,$lat,$lon,$count,$cat_id,$search){
        $this->load->model('client/ClientProfile_model');
        $clientList = array();
        // check if user is logged in or not
        $loggedIn = 0;
        $user_claimed_offer_array = array();
        if($this->validateUser($user_id,$api_key)){
            $loggedIn = 1;
            //get user claimed offer array
            $this->db->select('claimed_offer_array')->from('users')->where('id',$user_id);
            $q = $this->db->get();
            if($q->num_rows() == 1){
                $claimed = $q->result();
                $claimed = $claimed[0]->claimed_offer_array;
                if($arr = json_decode($claimed)){
                    $user_claimed_offer_array = $arr;
                }
            }
        }
        // fetch clients using lat and long. Calculate using haversine function.
        // limit is 20, add load more after that
        // get offers and events from the client and manage branches
        // get associated category names.
        // if loggedIn , then get points of each user at that business
        // if search keywords present, search tags
        $sql = "SELECT id,profile_name,`image`,logo,links,lat,lon,categories, 111.045*haversine(lat,lon,".$lat.", ".$lon.") AS distance_in_km 
        FROM client_login";
        if($search){
            $keywords = explode(',',$search);
            foreach($keywords as $key => $keyword){
                $keyword = addslashes($keyword);
                if($key == 0){
                    $sql .= " WHERE tags LIKE '%".strtolower($keyword)."%'";
                }else{
                    $sql .= " OR tags LIKE '%".strtolower($keyword)."%'";
                }
            }
        }
        $sql .= " ORDER BY distance_in_km";
        $sql .= " LIMIT ".$count.",20";
        $q = $this->db->query($sql);
        if($q->num_rows()){
            $result = $q->result();
            foreach($result as $res){
                if($cat_id){
                    $categories = $res->categories;
                    if($categories = json_decode($categories)){
                        if(!in_array($cat_id,$categories)){
                            continue;
                        }
                    }else{
                        continue;
                    }
                }
                $cats = $res->categories;
                if($arr = json_decode($cats)){
                    $this->db->select('title')->from('categories')->where_in('id',$arr);
                    $q = $this->db->get();
                    $cats = $q->result();
                    foreach($cats as $cat)
                        $catnames[] = $cat->title;
                    $res->categories = $catnames;
                }
                $client_id = $res->id;
                $userPoints = 0;
                if($loggedIn){
                    $this->db->select('points')->from('client_user_synergy')
                    ->where('client_id',$client_id)->where('user_id',$user_id);
                    $q = $this->db->get();
                    if($q->num_rows() == 1){
                        $up = $q->result();
                        $userPoints = $up[0]->points;
                    }
                }
                $res->userPoints = $userPoints;
                // get client events and offers
                // fetch upcoming events
                $events = array();
                $this->db->select('title,json_data,image')->from('events_and_offers')
                ->where('client_id',$client_id)
                ->where('enabled',1)->where('deleted',0)->where('type',0)
                ->where('end_timestamp >',time())
                ->order_by('start_timestamp','asc')
                ->limit(4);
                $q = $this->db->get();
                if($q->num_rows()){
                    $evs = $q->result();
                    foreach($evs as $ev){
                        $str_date = ''; $end_date = ''; $str_time = ''; $end_time = '';
                        $json_data = $ev->json_data;
                        if($obj = json_decode($json_data)){
                            $str_date = (isset($obj->str_date)) ? $obj->str_date : '';
                            $end_date = (isset($obj->end_date)) ? $obj->end_date : '';
                            $str_time = (isset($obj->str_time)) ? $obj->str_time : '';
                            $end_time = (isset($obj->end_time)) ? $obj->end_time : '';
                        }
                        $arr = array(
                            'title' => $ev->title,
                            'image' => $ev->image,
                            'str_date' => $str_date,
                            'end_date' => $end_date,
                            'str_time' => $str_time,
                            'end_time' => $end_time,
                        );
                        $events[] = $arr;
                    }
                }
                $res->events = $events;
                // fetch offers - check branches and stuff
                $multiple_clients = $this->ClientProfile_model->getMultipleClients($client_id);
                $offers = array();
                $this->db->select('id,title,json_data,image,useMultiple')->from('events_and_offers')
                ->where('enabled',1)->where('deleted',0)->where('type',1)
                ->where('end_timestamp >',time())
                ->where('start_timestamp <',time());
                if(!empty($multiple_clients)){
                    $this->db->where_in('client_id',$multiple_clients);
                }else{
                    $this->db->where('client_id',$client_id);
                }
                $this->db->order_by('rand()');
                $this->db->limit(8);
                $q = $this->db->get();
                if($q->num_rows()){
                    $ofs = $q->result();
                    foreach($ofs as $ev){
                        if($ev->useMultiple == 0){
                            if(in_array($ev->id,$user_claimed_offer_array)){
                                continue;
                            }
                        }
                        $str_date = ''; $end_date = ''; $str_time = ''; $end_time = '';
                        $forever = 0; $unlock_points = 0;
                        $json_data = $ev->json_data;
                        if($obj = json_decode($json_data)){
                            $str_date = (isset($obj->str_date)) ? $obj->str_date : '';
                            $end_date = (isset($obj->end_date)) ? $obj->end_date : '';
                            $str_time = (isset($obj->str_time)) ? $obj->str_time : '';
                            $end_time = (isset($obj->end_time)) ? $obj->end_time : '';
                            $forever = (isset($obj->forever)) ? $obj->forever : 0;
                            $unlock_points = (isset($obj->unlock_points)) ? $obj->unlock_points : 0;
                        }
                        $arr = array(
                            'title' => $ev->title,
                            'image' => $ev->image,
                            'str_date' => $str_date,
                            'end_date' => $end_date,
                            'str_time' => $str_time,
                            'end_time' => $end_time,
                            'forever' => $forever,
                            'unlock_points' => $unlock_points
                        );
                        $offers[] = $arr;
                    }
                }
                $res->offers = $offers;
                $clientList[] = $res;
            }
        }
        return $clientList;
    }


}
?>