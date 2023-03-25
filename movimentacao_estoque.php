<?php
$page_title = 'Sugestão de Compra';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);

$all_movimentacao = find_movimentacao_estoque();

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
        <input class="form-control" id="search_movimentacao" type="text" placeholder="Busca..">
      </div>
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Movimentação de Estoque</span>
      </strong>
    </div>
    <div class="panel-body">
      <table class="table table-bordered" id="tableCompra">
        <thead>
          <tr>
            <th class="text-center">Produto</th>
            <th class="text-center">Data Hora</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Estoque</th>
            <th class="text-center">Estoque Anterior</th>
          </tr>
        </thead>
        <tbody id="tdbodyMovimentacao">
          <?php foreach ($all_movimentacao as $row) : ?>
            <tr>
              <td><?php echo remove_junk($row['name']); ?></td>
              <td class="text-center"><?php echo  read_date_time($row['datahora']); ?></td>
              <td class="text-center"><?php echo remove_junk($row['descricao']); ?></td>
              <td class="text-center"><?php echo (int) $row['estoque']; ?></td>
              <td class="text-center"><?php echo (int) $row['estoqueanterior']; ?></td>
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


<script>
$(document).ready(function(){
  $("#search_movimentacao").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tdbodyMovimentacao tr").filter(function() {
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