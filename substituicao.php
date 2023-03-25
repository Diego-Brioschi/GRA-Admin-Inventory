<?php
$page_title = 'Sugestão de Substituição';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);


if(@$_POST['sel_cat']){
  $cat = $_POST['sel_cat'];
}else{
  $cat = 'cat.name';
}
if($cat == 'Todos'){
  $cat = 'cat.name';
}
$all_validate = find_all_validate_by_product($cat);
$categoria = find_all('categories');
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="col-md-12">
  <div class="row">
    <div class="col-sm-4">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Lista de Substituição</span>
      </strong>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <div class="row">
        <form method="POST">
          <div class="col-sm-4">
            <label for="sel_cat">Categoria:</label>
            <select class="form-control" id="sel_cat" name="sel_cat">
              <option selected value="Todos">Todos</option>
              <?php foreach($categoria as $categorias): ?>
              <option value="<?php echo "'".$categorias['name']."'" ?>"><?php echo $categorias['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-sm-2">
            <button type="submit" style="margin-top: 25px;" class="btn btn-success">Filtrar</button>
          </div>
        </form>
        <div class="col-sm-4" style="margin-top: -5px;">
          <label for="search_substituicao">Busca:</label>
          <input class="form-control" id="search_substituicao" type="text" placeholder="Busca..">
        </div>
      </div>
    </div>
    <div class="panel-body">
      <h5>Legenda: <label class="label label-success">Distante de substituir</label><label class="label label-danger">Próximo de substituir</label></h5>
      <table class="table table-bordered" id="tableSubstituicao">
        <thead>
          <th> Usuário </th>
          <th> Produto </th>
          <th> Quantidade </th>
          <th> Data Entrega</th>
          <th> Validade </th>
          <th> Ações</th>
        </thead>
        <tbody id="tdbodySubstituicao">
          <?php foreach ($all_validate as $validade) : ?>
            <tr>
              <td><?php echo $validade['nome']; ?></td>
              <td><?php echo $validade['name']; ?></td>
              <td class="text-center"><?php echo $validade['qty']; ?></td>
              <td class="text-center"><?php echo read_date($validade['date']); ?></td>
              <?php
              $diferenca = strtotime($validade['end_date']) - strtotime(date("Y-m-d"));
              $dias = floor($diferenca / (60 * 60 * 24));
              if ($dias <= 31) {
                $tdBackground = "#FFC9D1";
              }
              if ($dias >= 32) {
                $tdBackground = "#C8F0D4";
              }
              ?>
              <td class="text-center font-weight-bold" style="background-color:<?php echo $tdBackground; ?>"><?php echo date('d/m/Y', strtotime($validade['validate'], strtotime($validade['date']))); ?></td>
              <td>
                <div class="btn-group">
                  <a href="edit_product.php?id=" class="btn btn-info btn-xs" title="Editar" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="delete_product.php?id=" class="btn btn-danger btn-xs" title="Deletar" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </div>
              </td>
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
  $(document).ready(function() {
    $("#search_substituicao").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#tdbodySubstituicao tr").filter(function() {
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