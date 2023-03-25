<?php
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  error_reporting(E_ERROR & ~E_NOTICE);

  $page_title = 'Histórico do Produto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php
 if(isset($_POST['p_codigobarras'])){
  $codigoarras = $_POST['p_codigobarras'];
  $sales = historico_produto($codigoarras);
 }
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <form method="post">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Procurar</button>
            </span>
            <input type="text" class="form-control" name="p_codigobarras"  placeholder="Digite o Código de Barras">
         </div>
        </div>
    </form>
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
            <span>Histórico do Produto</span>
          </strong>
        </div>
        <div class="panel-body">
          <strong>Produto: <?php echo $sales[0]['name'].' - '.$sales[0]['medida'].' - '.$sales[0]['nome']; ?></strong>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center"> Data </th>
                <th class="text-center"> Colaborador </th>
                <th class="text-center"> EAN </th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo read_date($sale['date']); ?></td>
               <td class="text-center"><?php echo $sale['colab']; ?></td>
               <td class="text-center"><?php echo $sale['product_codigobarras']; ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
