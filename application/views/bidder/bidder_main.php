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
  <h3>Logged In as Bidder</h3>
  <p>Welcome: <?php echo $name; ?></p>
  <h6><a href="<?php echo base_url(); ?>User/logout" class="btn btn-danger">Log Out <i class="fa fa-sign-out"></i></a></h6> 
</div>
  
<div class="container">
  <div class="row" id="main">
  </div>
  <hr>
  <h3>Pending Invites:</h3>
  <table class="table">
    <tr>
      <th>Title</th>
      <th>Display Items</th>
      <th>Start Date/Time</th>
      <th>End Date/Time</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php foreach($pendingBids as $key => $bid){ ?>
        <tr>
          <td><?php echo $bid->title; ?></td>
          <td><button onclick="showItems(`<?php echo $key; ?>`,'p')" class="btn btn-warning">Show</button></td>
          <td><?php echo date('d M Y H:i',$bid->start_time) ?></td>
          <td><?php echo date('d M Y H:i',$bid->end_time) ?></td>
          <td><?php echo ($bid->end_time < time() ? 'Expired' : 'Active') ?></td>
          <td><button onclick="manageInvite(1,`<?php echo $bid->id; ?>`)" class="btn btn-success">Accept</button> <button class="btn btn-danger" onclick="manageInvite(2,`<?php echo $bid->id; ?>`)">Reject</button></td>
        </tr>
    <?php } ?>
  </table>
  <h3>Accepted Bids:</h3>
  <table class="table">
    <tr>
      <th>Title</th>
      <th>Display Items</th>
      <th>Start Date/Time</th>
      <th>End Date/Time</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    <?php foreach($acceptedBids as $key => $bid){ ?>
        <tr>
          <td><?php echo $bid->title; ?></td>
          <td><button onclick="showItems(`<?php echo $key; ?>`,'a')" class="btn btn-warning">Show</button></td>
          <td><?php echo date('d M Y H:i',$bid->start_time) ?></td>
          <td><?php echo date('d M Y H:i',$bid->end_time) ?></td>
          <td><?php echo ($bid->end_time < time() ? 'Expired' : 'Active') ?></td>
          <td><a href="<?php echo base_url(); ?>bidder/bid_page/<?php echo $bid->id; ?>" class="btn btn-success"><?php echo ($bid->end_time < time() ? 'View Summary' : ($bid->start_time > time()) ? 'Waiting to Start' : 'Start Bidding' ) ?></a></td>
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
  let p_bids = `<?php echo json_encode($pendingBids); ?>`;
  let a_bids = `<?php echo json_encode($acceptedBids); ?>`;
  p_bids = JSON.parse(p_bids);
  a_bids = JSON.parse(a_bids);
  function showItems(index,arg){
    $('#myModal').modal('show');
    $('#modal_body').html("");
    let items = [];
    if(arg == 'p'){
      items = p_bids[index].items
    }else if(arg == 'a'){
      items = a_bids[index].items
    }
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
  function manageInvite(val,bid_id){
    let data = {
      status:val,
      bid_id:bid_id
    }
    if (confirm('Are you sure ?')){
        $.post(
        "<?php echo base_url(); ?>Bidder/manage_bid_invite",
        {data:JSON.stringify(data)},
        function(data,status){
            if(status == 'success' && !data.includes('Error:')){
              window.location.reload();
            }else{
              alert(data);
            }
        }
    );
    }
  }
</script>

</body>
</html>


