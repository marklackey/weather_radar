<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css"
integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"
integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="./zip_helper.js"></script>
</head>
<body onload="init()">

<!-- Modal Zip Code Form-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Set Zip Code</h4>
      </div>
      <form id="zipForm" action="zip_lookup.php" method="post">

      <div class="modal-body">
        <input id="windowWidth" type="hidden" name="windowWidth" value="800">
        <input id="windowHeight" type="hidden" name="windowHeight" value="600">
        <div class="container">
          <div class="alert alert-warning collapse" id="zip_lookup_err_msg">
            Couldn't find that Zip Code, please try again.
          </div>
        </div>
        Enter a 5-digit Zip Code: <input id="zip" type="text" name="zip" placeholder="e.g. 90210" minlength="5" <?php echo "value=\"",$_POST["zip"],"\"" ?>>
              </div>
      <div class="modal-footer">
        <input type="submit">
      </div>
    </form>

    </div>
  </div>
</div>
<script>
$( "#zipForm" ).validate({
  rules: {
    zip: {
      required: true,
      digits: true,
      minlength: 5,
      maxlength: 5
    }
  }
});
</script>

<!-- Modal instructions -->
<div class="modal fade" id="instructions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      Press 'z' to change zip code.
    </div>
  </div>
</div>
<?php
$time = microtime(true);
$zip_to_latlon = array();
if (($handle = fopen("./zipcode/zipcode.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    if(is_numeric($data[0])&&$data[0]>0&$data[0]<99999) {
      $zip_to_latlon[$data[0]] = array("lat"=>$data[3],"long"=>$data[4]);
    }
  }
  fclose($handle);
}
$lat = $zip_to_latlon[$_POST["zip"]]["lat"];
$long = $zip_to_latlon[$_POST["zip"]]["long"];

if($lat && $long) {
  $width= $_POST["windowWidth"] ? $_POST["windowWidth"] : 400;
  $height= $_POST["windowHeight"] ? $_POST["windowHeight"] : 400;
  echo "<img class=\"img-responsive\" src=","http://api.wunderground.com/api/3d095148a7a4a06e/animatedradar/image.gif?centerlat=",
  urlencode($lat),"&centerlon=",urlencode($long),"&radius=100&width=",$width,"&height=",$height,"&newmaps=1&timelabel=1&timelabel.y=10&num=5&delay=50","/>";
  //echo microtime(true)-$time," milliseconds<BR>";
  echo "<script>
  $('#instructions').modal('show');
  window.setTimeout(function() {
      $('#instructions').modal('hide');
  }, 2000);
  </script>";
}
else {
  echo "
    <script type=\"text/javascript\">
    if($('#zip').val()) {
      $('#zip_lookup_err_msg').show();
    }
    $('#myModal').modal({
        show: true
    });    </script>
";
}
?>
</body>
</html>
