<?php
$page_title = 'Sugestão de Compra';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);

$all_compra = find_sugestion_product_buy();

?>


<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="col-md-12">
  <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <div class="pull-right">
        <input class="form-control" id="search_compra" type="text" placeholder="Busca..">
      </div>
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Sugestão de Compra</span>
      </strong>
    </div>
    <div class="panel-body">
    <h5>Legenda: <label class="label label-success">Sem Nescessidade !</label><label class="label label-danger">Nescessita Compra !</label></h5>
      <table class="table table-bordered" id="tableCompra">
        <thead>
          <tr>
            <th>Produto</th>
            <th class="text-center">Quantidade em Estoque</th>
            <th class="text-center">Estoque Minimo</th>
            <th class="text-center">Nescessidade de Compra</th>
          </tr>
        </thead>
        <tbody id="tdbodyCompras">
          <?php foreach ($all_compra as $row) : ?>
            <tr>
              <td><?php echo remove_junk($row['name']); ?></td>
              <td class="text-center"><?php echo (int) $row['quantity']; ?></td>
              <td class="text-center"><?php echo (int) $row['stock_min']; ?></td>
              <td class="text-center"><?php if($row['sugestion'] < 0){ echo 0; }else { echo (int) $row['sugestion']; } ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js" integrity="sha512-/xmIG37mK4F8x9kBvSoZjbkcQ4/y2AbV5wv+lr/xYhdZRjXc32EuRasTpg7yIdt0STl6xyIq+rwb4nbUmrU/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>

<script type="text/javascript">
$(function() {
  $("#tableCompra td").each(function() {
    var currentRow=$(this).closest("tr"); 
    var nescessidade = currentRow.find("td:eq(3)").text();
    var minimo = currentRow.find("td:eq(2)").text();
    if (nescessidade.toString() <= '0') {
      var id = $(this).closest('td').parent()[0].sectionRowIndex;
      $('#tableCompra tbody tr').eq(id).css('background-color', '#C8F0D4');
    }
    if((nescessidade.toString() >= '1')){
      var id = $(this).closest('td').parent()[0].sectionRowIndex;
      $('#tableCompra tbody tr').eq(id).css('background-color', '#FFC9D1');
    }

  });
});
</script>

<script>
$(document).ready(function(){
  $("#search_compra").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tdbodyCompras tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

<script>
  $(document).on('click', '.btn_toogle_menu', function() {
    $('#mySidebar').toggle();
    $(".page").removeClass("page");
    $('.container-fluid').prepend('<button class="btn btn-primary btn-lg btn_right_toogle" style="margin-bottom:10px;"><span class="glyphicon glyphicon-circle-arrow-right"></span></button>');
    $(".container-fluid").addClass("container_fluid_menu");
  });
  $(document).on('click', '.btn_right_toogle', function() {
    $('#mySidebar').toggle();
    $("#page").addClass("page");
    $('.btn_right_toogle').hide();
    $(".container-fluid").removeClass("container_fluid_menu");
  });
</script>