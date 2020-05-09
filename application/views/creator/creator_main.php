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
  <h3>Logged In as Bid Creator</h3>
  <p>Welcome: <?php echo $name; ?></p>
  <h6><a href="<?php echo base_url(); ?>User/logout" class="btn btn-danger">Log Out <i class="fa fa-sign-out"></i></a></h6> 
</div>
  
<div class="container">
  <div class="row" id="main">
    <div class="col-lg-6">
      <h3><a href="<?php echo base_url(); ?>creator/create_bid" class="btn btn-primary btn-block btn-lg">Create a New Bid <i class="fa fa-plus"></i></a></h3>
    </div>
  </div>
  <hr>
  <h3>My Bids:</h3>
  <table class="table">
    <tr>
      <th>Title</th>
      <th>Display Items</th>
      <th>Start Date/Time</th>
      <th>End Date/Time</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    <?php foreach($allBids as $key => $bid){ ?>
        <tr>
          <td><?php echo $bid->title; ?></td>
          <td><button onclick="showItems(`<?php echo $key; ?>`)" class="btn btn-warning">Show</button></td>
          <td><?php echo date('d M Y H:i',$bid->start_time); ?></td>
          <td><?php echo date('d M Y H:i',$bid->end_time); ?></td>
          <td><?php echo ($bid->end_time < time() ? 'Expired' : 'Active') ?></td>
          <td><a class="btn btn-success" href="<?php echo base_url(); ?>creator/bid_page/<?php echo $bid->id; ?>"><?php echo ($bid->end_time < time() ? 'View Summary' : ($bid->start_time > time()) ? 'Waiting to Start' : 'Monitor' ) ?></a></td>
        </tr>
    <?php } ?>
  </table>
</div>

 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Bid Items</h4>
        </div>
        <div class="modal-body" id="modal_body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<script>
  let bids = `<?php echo json_encode($allBids); ?>`;
  bids = JSON.parse(bids);
  function showItems(index){
    $('#myModal').modal('show');
    $('#modal_body').html("");
    let items = bids[index].items;
    items.forEach(function(val,key){
      let html = `
        <div>
        <h3>Item `+(key+1)+`</h3>
          <h5>Title:</h5> `+val.title+`<br>
          <h5>Description:</h5> `+val.description+`
          <hr>
        </div>
      `;
      $('#modal_body').append(html);
    })
  }
</script>

</body>
</html>


