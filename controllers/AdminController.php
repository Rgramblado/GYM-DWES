<?php

/**
 * Controlador de las páginas de administración 
 */

/**
 * Incluimos todos los modelos que necesite este controlador
 */
require_once MODELS_FOLDER . 'UserModel.php';
require_once MODELS_FOLDER . 'MessagesModel.php';
require_once MODELS_FOLDER . 'ActivitiesModel.php';
require_once MODELS_FOLDER . 'ScheduleModel.php';
session_start();

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION["role_id"]) || $_SESSION["role_id"] < 2) {
            $this->redirect();
        }
    }


    public function listUsers()
    {
        $params = [
            "title" => "Administrar usuarios"
        ];

        $search = $this->search();
        //Si algo ha ido mal, mostraremos un error
        if (!$search["correct"]) {
            $params["error"] = $search["error"];
        } else {
            $params["data"] = $search["data"];
            $params["count"] = $search["count"];
        }

        $this->view->show("AdminUsers", $params);
    }

    public function editUser()
    {
        $params = [
            "title" => "Editar usuario"
        ];

        $id = intval(filter_input(INPUT_GET, "userid", FILTER_VALIDATE_INT));
        if (!$id) {
            $params["error"] = "Error en el id del usuario";
            $this->view->show("EditUser", $params);
        }
        $user = (new UserModel())->getUserById($id);
        if (!$user["correct"]) {
            $params["error"] = $user["error"];
        } else {
            $params["data"] = $user["data"];
        }
        $this->view->show("EditUser", $params);
    }

    public function activateUser(){
        if(filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)){
            (new UserModel())->activateUser($_GET["id"]);
        }
        $this->redirect("admin", "listusers");
    }

    public function deactivateUser(){
        if(filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)){
            (new UserModel())->deactivateUser($_GET["id"]);
        }
        $this->redirect("admin", "listusers");
    }

    public function editSchedule()
    {
        $params["title"] = "Editar horario";

        //Cargamos las actividades 
        $activities = new ActivitiesModel();

        $resultActivities = $activities->selectActiveActivities();
        if (!$resultActivities["correct"]) {
            $params["error"] = $resultActivities["error"];
        } else if (!$resultActivities["data"]) {
            $params["error"] = "No existen actividades";
        } else {
            $params["activities"] = $resultActivities["data"];
        }

        //Si se ha creado una nueva actividad, la insertamos
        if (isset($_POST["submit"])) {
            $errors = $this->createNewTimeSlot($resultActivities["data"]);
            if ($errors != []) {
                $params["errors"] = $errors;
            }
        }

        //Si se está eliminando una actividad
        $schedule = new ScheduleModel();
        if (isset($_GET["delete"]) && filter_input(INPUT_GET, "delete", FILTER_VALIDATE_INT)){
            $schedule->deleteTimeslot($_GET["delete"]);
        }

        //Recogemos el horario
        
        $resultSchedule = $schedule->selectAllSchedule();
        if(!$resultSchedule["correct"]){
            if(!isset($params["errors"])){
                $params["errors"] = array();
            }
            $params["errors"]["errorSelectSchedule"] = "Hubo algún problema rescatando el horario";
        }else{
            $params["schedule"] = $resultSchedule["data"];
        }

        $this->view->show("AdminSchedule", $params);
    }

    /**
     * Función para crear un nuevo tramo horario con una actividad
     */
    private function createNewTimeSlot($activities)
    {
        $return = [];
        //Creamos los filtros
        $possibleDays = ["monday", "tuesday", "wednesday", "thursday", "friday"];

        $possibleHours = [];
        for ($i = 7; $i <= 21; $i++) {
            for ($j = 0; $j <= 1; $j++) {
                $hour = strlen($i) == 2 ? $i : "0" . $i;
                $minutes = $j == 0 ? "00" : "30";
                $time = $hour . ":" . $minutes;
                $possibleHours[] = $time;
            }
        }

        $possibleActivities = [];
        foreach ($activities as $activity) {
            $possibleActivities[] = $activity["id"];
        }

        $activity = filter_input(INPUT_POST, "activity", FILTER_VALIDATE_INT);
        $day = filter_input(INPUT_POST, "day", FILTER_SANITIZE_STRING);
        $hourInit = filter_input(INPUT_POST, "hour_init", FILTER_SANITIZE_STRING);
        $hourEnd = filter_input(INPUT_POST, "hour_end", FILTER_SANITIZE_STRING);

        $aaa = array_search($hourInit, $possibleHours);

        //Si los parámetros introducidos no son válidos, añadimos el error
        if (!$activity || array_search($activity, $possibleActivities) === false) {
            $return["errorActivity"] = "Error: Actividad no válida";
        }

        if (!$day || array_search($day, $possibleDays) === false) {
            $return["errorDay"] = "Error: Día no válido";
        }

        if (!$hourInit || array_search($hourInit, $possibleHours) === false) {
            $return["errorHourInit"] = "Error: Hora no válida";
        }

        if (!$hourEnd || array_search($hourEnd, $possibleHours) === false) {
            $return["errorHourEnd"] = "Error: Hora no válida";
        }

        //Hay que comprobar que la hora de inicio sea anterior a la hora de finalización
        if (strtotime($hourInit) >= strtotime($hourEnd)) {
            $return["errorHours"] = "Error: La hora de inicio no puede ser igual o inferior a la hora de finalización";
        }

        if ($return == []) {
            //Hay que tener en cuenta que no se pueden recoger más de una actividad a la misma vez en el mismo día
            $schedule = new ScheduleModel();
            $resultSelect = $schedule->selectAllScheduleFromDay($day);
            if ($resultSelect["correct"]) {
                foreach ($resultSelect["data"] as $timeSlot) {
                    if (!((strtotime($timeSlot["hour_init"]) > strtotime($hourInit) && strtotime($timeSlot["hour_end"]) > strtotime($hourEnd)) ||
                        (strtotime($timeSlot["hour_init"]) < strtotime($hourInit) && strtotime($timeSlot["hour_end"]) < strtotime($hourEnd)))) {
                        $return["errorHours"] = "Error: Ya hay una actividad en este tramo horario";
                    }
                }
            } else {
                $return["errorSelect"] = "Algo ha ido mal";
            }
        }

        //Si no hay mensajes de error, procedemos a crear la actividad
        if ($return == []) {
            $resultInsert = $schedule->insertNewTimeSlot($activity, $hourInit, $hourEnd, $day);
            if (!$resultInsert["correct"]) {
                $return["errorInsert"] = $resultInsert["error"];
            }
        }

        return $return;
    }

    public function editActivities()
    {
        $params["title"] = "Editar actividades";
        $activities = new ActivitiesModel();
        if (isset($_POST["submit"])) {
            $params["errorInsert"] = $this->createActivity();
        }

        if (isset($_GET["delete"])) {
            $params["errorDelete"] = $this->dropActivity();
        }
        $result = $activities->selectActiveActivities();
        if (!$result["correct"]) {
            $params["error"] = $result["error"];
        } else if (!$result["data"]) {
            $params["error"] = "No existen actividades";
        } else {
            $params["data"] = $result["data"];
        }
        $this->view->show("AdminActivities", $params);
    }

    private function createActivity()
    {
        //Comprobamos la validez de los campos
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
        $color = filter_input(INPUT_POST, "color", FILTER_SANITIZE_STRING);
        $capacity = filter_input(INPUT_POST, "capacity", FILTER_VALIDATE_INT);

        if (!$name || !$color || !$capacity) {
            return "Introduce valores válidos";
        }

        $activities = new ActivitiesModel();
        $activities->name = $name;
        $activities->color = $color;
        $activities->capacity = $capacity;
        $return = $activities->insertActivity();
        if (!$return["correct"]) {
            return $return["error"];
        } else {
            return null;
        }
    }

    private function dropActivity()
    {
        $id = filter_input(INPUT_GET, "delete", FILTER_VALIDATE_INT);
        if (!$id) {
            return "ID no válido";
        }
        $activities = new ActivitiesModel();
        $activities->id = $id;
        $activities->deleteActivity();
        return null;
    }

    private function search()
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];
        $user = new UserModel();
        if (
            isset($_GET["search"]) &&
            (!filter_input(INPUT_GET, "search", FILTER_SANITIZE_STRING)
                || !preg_match('/^[a-z0-9]{0,35}$/i', $_GET["search"]))
        ) {
            $return["correct"] = false;
            $return["error"] = "El usuario no debe contener más de 35 caractéres alfanuméricos";
            return $return;
        }

        $user->username = isset($_GET["search"]) ? $_GET["search"] : "";
        return $user->searchUsers();
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
        $role_id = filter_input(INPUT_POST, "role_id", FILTER_VALIDATE_INT);
        $user_confirmed = filter_input(INPUT_POST, "role_id", FILTER_VALIDATE_INT);
        $email_confirmed = filter_input(INPUT_POST, "role_id", FILTER_VALIDATE_INT);

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
            $user->role_id = $role_id ? $role_id : 1;
            $user->user_confirmed = $user_confirmed;
            $user->email_confirmed = $email_confirmed;

            $params = [
                "title" => "Registrarme",
                "result" => $user->updateUser($id)
            ];
            $this->view->show("Register", $params);
        }
    }
}
