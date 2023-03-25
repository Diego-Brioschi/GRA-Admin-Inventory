<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ERROR & ~E_NOTICE);

$page_title = 'Histórico do Produto';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>

<?php
if (isset($_POST['p_codigobarras'])) {
  $codigoarras = $_POST['p_codigobarras'];
  $product = historico_produto($codigoarras);
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-2">
    <div class="form-group">
      <label for="sel_loja">Selecione a Loja:</label>
      <select class="form-control" id="sel_loja">
        <option value="FARMAIS DRACENA">1</option>
        <option value="ULTRA DRACENA">2</option>
        <option value="FARMAIS VENCESLAU">3</option>
        <option value="FARMAIS JUNQUEIROPOLIS">4</option>
        <option value="BIG PANORAMA">5</option>
        <option value="BIG EPITACIO">6</option>
        <option value="DROGAREIS">7</option>
        <option value="BIG ANASTACIO">8</option>
        <option value="BIG KIDS ANASTACIO">9</option>
        <option value="BIG PACAEMBU">10</option>
        <option value="ULTRA VENCESLAU">11</option>
        <option value="ULTRA EPITACIO">12</option>
        <option value="BIG TUPI PAULISTA">13</option>
        <option value="BIG BATAGUASSU">15</option>
      </select>
    </div>
    <button type="button" class="btn btn-primary" style="margin-bottom: 5px;" id="btn_search">Procurar</button>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Imobilizados em Loja</span>
        </strong>
      </div>
      <div class="panel-body">
        <div id="div_relatorio_imobilizado"></div>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center"> Imobilzado </th>
              <th class="text-center"> Quantidade </th>
              <th class="text-center"> Estado </th>
              <th class="text-center"> Substituição </th>
            </tr>
          </thead>
          <tbody id="tbody_imobilizados">
          </tbody>
        </table>
      </div>
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
  $( document ).ready(function() {
    $(document).on('click', '#btn_search', function(e) {
      var sel_loja = $('#sel_loja :selected').val();
      $.ajax({
        url: "scripts/relatorios/get_imobilizados_loja.php",
        data: {
          sel_loja: sel_loja
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          var td;
          if(json.length >= 1){
            json.forEach(function(e, index) {
              td += '<tr>';
              td += ' <td class="text-center">' + e.name + '</td>';
              td += ' <td class="text-center">' + e.quantity + '</td>';
              td += ' <td class="text-center">' + e.estado + '</td>';
              var dataFormatada = e.end_date.replace(/(\d*)-(\d*)-(\d*).*/, '$2/$1');
              td += ' <td class="text-center">' + dataFormatada + '</td>';
              td += '</tr>';
            });
            $('#tbody_imobilizados').html(td).show();
            $('#div_relatorio_imobilizado').html('<button id="btn_relatorio_imobilizados" style="margin-bottom: 5px;" class="btn btn-success">Gerar Relatório de Imobilizados</button>');
          }else{
            $('#div_relatorio_imobilizado').empty();
            td += '<tr><td colspan="4" class="text-center">NENHUM RESULTADO ENCONTRADO</td></tr>';
            $('#tbody_imobilizados').html(td).show();
          }
        }
      });
      $(document).on('click', '#btn_relatorio_imobilizados', function(e) {
        var sel_loja = $('#sel_loja :selected').val();
        var urlRequestion = "https://localhost/Sistema%20Estoque/estoque/scripts/relatorios/termo_imobilizados.php?";
        urlRequestion += "loja=" + sel_loja;
        window.open(urlRequestion,'PDF');
      });
    });
  });
</script>