<?php
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  error_reporting(E_ERROR & ~E_NOTICE);

  $page_title = 'Histórico do Colaborador';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php
 if(isset($_POST['p_colab'])){
  $user = $_POST['p_colab'];
  $sales = historico_colaborador($user);
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
            <input type="text" id="p_colab" class="form-control" name="p_colab"  placeholder="Digite o nome do Colaborador">
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
            <span>Histórico do Colaborador</span>
          </strong>
        </div>
        <div class="panel-body">
          <strong>Colaborador: <?php echo $sales[0]['colab']; ?></strong>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center"> Data </th>
                <th class="text-center"> EAN </th>
                <th class="text-center"> Produto </th>
                <th class="text-center"> QTD. </th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo read_date($sale['date']); ?></td>
               <td class="text-center"><?php echo $sale['product_codigobarras']; ?></td>
               <td class=""><?php echo $sale['name']. " - " .$sale['medida'] . " - ". $sale['nome']; ?></td>
               <td class="text-center"><?php echo $sale['qty']. "UN.";?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
