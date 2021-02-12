<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
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
    <div class="py-5 text-center text-white h-100 align-items-center justify-content-center d-flex flex-column" id="main">
        <?php if (isset($result)) : ?>
            <?php if ($result["correct"]) : ?>
                <div class="alert alert-success text-center">
                    Se ha registrado correctamente
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <?php echo $result["error"];?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
                <label for="username" class="col-4 col-form-label">Username</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-user"></i>
                        </div>
                        <input id="username" name="username" placeholder="Nombre de usuario" type="text" class="form-control" required="required" <?php if(isset($_POST["username"])) echo "value=\"".$_POST["username"]."\""?>>
                    </div>
                    <?php if (isset($errorMsgs["username"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["username"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">E-mail</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-at"></i>
                        </div>
                        <input id="email" name="email" placeholder="E-mail" type="email" class="form-control" required="required" <?php if(isset($_POST["email"])) echo "value=\"".$_POST["email"]."\""?>>
                    </div>
                    <?php if (isset($errorMsgs["email"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["email"] ?>
                        </div>
                    <?php endif; ?>
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
                    <?php if (isset($errorMsgs["password"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["password"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="pass2" class="col-4 col-form-label">Repetir contraseña</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-key"></i>
                        </div>
                        <input id="pass2" name="password2" placeholder="Repetir contraseña" type="password" class="form-control" required="required">
                    </div>
                    <?php if (isset($errorMsgs["password2"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["password2"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-4 col-form-label">Nombre</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-address-card-o"></i>
                        </div>
                        <input id="name" name="name" placeholder="Nombre" type="text" class="form-control" required="required" <?php if(isset($_POST["name"])) echo "value=\"".$_POST["name"]."\""?>>
                    </div>
                    <?php if (isset($errorMsgs["name"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["name"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="surname" class="col-4 col-form-label">Apellidos</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-address-card-o"></i>
                        </div>
                        <input id="surname" name="surname" placeholder="Apellidos" type="text" class="form-control" required="required" <?php if(isset($_POST["surname"])) echo "value=\"".$_POST["surname"]."\""?>>
                    </div>
                    <?php if (isset($errorMsgs["surname"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["surname"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="nif" class="col-4 col-form-label">NIF</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-address-card-o"></i>
                        </div>
                        <input id="nif" name="nif" placeholder="NIF" type="text" class="form-control" required="required" <?php if(isset($_POST["nif"])) echo "value=\"".$_POST["nif"]."\""?>>
                    </div>
                    <?php if (isset($errorMsgs["nif"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["nif"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="telephone" class="col-4 col-form-label">Teléfono</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <input id="telephone" name="telephone" placeholder="Teléfono" type="text" class="form-control" required="required" <?php if(isset($_POST["telephone"])) echo "value=\"".$_POST["telephone"]."\""?>> 
                    </div>
                    <?php if (isset($errorMsgs["telephone"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["telephone"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Dirección</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-home"></i>
                        </div>
                        <input id="address" name="address" placeholder="Dirección" type="text" class="form-control" required="required" <?php if(isset($_POST["address"])) echo "value=\"".$_POST["address"]."\""?>> 
                    </div>
                    <?php if (isset($errorMsgs["telephone"])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMsgs["telephone"] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="image" class="col-4 col-form-label">Imagen</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-home"></i>
                        </div>
                        <input id="image" name="image" placeholder="Dirección" type="file" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-4 col-8">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>