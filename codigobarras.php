<?php
$page_title = 'Código de Barras';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);

$codebar = find_all('product_codigobarras');

?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<?php
//declaramos arreglo para guardar codigos
$arrayCodigos = array();
?>

<h2> Código de Barras </h2>

<table class="table table-bordered">
  <tr>
    <td>Produto</td>
    <td>Codigo barras</td>
  </tr>
  <?php
  foreach ($codebar as $row) :
    $arrayCodigos[] = (string)$row['codigobarras'];
  ?>
    <tr>
      <td><?php echo $row['codigobarras']; ?></td>
      <td><svg id='<?php echo "barcode" . $row['codigobarras']; ?>'></td>
    </tr>
  <?php endforeach; ?>
</table>


<script src="libs/js/JsBarcode.all.min.js"></script>

<script type="text/javascript">
  function arrayjsonbarcode(j) {
    json = JSON.parse(j);
    arr = [];
    for (var x in json) {
      arr.push(json[x]);
    }
    return arr;
  }

  jsonvalor = '<?php echo json_encode($arrayCodigos) ?>';
  valores = arrayjsonbarcode(jsonvalor);

  for (var i = 0; i < valores.length; i++) {

    JsBarcode("#barcode" + valores[i], valores[i].toString(), {
      format: "codabar",
      lineColor: "#000",
      width: 2,
      height: 30,
      displayValue: true
    });
  }
</script>


<?php include_once('layouts/footer.php'); ?>