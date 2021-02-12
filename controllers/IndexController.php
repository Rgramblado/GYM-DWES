<?php

use \vendor\PHPMailer\PHPMailer\PHPMailer;
use \vendor\PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Controlador de la página index desde la que se puede hacer el login y el registro
 */

/**
 * Incluimos todos los modelos que necesite este controlador
 */
require_once MODELS_FOLDER . 'UserModel.php';
session_start();
class IndexController extends BaseController
{
   public function __construct()
   {
      parent::__construct();
   }

   public function index()
   {
      $parametros = [
         "title" => "GYM-DWES"
      ];


      if (isset($_SESSION["username"]))
         $this->view->show("WelcomeLogged", $parametros);
      else
         $this->view->show("WelcomeNotLogged", $parametros);
   }

   /**
    * Podemos implementar la acción login
    *
    * @return void
    */
   public function login()
   {
      $parametros = [
         "title" => "GYM-DWES"
      ];


      if (isset($_SESSION["username"]))
         $this->view->show("WelcomeLogged", $parametros);
      else {
         $parametros["title"] = "Login";

         if (isset($_POST["submit"])) {
            $this->executeLogin();
         } else {
            $this->view->show("login", $parametros);
         }
      }
   }

   /**
    * Podemos implementar la acción registro de usuarios
    *
    * @return void
    */
   public function register()
   {
      $parametros = [
         "title" => "GYM-DWES"
      ];


      if (isset($_SESSION["username"]))
         $this->view->show("WelcomeLogged", $parametros);
      else if (isset($_POST["submit"])) {
         $this->executeRegister();
      } else {
         $this->view->show("register", $parametros);
      }
   }

   private function executeLogin()
   {
      $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

      $user = new UserModel();
      $user->username = $username;
      $user->password = $password;
      $login = $user->executeLogin();
      if (!$login["correct"]) {
         $params = [
            "title" => "Login",
            "error" => $login["error"]
         ];
         $this->view->show("login", $params);
      } else if(!$login["data"]["user_confirmed"]){
         $params = [
            "title" => "Login",
            "error" => "El usuario no ha sido activado. Contacte con el administrador."
         ];
         $this->view->show("login", $params);
      }  
      else {
         $_SESSION["id"] = $login["data"]["id"];
         $_SESSION["username"] = $login["data"]["username"];
         $_SESSION["password"] = $login["data"]["password"];
         $_SESSION["role_id"] = $login["data"]["role_id"];
         $_SESSION["date"] = date("d/m/Y H:i:s");
         $this->redirect();
      }
   }

   /**
    * Aplica los filtros necesarios para el registro y, si los supera, ejecuta el registro del usuario
    */
   private function executeRegister()
   {
      //Filtramos todas las entradas
      $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
      $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL); //Este filtro automáticamente valida el email
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
      $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_STRING);
      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
      $surname = filter_input(INPUT_POST, "surname", FILTER_SANITIZE_STRING);
      $nif = filter_input(INPUT_POST, "nif", FILTER_SANITIZE_STRING);
      $telephone = filter_input(INPUT_POST, "telephone", FILTER_SANITIZE_STRING);
      $address = $_POST["address"]; //filter_input(INPUT_POST, "address", FILTER_SANITIZE_STRING);
      if (isset($_FILES['image'])) {
         $image_base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
         $image = 'data:image/' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) . ';base64,' . $image_base64;
      } else {
         $image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIAAgMAAACJFjxpAAAADFBMVEXFxcX////p6enW1tbAmiBwAAAFiElEQVR4AezAgQAAAACAoP2pF6kAAAAAAAAAAAAAAIDbu2MkvY0jiuMWWQoUmI50BB+BgRTpCAz4G6C8CJDrC3AEXGKPoMTlYA/gAJfwETawI8cuBs5Nk2KtvfiLW+gLfK9m+r3X82G653+JP/zjF8afP1S//y+An4/i51//AsB4aH+/QPD6EQAY/zwZwN8BAP50bh786KP4+VT+3fs4/noigEc+jnHeJrzxX+NWMDDh4g8+EXcnLcC9T8U5S/CdT8bcUeBEIrwBOiI8ki7Ba5+NrePgWUy89/nYyxQ8Iw3f+pWY4h1gb3eAW7sDTPEOsLc7wK1TIeDuDB+I/OA1QOUHv/dFsZQkhKkh4QlEfOULYz2nGj2/Nn1LmwR/86VxlCoAW6kCsHRGANx1RgCMo5Qh2EsZgrXNQZZShp5Liv7Il8eIc5C91EHY2hxk6bwYmNscZIReDBwtCdhbErC1JGBpScBcOgFMLQsZMQs5Whayd+UQsLYsZGlZyNyykKllISNmIUfAwifw8NXvTojAjGFrdYi11SGWVoeYWx1i6lmQCiEjFkKOVgjZ+xxIhZCtFULWHkCqxCw9gNQKmP9vNHzipdEPrRcxtVbAeDkAvve0iM2QozVD9hfjhp4YP/UrkJYDbD2AtBxgfSkAvvHEeNcDSAsilgtAWxIy91J8AXgZAJ5e33+4tuACcAG4AFwALgBXRXQB6AFcB5MXAuA6nl9/0Vx/011/1V5/1/dfTPJvRtdnu/zL6beeFO/7r+fXBYbrEkt/j+i6ytXfpuvvE/ZXOnsA/a3a/l5xf7O6v1t+Xe/vOyz6HpO8yyboM8o7rfJes77bru83THk48p7TvOs27zvOO6/73vO++z7l4cgnMPQzKPopHC0N9noSSz6LJp/Gk88jyicy5TOp6qlc+VyyfDJbPpuuns6XzyfMJzTmMyrrKZ35nNJ8Ums+q7af1tvPK+4nNodEnPKp3fnc8npyez67/qVP7+/fL8hfcMjfsOhf8cjfMclfcnn9+BkOnLECP8Q58OYeyJ40eoyF6Ee/En/JHlP6mIlRVXprF4BxtAvArV0AxtEuALd2ARhHuwDc2gVgHPX/hFv9fMBddjIGeKg/WCxlCsI46u+Ga5mCcJd+sIG9UkGAW32ZbApFAHhod4Bb3eo04h3god0BbiUHYApVCNjbHeBW+QDAXT4a7qg7r7e214057vg0QhkEHkoSwq0kIdydXw4/Q3H8hjYJ3vL0WConBJhCHQaOToeBrU0BljYFmEoVgHGUKgAPnREAt84IgLuqFgAYSUEOAHszDwuAtSkHAZhLGYIpdCLgKGUIHtocZG1zkLmUIRhxDnJU1RDA1uYga5uDzKUOwhTnIEfnxcDe5iBrcyQAYGlzkKkUYhhxDrKXQgxbSwLWUohhbknA1JKAEZOAvSUBW0sC1pYEzC0JmFoSMMJyCDhaFrK3JGDtyiFgaVnI3LKQqWUhI2YhR8tC9paFrC0LWVoWMrcsZGpZyIhZyNGykL2rSIGtlQHWVgZYWhlgbmWAqZUBRiwDHK0MsLcywNbKAGsOoNUhllaHmFsdYmp1iBHrEEerQ+w5gFYI2VodYm11iKXVIeYcQCuETK0QMmIh5MgBtELI3gohWyuErDmAVolZWiFkzgG0SszUKjGjfj6gVmKOVonZcwCtFbB9HQC+ozWDbz1bvGu9iKW1AuYcQOtFTLEX1GbIaFegN0OOHEBrhuw5gNYM2XIArRuz5gDacoB3bTnAEktxXQ4wfw0AvveM8b4tiJjSJOwLIsbXsAKeNeKCiOO3D+AVbUl0AfjGs8ZPbUnIdgFoa1LWC0BblfMuB9AeC1j6gqQE0J9LmC8AOYD2ZMb7i4bt2ZTpWoHfPoB7Tj2fXzT8N1X41vkq/QHOAAAAAElFTkSuQmCC';
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

         $params = [
            "title" => "Registrarme",
            "result" => $user->executeRegister()
         ];
         $this->view->show("Register", $params);
      }
   }
}
