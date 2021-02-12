<?php

/**
 * Controlador de la página index desde la que se puede hacer el login y el registro
 */

/**
 * Incluimos todos los modelos que necesite este controlador
 */
require_once MODELS_FOLDER . 'UserModel.php';
require_once MODELS_FOLDER . 'MessagesModel.php';
session_start();
class MessagesController extends BaseController
{
    private $from;
    private $to;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION["role_id"]) ||$_SESSION["role_id"] < 1) {
            $this->redirect();
        }
        $this->from = $_SESSION["id"];
        $this->to = isset($_GET["to"]) ? filter_input(INPUT_GET, "to", FILTER_SANITIZE_STRING) : null;
    }

    public function index()
    {
        $params = [
            "title" => "Mis mensajes"
        ];

        $search = $this->search();
        //Si algo ha ido mal, mostraremos un error
        if (!$search["correct"]) {
            $params["error"] = $search["error"];
        } else {
            $params["data"] = $search["data"];
            $params["count"] = $search["count"];
        }

        $this->view->show("ListUsersMessages", $params);
    }

    public function messagesWith()
    {
        $messages = new MessagesModel();
        $messages->user_to = $this->to;
        if(isset($_POST["submit"])){
            $this->sendMessage($messages);
        }
        $result = $messages->get_messages_with();

        $params = [
            "title" => "Mensajes con " . $this->to
        ];

        if (!$result["correct"]) {
            $params["error"] = $result["error"];
        }else{
            $params["data"] = $result["data"];
        }

        $this->view->show("Messages", $params);
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

    private function sendMessage($message){
        $content = filter_input(INPUT_POST, "message", FILTER_SANITIZE_STRING);
        $message->content = $content;
        $message->sendMessageTo();
    }
}
