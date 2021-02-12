<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
    <script>
      function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('6Le6zCcaAAAAAETllnaUVHRftqXJtXcaIwsu9pwL', {action: 'submit'}).then(function(token) {
              
          });
        });
      }
  </script>
</head>

<body>
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <div class="container-fluid p-0 m-0">
            <div class="row w-100">
                <div class="col"><a class="navbar-brand m-2" href="/"><b>GYM-DWES</b></a></div>
                <div class="col d-flex justify-content-end align-items-center">
                    <a href="?action=login"><button class="btn btn-light mr-3 p-2">Iniciar sesión</button></a>
                    <a href="?action=register"><button class="btn btn-light mr-3 p-2">Registrarme</button></a>
                </div>
            </div>
        </div>
    </nav>
    <div class="py-5 text-center text-white h-100 align-items-center justify-content-center d-flex" id="main">
        <form action="#" method="POST">
            <div class="form-group row">
                <label for="username" class="col-4 col-form-label">Usuario</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-user"></i>
                        </div>
                        <input id="username" name="username" placeholder="Nombre de usuario" type="text" class="form-control" required="required">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-4 col-form-label">Contraseña</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-key"></i>
                        </div>
                        <input id="password" name="password" placeholder="Password" type="password" class="form-control" required="required">
                    </div>
                </div>
            </div>
            <div class="form-group row d-flex justify-content-center">
                <div class="g-recaptcha" data-sitekey="6Le6zCcaAAAAAETllnaUVHRftqXJtXcaIwsu9pwL"></div>
            </div>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>

            <?php endif; ?>
            <div class="form-group row">
                <div class="offset-4 col-8">
                    <button name="submit" type="submit" class="btn btn-primary" onclick="onClick(e)">Login</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>