<!DOCTYPE html>
<html lang="en">
<head>
  <title>The Bidding App</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center">
  <h3>Collaborative Bidding Engine</h3>
  <p>Created By: Pranjal Batra</p> 
</div>
  
<div class="container">
  <div class="row" id="main">
    <div class="col-sm-6">
      <h3><button onclick="toggleView(1,2)" class="btn btn-info btn-block btn-lg">Log In as Bidder <i class="fa fa-user"></i></button></h3>
    </div>
    <div class="col-sm-6">
      <h3><button onclick="toggleView(1,1)" class="btn btn-primary btn-block btn-lg">Log In as Bid Creator <i class="fa fa-user-plus"></i></button></h3>
    </div>
  </div>

  <div class="row" id="log_in" style="display:none">
    <h3>Log In as : <span id="login_as"></h3>
    <div class="col-md-12 text-center">
      <div class="form-group">
        <label for="usn">Username:</label>
        <input type="text" class="form-control" id="username">
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="password">
      </div>
      <button onclick="toggleView(0)" class="btn btn-danger">Back</button>
      <button onclick="manage_user(0)" class="btn btn-default">Log In <i class="fa fa-sign-in"></i></button>
      <hr>
      <h5>New User? </h5>
      <button onclick="toggleView(2)" class="btn btn-warning btn-block btn-lg">Create <i class="fa fa-plus"></i></button>
    </div>
  </div>

  <div class="row" id="sign_up" style="display:none">
    <h3>Sign Up as : <span id="signup_as"></h3>
    <div class="col-md-12 text-center">
      <div class="form-group">
        <label for="usn">Name:</label>
        <input type="text" class="form-control" id="name">
      </div>
      <div class="form-group">
        <label for="uname">Username:</label>
        <input type="text" class="form-control" id="uname">
      </div>
      <div class="form-group">
        <label for="pass">Password:</label>
        <input type="password" class="form-control" id="pass">
      </div>
      <button onclick="toggleView(0)" class="btn btn-danger">Back</button>
      <button onclick="manage_user(1)" class="btn btn-default">Sign Up</button>
    </div>
  </div>

</div>
<script>
  var utype = 0;
  var usr = '';
  function toggleView(arg1,arg2 = null){
    if(arg2 != null){
      utype = arg2;
      if(arg2 == 1){
        usr = "Bid Creator";
      }else if(arg2 == 2){
        usr = "Bidder";
      }
      $('#login_as').html(usr);
      $('#signup_as').html(usr)
    }
    if(arg1 == 0){
      $('#main').css('display','block');
      $('#log_in').css('display','none');
      $('#sign_up').css('display','none');
    }else if(arg1 == 1){
      $('#main').css('display','none');
      $('#log_in').css('display','block');
      $('#sign_up').css('display','none');
    }else if(arg1 == 2){
      $('#main').css('display','none');
      $('#log_in').css('display','none');
      $('#sign_up').css('display','block');
    }
  }

  function manage_user(arg){
    let url = '';
    let data = {};
    if(arg == 0){
      // authenticate user
      url = 'auth_user';
      data.username = $('#username').val();
      data.password = $('#password').val();
    }else if(arg == 1){
      // create user
      url = 'create_user';
      data.username = $('#uname').val();
      data.name = $('#name').val();
      data.password = $('#pass').val();

    }
    $.post(
        "<?php echo base_url(); ?>User/"+url+"/"+utype,
        {data:JSON.stringify(data)},
        function(data,status){
            if(status == 'success' && !data.includes('Error:')){
              if(arg == 0){
                window.location.href = "<?php echo base_url(); ?>"+data;
              }else if(arg == 1){
                toggleView(1,utype);
              }
            }else{
                alert(data);
            }
        }
    );

  }

</script>
</body>
</html>


