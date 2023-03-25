<?php
$page_title = 'Baixas por Periodo';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
        <h5>Baixas por Período</h5>
      </div>
      <div class="panel-body">
          <form class="clearfix" method="post" action="sale_report_process.php">
            <div class="form-group">
              <label class="form-label">Período</label>
                <div class="input-group">
                  <input type="text" class="datepicker form-control" name="start-date" placeholder="Inicial" autocomplete="off">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="end-date" placeholder="Final" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                 <button type="submit" name="submit" class="btn btn-primary">Gerar Relatório</button>
            </div>
          </form>
      </div>

    </div>
  </div>

</div>
<?php include_once('layouts/footer.php'); ?>
