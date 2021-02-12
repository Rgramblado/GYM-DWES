<!DOCTYPE html>
<html>

<head>
  <?php require 'includes/head.php' ?>
</head>

<body>
  <?php require 'includes/menu.php' ?>
  <div class="py-5 text-center text-white h-100 align-items-center d-flex" id="main">
    <div class="container py-5">
      <div class="row">
        <div class="mx-auto col-lg-8 col-md-10">
          <h1 class="display-3 mb-4">GYM-DWES</h1>
          <p class="lead mb-5">Ponte en forma mientras aprendes a programar</p> 
          <a class="btn btn-lg btn-outline-primary mx-1 mt-2" href="?controller=user&action=showschedule">Horarios</a> 
          <a class="btn btn-lg mx-1 btn-outline-primary mt-2" href="?controller=user&action=showmyactivities">Mis actividades</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>