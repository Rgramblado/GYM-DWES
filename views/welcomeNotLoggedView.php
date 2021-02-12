<!DOCTYPE html>
<html>

<head>
  <?php require 'includes/head.php' ?>
</head>

<body>
  <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
    <div class="container-fluid p-0 m-0">
      <div class="row w-100">
        <div class="col"><a class="navbar-brand m-2
          " href="/"><b>GYM-DWES</b></a></div>
        <div class="col d-flex justify-content-end align-items-center">
          <a href="?action=login"><button class="btn btn-light mr-3 p-2">Iniciar sesión</button></a>
          <a href="?action=register"><button class="btn btn-light mr-3 p-2">Registrarme</button></a>
        </div>
      </div>
    </div>
  </nav>
  <div class="py-5 text-center text-white h-100 align-items-center d-flex" id="main">
    <div class="container py-5">
      <div class="row">
        <div class="mx-auto col-lg-8 col-md-10">
          <h1 class="display-3 mb-4">GYM-DWES</h1>
          <p class="lead mb-5">Ponte en forma mientras aprendes a programar</p> 
          <a class="btn btn-lg mx-1 btn-outline-primary" href="?action=register">Registrarme</a> 
          <a class="btn btn-lg mx-1 btn-outline-primary" href="?action=login">Iniciar sesión</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>