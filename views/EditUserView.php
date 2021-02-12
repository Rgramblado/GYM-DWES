<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/menu.php' ?>

    <div class="py-5 text-center text-white h-100 align-items-center justify-content-center d-flex flex-column" id="main">
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php else : ?>
            <label for="image"><img src="<?php echo $data["image"] ?>" alt="" class="img-fluid rounded-circle img-edit"></label>
            <?php if ($_GET["controller"] == "admin") {
                $action = "/?controller=admin&action=edituser&userid={$_GET["userid"]}";
            } else {
                $action = "/?controller=user&action=edituser";
            } ?>
            <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="username" class="col-4 col-form-label">Username</label>
                    <div class="col-8">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                            <input id="username" name="username" placeholder="Nombre de usuario" type="text" class="form-control" value="<?php echo $data["username"] ?>">
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
                            <input id="email" name="email" placeholder="E-mail" type="email" class="form-control" value="<?php echo $data["email"] ?>">
                        </div>
                        <?php if (isset($errorMsgs["email"])) : ?>
                            <div class="alert alert-danger">
                                <?php echo $errorMsgs["email"] ?>
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
                            <input id="name" name="name" placeholder="Nombre" type="text" class="form-control" value="<?php echo $data["name"] ?>">
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
                            <input id="surname" name="surname" placeholder="Apellidos" type="text" class="form-control" required="required" value="<?php echo $data["surname"] ?>">
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
                            <input id="nif" name="nif" placeholder="NIF" type="text" class="form-control" required="required" value="<?php echo $data["nif"] ?>">
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
                            <input id="telephone" name="telephone" placeholder="Teléfono" type="text" class="form-control" value="<?php echo $data["telephone"] ?>">
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
                            <input id="address" name="address" placeholder="Dirección" type="text" class="form-control" value="<?php echo $data["address"] ?>">
                        </div>
                        <?php if (isset($errorMsgs["telephone"])) : ?>
                            <div class="alert alert-danger">
                                <?php echo $errorMsgs["telephone"] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-8">
                        <div class="input-group">
                            <input id="image" name="image" placeholder="Dirección" type="file" class="form-control" hidden>
                        </div>
                    </div>
                </div>
                <?php if ($_GET["controller"] == "admin") : ?>
                    <div class="form-group">
                        <label for="user_confirmed">Usuario confirmado</label>
                        <select class="form-control" name="user_confirmed" id="user_confirmed">
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email_confirmed">Email confirmado</label>
                        <select class="form-control" name="email_confirmed" id="email_confirmed">
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="role">Rol asignado</label>
                        <select class="form-control" name="role" id="role">
                            <option value="1">Usuario</option>
                            <option value="2">Administrador</option>
                        </select>
                    </div>
                    
                <?php endif; ?>
                <div class="form-group row">
                    <div class="offset-4 col-8">
                        <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>