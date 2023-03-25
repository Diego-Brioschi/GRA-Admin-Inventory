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

<button class="openbtn" onclick="openNav()">☰ Open Sidebar</button> 

<?php include_once('layouts/footer.php'); ?>