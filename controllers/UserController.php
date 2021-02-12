<?php

/**
 * Controlador de la página index desde la que se puede hacer el login y el registro
 */

/**
 * Incluimos todos los modelos que necesite este controlador
 */
require_once MODELS_FOLDER . 'UserModel.php';
require_once MODELS_FOLDER . 'MessagesModel.php';
require_once MODELS_FOLDER . 'ScheduleModel.php';
session_start();

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION["role_id"]) || $_SESSION["role_id"] < 1) {
            $this->redirect();
        }
    }


    public function sign_out()
    {
        unset($_SESSION);
        session_destroy();
        $this->redirect();
    }

    /**
     * Función para mostrar las actividades del usuario
     */
    public function showMyActivities()
    {
        $params["title"] = "Mis actividades";
        if (isset($_GET["delid"]) && $delid = filter_input(INPUT_GET, "delid", FILTER_VALIDATE_INT)) {
            (new ScheduleModel())->deleteUserFromActivity($delid);
        }
        $activities = (new ScheduleModel())->getUserActivities();
        if ($activities["correct"] && $activities["data"]) {
            $params["activities"] = $activities["data"];
        } else {
            $params["error"] = "No se han encontrado actividades para el usuario";
        }
        $this->view->show("Activities", $params);
    }

    /**
     * Función para mostrar el horario
     */
    public function showSchedule()
    {
        $params["title"] = "Horario";


        //Si se está eliminando una actividad
        $schedule = new ScheduleModel();
        if (isset($_GET["delete"]) && filter_input(INPUT_GET, "delete", FILTER_VALIDATE_INT)) {
            $schedule->deleteTimeslot($_GET["delete"]);
        }

        //Vemos si el usuario se está apuntando a una actividad
        if (isset($_GET["activityIn"])) {
            $params["msg"] = $this->getIntoActivity();
        }

        //Recogemos el horario

        $resultSchedule = $schedule->selectAllSchedule();
        if (!$resultSchedule["correct"]) {
            if (!isset($params["errors"])) {
                $params["errors"] = array();
            }
            $params["errors"]["errorSelectSchedule"] = "Hubo algún problema rescatando el horario";
        } else {
            $params["schedule"] = $resultSchedule["data"];
        }

        //Hay que saber cuanta gente hay en cada actividad. Para ello primero calculamos las fechas
        if (date('w') != 1) {
            $monday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("last monday +" . $_GET["week"] . " week") : strtotime("last monday");
            $tuesday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("last monday +" . $_GET["week"] . " week +1 days") : strtotime("last monday +1 days");
            $wednesday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("last monday +" . $_GET["week"] . " week +2 days") : strtotime("last monday +2 days");
            $thursday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("last monday +" . $_GET["week"] . " week +3 days") : strtotime("last monday +3 days");
            $friday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("last monday +" . $_GET["week"] . " week +4 days") : strtotime("last monday +4 days");
        } else {
            $monday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("today +" . $_GET["week"] . " week") : strtotime("today");
            $tuesday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("today +" . $_GET["week"] . " week +1 days") : strtotime("today +1 days");
            $wednesday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("today +" . $_GET["week"] . " week +2 days") : strtotime("today +2 days");
            $thursday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("today +" . $_GET["week"] . " week +3 days") : strtotime("today +3 days");
            $friday = isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? strtotime("today +" . $_GET["week"] . " week +4 days") : strtotime("today +4 days");
        }

        if (isset($params["schedule"])) {
            foreach ($params["schedule"] as $key => $timeslot) {
                $params["schedule"][$key]["count"] = ($schedule->selectCountUsersInActivity($timeslot["id"], date('d/m/Y', ${$timeslot["day"]})))["data"]["count"];
            }
        }



        $this->view->show("Schedule", $params);
    }

    /**
     * Función para apuntarse a una actividad
     */
    private function getIntoActivity()
    {
        $return = "";
        //Obtenemos la fecha en la que el usuario se quiere apuntar
        $schedule = new ScheduleModel();
        $day = $schedule->selectDayFromSchedule(filter_input(INPUT_GET, "activityIn", FILTER_VALIDATE_INT));
        if (!$day["data"] || $day["data"] == null) {
            $day = null;
        } else {
            $day = $day["data"]["day"];
        }
        //Calculamos la fecha, en base a la semana
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday"];
        if (array_search($day, $days) === false) {
            $return = "Error: Actividad no válida";
            return $return;
        }
        $date = date('w') == 1 ?
            (isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? date('d/m/Y', strtotime('today +' . array_search($day, $days) . " days +" . $_GET["week"] . " weeks")) :
                date('d/m/Y', strtotime('today +' . array_search($day, $days) . " days"))) : (isset($_GET["week"]) && filter_input(INPUT_GET, "week", FILTER_VALIDATE_INT) > 0 ? date('d/m/Y', strtotime('last monday +' . array_search($day, $days) . " days +" . $_GET["week"] . " weeks")) :
                date('d/m/Y', strtotime('last monday +' . array_search($day, $days) . " days")));


        //Vemos si el usuario ya está apuntado en la actividad
        if ($schedule->checkUserInActivity(filter_input(INPUT_GET, "activityIn", FILTER_VALIDATE_INT), $date)["data"]) {
            $return = "Error: Ya está apuntado en la actividad";
            return $return;
        }

        //Si todo está correcto, lo insertamos
        if (!($schedule->insertUserInSchedule(filter_input(INPUT_GET, "activityIn", FILTER_VALIDATE_INT), $date))["correct"]) {
            $return = "Error: Algo ha ido mal";
            return $return;
        } else {
            $return = "Se ha inscrito correctamente";
            return $return;
        }
    }

    /**
     * Función para editar el usuario
     */
    public function editUser()
    {
        $params = [
            "title" => "Editar usuario"
        ];

        $id = $_SESSION["id"];

        $user = (new UserModel())->getUserById($id);
        if (!$user["correct"]) {
            $params["error"] = $user["error"];
        } else {
            $params["data"] = $user["data"];
            if (isset($_POST["submit"])) {
                $this->executeUpdate($id);
            }
        }

        $this->view->show("EditUser", $params);
    }

    private function executeUpdate($id)
    {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL); //Este filtro automáticamente valida el email
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
        $surname = filter_input(INPUT_POST, "surname", FILTER_SANITIZE_STRING);
        $nif = filter_input(INPUT_POST, "nif", FILTER_SANITIZE_STRING);
        $telephone = filter_input(INPUT_POST, "telephone", FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_STRING);
        if (isset($_FILES['image'])) {
            $image_base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
            $image = 'data:image/' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) . ';base64,' . $image_base64;
        }


        //Conjunto de regex 
        $regexUsername = '/^[a-z0-9]{4,16}$/i';
        $regexPassword = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*\W)\S*$/';
        $regexNif = '/^(\d{8}[A-Za-z])|([KLMXYZ]\d{7}[A-Z])$/';
        $regexName = '/^[\pL\s]{1,}$/';
        $regexTelephone = '/^\+?\d{7,14}$/';

        //Comprobamos que se cumplen las condiciones:
        $allCorrect = true;
        $errorMsgs = [];

        if (!$username || !preg_match($regexUsername, $username)) {
            $errorMsgs["username"] = "El usuario solo puede contener letras y números, entre 4 y 16 caracteres";
            $allCorrect = false;
        }

        if (!$email) {
            $errorMsgs["email"] = "El formato del email no es correcto";
            $allCorrect = false;
        }

        if (!$password || !preg_match($regexPassword, $password)) {
            $errorMsgs["password"] = "La contraseña debe tener más de 8 caracteres, contener una mayúscula, minúscula, número y carácter especial (no espacios).";
            $allCorrect = false;
        }

        if ($password != $password2) {
            $errorMsgs["password2"] = "Las contraseñas no coinciden.";
            $allCorrect = false;
        }

        if (!$name || !preg_match($regexName, $name)) {
            $errorMsgs["name"] = "El formato del nombre no es correcto";
            $allCorrect = false;
        }

        if (!$surname || !preg_match($regexName, $surname)) {
            $errorMsgs["surname"] = "El formato de los apellidos no es correcto";
            $allCorrect = false;
        }

        if (!$nif || !preg_match($regexNif, $nif)) {
            $errorMsgs["nif"] = "El formato del NIF no es correcto (KLMNX o número + 7 números + 1 letra).";
            $allCorrect = false;
        }

        if (!$telephone || !preg_match($regexTelephone, $telephone)) {
            $errorMsgs["telephone"] = "El formato del teléfono no es correcto.";
            $allCorrect = false;
        }

        if (!$address) {
            $errorMsgs["address"] = "El formato de la dirección no es correcto";
            $allCorrect = false;
        }

        //Si hay errores, mostramos la pantalla con los errores que se hayan ido produciendo.
        if (!$allCorrect) {
            $params = [
                "title" => "Registrarme",
                "errorMsgs" => $errorMsgs
            ];
            $this->view->show("Register", $params);
        } else {
            $user = new UserModel();
            $user->username = $username;
            $user->password = $password;
            $user->email = $email;
            $user->name = $name;
            $user->surname = $surname;
            $user->nif = $nif;
            $user->telephone = $telephone;
            $user->address = $address;
            $user->image = isset($image) ? $image : null;
            $user->role_id = 1;
            $user->user_confirmed = 1;
            $user->email_confirmed = 1;

            $params = [
                "title" => "Registrarme",
                "result" => $user->updateUser($id)
            ];
            $this->view->show("Register", $params);
        }
    }
}
