<?php
$page_title = 'Titulo';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);

?>


<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div id="camera"></div>
<div id="qr-reader" style="width: 600px"></div>

</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js" integrity="sha512-/xmIG37mK4F8x9kBvSoZjbkcQ4/y2AbV5wv+lr/xYhdZRjXc32EuRasTpg7yIdt0STl6xyIq+rwb4nbUmrU/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>
<!--<script src="libs/js/quaggajs/quagga.min.js"></script>-->
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

<!--<script>
  Quagga.init({
    inputStream: {
      name: "Live",
      type: "LiveStream",
      target: document.querySelector('#camera'),
      constraints: {
        width: {min: 1280},
        height: {min: 720},
		aspectRatio: {min: 1, max: 100},
        facingMode: 'environment', // or user
      },
    },
    locator: {
      halfSample: true,
      patchSize: 'medium',
    },
    numOfWorkers: 4,
	frequency: 10,
    decoder: {
      readers: [
        "code_128_reader",
        "ean_reader",
        "ean_8_reader",
        "code_39_reader",
        "code_39_vin_reader",
        "codabar_reader",
        "upc_reader",
        "upc_e_reader",
        "i2of5_reader"
      ],
    },
    locate: true,
  }, function(err) {
    if (err) {
      console.log(err);
      return
    }
    console.log("Initialization finished. Ready to start");
    Quagga.start();
  });

  Quagga.onDetected(function(data) {
    alert(data.codeResult.code);
  });
</script>-->

<script>
function onScanSuccess(decodedText, decodedResult) {
    console.log(`Code scanned = ${decodedText}`, decodedResult);
}
var html5QrcodeScanner = new Html5QrcodeScanner(
	"qr-reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess);
</script>