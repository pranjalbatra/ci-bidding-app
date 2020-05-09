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
  <h3>Bid Leader Board</h3>
  <h4>Title:<p><?php echo $title; ?></p></h4>
  <h5>Start: <p><?php echo date('d M Y H:i',$start_time); ?></p></h5>
  <h5>End: <p><?php echo date('d M Y H:i',$end_time); ?></p></h5>
</div>
  
<div class="container">
  <div class="row" id="main">
    <div class="col-md-6">
      <h4>Enter Bid Amount:</h4>
      <div class="form-group">
        <input type="number" id="amount" class="form-control"><br>
        <button onclick="submitBid()" class="btn btn-primary btn-lg btn-block">Submit Amount</button>
      </div>
    </div>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody id="tbl_data">
     
    </tbody>
  </table>
</div>

<script>
  function get_bid_ranking(){
    let data = {
      bid_id:<?php echo $id; ?>
    };
    let user_id = <?php echo $user_id; ?>;
    $.post(
        "<?php echo base_url(); ?>Bidder/get_bid_ranking",
        {data:JSON.stringify(data)},
        function(data,status){
            if(status == 'success' && !data.includes('Error:')){
              let tbl = JSON.parse(data);
              var html = '';
              for(var i = 0; i < tbl.length; i++){
                html += `
                  <tr `+(user_id == tbl[i].bidder_id ? ` class="info"` : ``)+`>
                    <td>`+(i+1)+`</td>
                    <td>`+tbl[i].name+(user_id == tbl[i].bidder_id ? ` (Me)` : ``)+`</td>
                    <td>`+tbl[i].amount+`</td>
                  </tr>
                `;
              }
              $('#tbl_data').html(html);
            }else{
              alert(data);
            }
        }
    );
  }
  get_bid_ranking();
  <?php if($start_time < time() && $end_time > time()){ ?>
    console.log('timeout started');
    setInterval(function(){ get_bid_ranking(); }, 15000);
  <?php } ?>
  function submitBid(){
    let data = {
      bid_id:<?php echo $id; ?>,
      amount:$('#amount').val()
    };
     $.post(
        "<?php echo base_url(); ?>Bidder/update_bid_amount",
        {data:JSON.stringify(data)},
        function(data,status){
            if(status == 'success' && !data.includes('Error:')){
              get_bid_ranking();
            }else{
              alert(data);
            }
        }
    );
  }
</script>

</body>
</html>


