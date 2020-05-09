<!DOCTYPE html>
<html lang="en">
<head>
  <title>The Bidding App</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<div class="jumbotron text-center">
  <h4>Create a new bid</h4>
</div>
  
<div class="container">
  <div class="row" id="main">
    <!--  -->
    <div class="col-md-12 text-center">
      <div class="form-group">
        <label for="title">Bid Title:</label>
        <input type="text" class="form-control" id="bid_title">
      </div>
      <div class="form-group">
        <label for="strd">Start Date:</label>
        <div class='input-group date' id='start_date'>
          <input type='text' class="form-control" id="start_time" />
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>
      <div class="form-group">
        <label for="endd">End Date:</label>
        <div class='input-group date' id='end_date'>
          <input type='text' class="form-control" id="end_time"/>
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>
      <h3>Add Bid Items:</h3>
      
      <div id="rangebox-hidden" style="display:none">
        <div class="form-group">
          <label for="title">Item Title:</label>
          <input type="text" class="form-control" name="item_title[]">
        </div>
         <div class="form-group">
          <label for="title">Item Description:</label>
          <textarea class="form-control" name="item_desc[]"> </textarea>
        </div>
      </div>
      <div id = "ranges">
        <div id="rangebox0">
          <div class="form-group">
            <label for="title">Item Title:</label>
            <input type="text" class="form-control" name="item_title[]">
          </div>
           <div class="form-group">
            <label for="title">Item Description:</label>
            <textarea class="form-control" name="item_desc[]"> </textarea>
          </div>
          <button onclick="remove('rangebox0')" type="button" class="btn btn-danger mt-4 btn-block">Remove <i class="fa fa-minus"></i></button>
          <hr>
        </div>
      </div>

      <div class="text-center">
          <button onclick="addmore()" type="button" class="btn btn-warning mt-4 btn-block">Add More <i class="fa fa-plus"></i></button>
      </div>

      <hr>
      <button onclick="create_bid()" class="btn btn-info btn-lg btn-block">Publish</button>
    </div>
    <!--  -->
  </div>
</div>
<script>
$(function() {
  $('#start_date').datetimepicker();
  $('#end_date').datetimepicker();
});
  
var glob = 0;
function addmore(){
    var box = $('#rangebox-hidden').clone();
    var id = 'rangebox'+glob; glob++;
    box.attr('id',id);
    box.append(`<button onclick="remove('`+id+`')" type="button" class="btn btn-danger mt-4 btn-block">Remove <i class="fa fa-minus"></i></button>`);
    var html = `<div id="`+id+`">`+box.html()+`<hr></div>`
    $('#ranges').append(html);
}
function remove(id){
    id = '#'+id;
    $(id).remove();
}

function create_bid(){
  let data = {
    title:$('#bid_title').val(),
    start_time:$('#start_time').val(),
    end_time:$('#end_time').val()
  };
  let item_titles = $('input[name ="item_title[]"]').map(function(){return $(this).val();}).get();
  let item_descs = $('textarea[name ="item_desc[]"]').map(function(){return $(this).val();}).get();

  let items = [];
  item_titles.forEach(function(val,key){
    if(val != "" && item_descs[key] != ""){
      items.push({
        title:val,
        description: item_descs[key]
      })
    }
  })
  if(items.length == 0){
    alert('Please add more than 1 items');
    return;
  }
  data.items = items;
  console.log(data);
  $.post(
        "<?php echo base_url(); ?>Creator/create_new_bid",
        {data:JSON.stringify(data)},
        function(data,status){
            if(status == 'success' && !data.includes('Error:')){
              window.location.href = "<?php echo base_url(); ?>creator";
            }else{
              alert(data);
            }
        }
    );
}

</script>
</body>
</html>


