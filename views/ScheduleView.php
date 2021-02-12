<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/menu.php' ?>
    <div class="py-5 px-5 d-flex flex-column" id="main">
        <?php if(isset($msg)):?>
            <div class="alert alert-success">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        <div class="d-flex text-center align-items-center justify-content-center my-3">
            <?php
            //Calculo el lunes correspondiente
            if (date('w') != 1) {
                $monday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("last monday +" . $_GET["week"] . " week") : strtotime("last monday");
                $friday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("last monday +" . $_GET["week"] . " week +4 days") : strtotime("last monday +4 days");
            } else {
                $monday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("today +" . $_GET["week"] . " week") : strtotime("today");
                $friday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("today +" . $_GET["week"] . " week +4 days") : strtotime("today +4 days");
            } ?>

            <?php if (isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0) : ?>
                <a href="?controller=user&action=showschedule&week=<?php echo (filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) - 1); ?>"><button class="btn btn-outline-primary"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></button></a>
            <?php endif; ?>
            <span class="text-primary mx-2"><?php echo date("d/m/Y", $monday) . " - " . date("d/m/Y", $friday) ?></span>
            <a href="?controller=user&action=showschedule&week=<?php echo (filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) + 1); ?>"><button class="btn btn-outline-primary"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button></a>
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
                                                        <div class=\"badge rounded-pill bg-success mb-2 align-self-end\"> " . $timeslot["count"] . '/'. $timeslot["capacity"] . "</div>
                                                        <span class=\"bg-light p-1 rounded\">" . $timeslot["name"] . "</span>
                                                        <a href=\"?controller=user&action=showschedule&week=". (isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? $_GET["week"] : "0" )."&activityIn=" . $timeslot["id"] . "\"><button class=\"btn btn-outline-primary mt-2\">Apuntarme</button></a>
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
                            else : //Si no hay actividades en el horario, imprimimos celdas vacias
                                for ($k = 0; $k < 5; $k++) {
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