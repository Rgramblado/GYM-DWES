<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/menu.php' ?>

    <div class="py-5 d-flex flex-column" id="main">
        <div class="d-flex flex-column justify-content-end px-3">
            <form method="GET" action="#">
                <div class="form-group flex-row wrap">
                    <input type="text" name="search" class="form-control rounded">
                    <input type="text" name="controller" value="messages" hidden>
                    <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </form>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($data)) : ?>
                <div class="container-fluid">
                    <?php foreach ($data as $user) : ?>
                        <div class="row my-2">
                            <div class="col-lg-1 col-sm-2">
                                <img src="<?php echo $user["image"]; ?>" alt="" class="img-fluid rounded-circle">
                            </div>
                            <div class="col-6 d-flex align-items-center text-light">
                                <?php echo $user["username"]; ?>
                            </div>
                            <div class="col-5 col-sm-4 d-flex align-items-center justify-content-center">
                                <?php if($user["user_confirmed"]) : ?>
                                <a name="" id="" class="btn btn-primary mx-2" href="?controller=admin&action=deactivateUser&id=<?= $user["id"] ?>" role="button">Desactivar usuario</a>
                                <?php else : ?>
                                <a name="" id="" class="btn btn-primary mx-2" href="?controller=admin&action=activateUser&id=<?= $user["id"] ?>" role="button">Activar usuario</a>
                                <?php endif; ?>
                                <a href="?controller=admin&action=edituser&userid=<?php echo $user["id"]; ?>"><button class="btn btn-outline-primary">Editar usuario</button></a>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center">
            <?php if (isset($count)) : for ($i = 1; $i < ceil($count / 2); $i++) : ?>
                    <a href="?controller=admin&action=listusers&page=<?php echo $i; ?>"><button class="btn btn-outline-primary mx-1"><?php echo $i; ?></button></a>
            <?php endfor; endif; ?>
        </div>
    </div>
</body>

</html>