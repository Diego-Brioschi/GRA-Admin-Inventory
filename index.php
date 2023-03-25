<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>

<?php
//Caso Usuario Venha do GRA Admin (Login Atomatico)

if(isset($_GET['password'], $_GET['login'])){
  $login = $_GET['login'];
  $password = $_GET['password'];
  $scriptAutoLogin = "
  <script>
    $(document).ready(function() {
      $('#username').val('$login');
      $('#password').val('$password');
      $('#formLogin').submit();
    });
  </script>
  ";
}

?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h1>Bem Vindo</h1>
       <p>Entre com seu usuário e senha</p>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="auth.php" id="formLogin" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Login</label>
              <input type="name" class="form-control" name="username" id="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Senha</label>
            <input type="password" name= "password" id="password" class="form-control" placeholder="password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info  pull-right">Entrar</button>
        </div>
    </form>
</div>

</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js" integrity="sha512-/xmIG37mK4F8x9kBvSoZjbkcQ4/y2AbV5wv+lr/xYhdZRjXc32EuRasTpg7yIdt0STl6xyIq+rwb4nbUmrU/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>

<?php echo @$scriptAutoLogin; ?>
