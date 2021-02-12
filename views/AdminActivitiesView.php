<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/menu.php' ?>
    <div class="py-5 d-flex flex-column" id="main">
        <div class="justify-content-center w-100 d-flex">
            <button class="btn btn-outline-primary justify-self-center my-3" type="button" data-toggle="collapse" data-target="#new-activity-container">Insertar actividad</button>
        </div>
        <div class="collapse container" id="new-activity-container">
            <div class="row d-flex justify-content-center">
                <form class="rounded bg-light p-3" action="?controller=admin&action=editactivities" method="post">
                    <h3 class="text-center">Nueva actividad</h3>
                    <div class="form-group row">
                        <label for="name" class="col-4 col-form-label">Nombre</label>
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-soccer-ball-o"></i>
                                    </div>
                                </div>
                                <input id="name" name="name" placeholder="Nombre de la actividad" type="text" class="form-control" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="color" class="col-4 col-form-label">Color</label>
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-paint-brush"></i>
                                    </div>
                                </div>
                                <input id="color" name="color" placeholder="Color" type="color" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="capacity" class="col-4 col-form-label">Capacidad</label>
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-sort-numeric-asc"></i>
                                    </div>
                                </div>
                                <input id="capacity" name="capacity" placeholder="Capacidad" type="text" class="form-control">
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
        </div>
        <div class="container">

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?php echo $error ?></div>
            <?php else : ?>
                <div class="row m-4 rounded bg-light p-2 text-center">
                    <div class="col-4">
                        <h4>Nombre</h4>
                    </div>
                    <div class="col-3">
                        <h4>Fecha de inclusión</h4>
                    </div>
                    <div class="col-2">
                        <h4>Capacidad</h4>
                    </div>
                    <div class="col-3">
                        <h4>Controles</h4>
                    </div>
                </div>
                <?php foreach ($data as $activitie) : ?>
                    <div class="row m-4 rounded bg-light p-2 text-center">
                        <div class="col-4"><?php echo $activitie["name"] ?></div>
                        <div class="col-3"><?php echo $activitie["date_in"] ?></div>
                        <div class="col-2"><?php echo $activitie["capacity"] ?></div>
                        <div class="col-3"><?php echo "<a href=\"?controller=admin&action=editactivities&delete=" . $activitie["id"] . "\"><button class='btn btn-outline-primary'>Eliminar</button></a>" ?></div>
                    </div>
            <?php endforeach;
            endif; ?>

        </div>
    </div>
</body>

</html>