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
    $.post(
        "<?php echo base_url(); ?>Creator/get_bid_ranking",
        {data:JSON.stringify(data)},
        function(data,status){
            if(status == 'success' && !data.includes('Error:')){
              let tbl = JSON.parse(data);
              var html = '';
              for(var i = 0; i < tbl.length; i++){
                html += `
                  <tr>
                    <td>`+(i+1)+`</td>
                    <td>`+tbl[i].name+`</td>
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
</script>

</body>
</html>


