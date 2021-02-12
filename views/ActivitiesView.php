<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/menu.php' ?>

    <div class="py-5 d-flex flex-column" id="main">
        <div class="py-5 d-flex">
            <a name="" id="" class="btn btn-primary mx-3" href="?controller=user&action=showmyactivities" role="button">Todas</a>
            <a name="" id="" class="btn btn-primary mx-3" href="?controller=user&action=showmyactivities&status=programmed" role="button">Programadas</a>
            <a name="" id="" class="btn btn-primary mx-3" href="?controller=user&action=showmyactivities&status=finished" role="button">Finalizadas</a>
            <a name="" id="" class="btn btn-primary mx-3" href="?controller=user&action=showmyactivities&status=cancelled" role="button">Canceladas</a>
        </div>
        <?php if (!isset($error)) : ?>
            <table class="table bg-light">
                <thead>
                    <tr>
                        <th><a href="?controller=user&action=showmyactivities&orderby=activity<?php if(isset($_GET["orderby"]) &&  $_GET["orderby"] == "activity" && !isset($_GET["desc"])) echo "&desc=true"?>">Actividad</a></th>
                        <th><a href="?controller=user&action=showmyactivities&orderby=date<?php if(isset($_GET["orderby"]) &&  $_GET["orderby"] == "date" && !isset($_GET["desc"])) echo "&desc=true"?>">Fecha</a></th>
                        <th>Estado</th>
                        <th>Controles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity) :
                        $format = "Y-m-d H:i:s";
                        $date = DateTime::createFromFormat($format, $activity["date"] . " " . $activity["hour_init"]);
                        if ($activity["date_cancel"] != null || $activity["date_out"] != null) {
                            $bg = "bg-danger";
                        } else if ($date < new DateTime()) {
                            $bg = "bg-warning";
                        } else {
                            $bg = "bg-success";
                        }
                    ?>
                        <tr class=<?= $bg ?>>
                            <td><?= $activity["name"] ?></td>
                            <td><?= $activity["date"] . " " . $activity["hour_init"] . "-" . $activity["hour_end"] ?> </td>
                            <td><?php
                                switch ($bg) {
                                    case "bg-danger":
                                        echo "Cancelada";
                                        break;
                                    case "bg-warning":
                                        echo "Terminada";
                                        break;
                                    case "bg-success":
                                        echo "Programada";
                                        break;
                                }
                                ?></td>
                            <td>
                                <?php if ($bg == "bg-success") : ?>
                                    <a class="btn btn-danger" href="?controller=user&action=showmyactivities&delid=<?= $activity["id"] ?>" role="button">Eliminar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <?= $error ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>