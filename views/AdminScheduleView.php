<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/menu.php' ?>
    <div class="py-5 px-5 d-flex flex-column" id="main">
        <?php if (isset($errors["errorInsert"])) :
            echo "<div class=\"alert alert-danger\"> " . $errors["errorInsert"] . " </div>";
        endif; ?>
        <?php if (isset($errors["errorActivity"])) :
            echo "<div class=\"alert alert-danger\"> " . $errors["errorActivity"] . " </div>";
        endif; ?>
        <?php if (isset($errors["errorDay"])) :
            echo "<div class=\"alert alert-danger\"> " . $errors["errorDay"] . " </div>";
        endif; ?>
        <?php if (isset($errors["errorHourInit"])) :
            echo "<div class=\"alert alert-danger\"> " . $errors["errorHourInit"] . " </div>";
        endif; ?>
        <?php if (isset($errors["errorHourEnd"])) :
            echo "<div class=\"alert alert-danger\"> " . $errors["errorHourEnd"] . " </div>";
        endif; ?>
        <?php if (isset($errors["errorHours"])) :
            echo "<div class=\"alert alert-danger\"> " . $errors["errorHours"] . " </div>";
        endif; ?>

        <div class="justify-content-center w-100 d-flex">
            <button class="btn btn-outline-primary justify-self-center my-3" type="button" data-toggle="collapse" data-target="#new-activity-container">Insertar actividad</button>
        </div>
        <div class="collapse container my-3 " id="new-activity-container">
            <div class="row d-flex justify-content-center">
                <form class="rounded bg-light p-3" action="?controller=admin&action=editschedule" method="post">
                    <h3 class="text-center">Nueva actividad</h3>
                    <div class="form-group row">
                        <label for="activity" class="col-4 col-form-label">Nombre</label>
                        <div class="col-8">
                            <div class="input-group">
                                <select id="activity" name="activity" class="custom-select">
                                    <?php foreach ($activities as $activity) : ?>
                                        <div class="d-flex">
                                            <option value="<?php echo $activity["id"] ?>"><?php echo $activity["name"] ?></option>
                                            <option disabled style="background-color: <?php echo $activity["color"] ?>"></option>
                                        </div>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="color" class="col-4 col-form-label">Día</label>
                        <div class="col-8">
                            <div class="input-group">
                                <select name="day" id="day" class="custom-select">
                                    <option value="monday">Lunes</option>
                                    <option value="tuesday">Martes</option>
                                    <option value="wednesday">Miércoles</option>
                                    <option value="thursday">Jueves</option>
                                    <option value="friday">Viernes</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hour_init" class="col-4 col-form-label">Hora de inicio</label>
                        <div class="col-8">
                            <div class="input-group">
                                <select name="hour_init" id="hour_init" class="custom-select">
                                    <?php for ($i = 7; $i <= 21; $i++) {
                                        for ($j = 0; $j <= 1; $j++) {
                                            $hour = strlen($i) == 2 ? $i : "0" . $i;
                                            $minutes = $j == 0 ? "00" : "30";
                                            $time = $hour . ":" . $minutes;
                                            echo "<option value=\"" . $time . "\">" . $time . "</option>";
                                        }
                                    } ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hour_end" class="col-4 col-form-label">Hora de fin</label>
                        <div class="col-8">
                            <div class="input-group">
                                <select name="hour_end" id="hour_end" class="custom-select">
                                    <?php for ($i = 7; $i <= 21; $i++) {
                                        for ($j = 0; $j <= 1; $j++) {
                                            $hour = strlen($i) == 2 ? $i : "0" . $i;
                                            $minutes = $j == 0 ? "00" : "30";
                                            $time = $hour . ":" . $minutes;
                                            echo "<option value=\"" . $time . "\">" . $time . "</option>";
                                        }
                                    } ?>
                                </select>

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
        <?php if (!isset($errors)) : ?>
            <table class="table table-striped table-bordered rounded table-light" id="schedule">
                <thead class="text-center">
                    <tr>
                        <th>Horas</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                    </tr>
                </thead>

                <?php
                $rowspans = [
                    "monday" => 0,
                    "tuesday" => 0,
                    "wednesday" => 0,
                    "thursday" => 0,
                    "friday" => 0
                ];

                for ($i = 7; $i <= 21; $i++) :
                    for ($j = 0; $j <= 1; $j++) :
                        $hour = strlen($i) == 2 ? $i : "0" . $i;
                        $minutes = $j == 0 ? "00" : "30";
                        $time = $hour . ":" . $minutes; ?>

                        <tr>
                            <th><?php echo $time ?></th>
                            <?php
                            if ($schedule) :
                                foreach ($rowspans as $day => $rowspan) :
                                    if ($rowspan == 0) {
                                        foreach ($schedule as $timeslot) {
                                            $done = false;
                                            if ($timeslot["day"] == $day && strtotime($timeslot["hour_init"]) == strtotime($time)) {
                                                $thisRowspan = (strtotime($timeslot["hour_end"]) - strtotime($timeslot["hour_init"])) / 1800;
                                                if ($thisRowspan > 1) {
                                                    $rowspans[$day] = $thisRowspan - 1;
                                                }
                                                echo "<td rowspan=\"$thisRowspan\" style=\"background-color: " . $timeslot["color"] . "\">
                                                    <div class=\"h-100 d-flex flex-column align-items-center\">
                                                        <div class=\"badge rounded-pill bg-success mb-2 align-self-end\"> ". $timeslot["capacity"]."</div>
                                                        <span class=\"bg-light p-1 rounded\">" . $timeslot["name"] . "</span>
                                                        <a href=\"?controller=admin&action=editschedule&delete=" . $timeslot["id"] . "\"><button class=\"btn btn-danger mt-2\">Eliminar</button></a>
                                                    </div>
                                                </td>";
                                                $done = true;
                                                break;
                                            }
                                        }
                                        if (!$done) {
                                            echo "<td></td>";
                                        }
                                    }

                                    if ($rowspan > 0) {
                                        $rowspans[$day]--;
                                    }

                                endforeach;
                            else: //Si no hay actividades en el horario, imprimimos celdas vacias
                                for ($k=0; $k < 5; $k++) { 
                                    echo "<td></td>";
                                }
                            endif;
                            ?>
                        </tr>

                <?php endfor;
                endfor; ?>
            </table>
        <?php endif; ?>
    </div>
    <script src="assets/styleSchedule.js"></script>
</body>

</html>