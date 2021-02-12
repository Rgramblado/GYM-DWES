<?php

/**
 *   Clase 'UserModel' que implementa el modelo de usuarios de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla usuarios
 */
class UserModel extends BaseModel
{

   private $id;

   private $username;

   private $password;

   private $name;

   private $surname;

   private $nif;

   private $telephone;

   private $address;

   private $email;

   private $image;

   private $user_confirmed;

   private $email_confirmed;

   private $role_id;


   public function __construct()
   {
      // Se conecta a la BD
      parent::__construct();
      $this->table = "user";
   }

   public function __set($name, $value)
   {
      if (property_exists($this, $name)) {
         $this->$name = $value;
      }
   }

   public function __get($name)
   {
      if (property_exists($this, $name)) {
         return $this->$name;
      }
   }

   public function getId()
   {
      $return = [
         "correct" => true,
         "data" => null,
         "error" => null
      ];
      $sql = "SELECT id from user where username = :username";

      try {
         $this->db->beginTransaction();
         $query = $this->db->prepare($sql);
         $query->execute([
            "username" => $this->username
         ]);

         $return["data"] = $query->fetch(PDO::FETCH_ASSOC);
         if (!$return["data"]) {
            $return["correct"] = false;
            $return["error"] = "No user";
         }
         $this->db->commit();
      } catch (PDOException $ex) {
         $return["correct"] = false;
         $return["error"] = $ex;
      }
      return $return;
   }

   public function getUserById($id)
   {
      $return = [
         "correct" => true,
         "data" => null,
         "error" => null
      ];
      $sql = "SELECT username,password, email, name, surname, nif, telephone, address, email_confirmed, user_confirmed, role_id, image from user where id = :id";

      try {
         $this->db->beginTransaction();
         $query = $this->db->prepare($sql);
         $query->execute([
            "id" => $id
         ]);

         $return["data"] = $query->fetch(PDO::FETCH_ASSOC);
         if (!$return["data"]) {
            $return["correct"] = false;
            $return["error"] = "No user";
         }
         $this->db->commit();
      } catch (PDOException $ex) {
         $return["correct"] = false;
         $return["error"] = $ex;
      }
      return $return;
   }

   /**
    * Función que obtiene los datos de los usuarios que busquemos.
    */
   public function searchUsers()
   {
      $return = [
         "correct" => true,
         "data" => null,
         "count" => null,
         "error" => null
      ];


      if (isset($_GET["page"]) && intval(filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT)) > 1) {
         $offset = intval(filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT)) * 2;
      } else {
         $offset = 0;
      }
      $sql = "SELECT id, username, image, email_confirmed, user_confirmed, role_id from user where username LIKE :username LIMIT :offset, 2";
      $return["count"] = ($this->countUsers())["data"]["count(id)"];


      try {
         $this->db->beginTransaction();
         $query = $this->db->prepare($sql);
         $query->bindParam(':offset', $offset, PDO::PARAM_INT);
         $likeUsername = '%' . $this->username . '%';
         $query->bindParam(':username', $likeUsername, PDO::PARAM_STR);
         $query->execute();

         $return["data"] = $query->fetchAll(PDO::FETCH_ASSOC);

         if (!$return["data"]) {
            $return["correct"] = false;
            $return["error"] = "No user";
         }
         $this->db->commit();
      } catch (PDOException $ex) {
         $return["correct"] = false;
         $return["error"] = $ex;
      }
      return $return;
   }

   /**
    * Función que devuelve el número de usuarios que existen con los datos proporcionados.
    */
   public function countUsers()
   {
      $return = [
         "correct" => true,
         "data" => null,
         "error" => null
      ];

      $sql = "SELECT count(id) from user where username LIKE :username ";



      try {
         $this->db->beginTransaction();
         $query = $this->db->prepare($sql);
         $query->execute([
            "username" => '%' . $this->username . '%'
         ]);

         $return["data"] = $query->fetch(PDO::FETCH_ASSOC);
         if (!$return["data"]) {
            $return["correct"] = false;
            $return["error"] = "No user";
         }
         $this->db->commit();
      } catch (PDOException $ex) {
         $return["correct"] = false;
         $return["error"] = $ex;
      }
      return $return;
   }

   /**
    * Función que ejecuta el login.
    */
   public function executeLogin()
   {
      $return = [
         "correct" => true,
         "data" => null,
         "error" => null
      ];
      $sql = "SELECT id, username, password, role_id, user_confirmed from user where (username = :username or email = :username) and password = :password";

      try {
         $this->db->beginTransaction();
         $query = $this->db->prepare($sql);
         $query->execute([
            "username" => $this->username,
            "password" => hash("sha256", $this->password)
         ]);

         $return["data"] = $query->fetch(PDO::FETCH_ASSOC);
         if (!$return["data"]) {
            $return["correct"] = false;
            $return["error"] = "El usuario y/o la contraseña son incorrectos";
         }
      } catch (PDOException $ex) {
         $return["correct"] = false;
         $return["error"] = $ex;
      }
      return $return;
   }

   /**
    * Función para introducir un usuario nuevo en la base de datos.
    */
   public function executeRegister()
   {
      $return = [
         "correct" => true,
         "error" => null
      ];
      $sql = "INSERT into user(username, password, email, name, surname, nif, telephone, address, image) VALUES 
                              (:username, :password, :email, :name, :surname, :nif, :telephone, :address, :image) ";

      try {
         $this->db->beginTransaction();
         $query = $this->db->prepare($sql);
         $query->execute([
            "username" => $this->username,
            "password" => hash('sha256', $this->password),
            "email" => $this->email,
            "name" => $this->name,
            "surname" => $this->surname,
            "nif" => $this->nif,
            "telephone" => $this->telephone,
            "address" => $this->address,
            "image" => $this->image
         ]);
         $this->db->commit();
      } catch (PDOException $ex) {
         $return["correct"] = false;
         $return["error"] = $ex;
      }
      return $return;
   }


   public function updateUser($id)
   {
      $return = [
         "correct" => true,
         "error" => null
      ];

      if ($this->image == null) {
         $sql = "UPDATE `user` SET 
         `username`=:username,
         `password`=:password,
         `email`=:email,
         `name`=:name,
         `surname`=:surname,
         `nif`=:nif,
         `telephone`=:telephone,
         `address`=:address,
         `email_confirmed`=:email_confirmed,
         `user_confirmed`=:user_confirmed,
         `role_id`=:role_id,
         WHERE id = $id";
      } else {
         $sql = "UPDATE `user` SET 
         `username`=:username,
         `password`=:password,
         `email`=:email,
         `name`=:name,
         `surname`=:surname,
         `nif`=:nif,
         `telephone`=:telephon,
         `address`=:address,
         `email_confirmed`=:email_confirmed,
         `user_confirmed`=:user_confirmed,
         `role_id`=:role_id,
         `image`=:image
         WHERE id = $id";
      }

      try {
         $this->db->beginTransaction();
         $params = [
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email,
            "name" => $this->name,
            "surname" => $this->surname,
            "nif" => $this->nif,
            "telephone" => $this->telephone,
            "address" => $this->address,
            "email_confirmed" => $this->email_confirmed,
            "user_confirmed" => $this->user_confirmed,
            "role_id" => $this->role_id
         ];
         $query = $this->db->prepare($sql);
         $query->execute($params);
         $this->db->commit();
      } catch (PDOException $ex) {
         $return["correct"] = false;
         $return["error"] = "Ha ocurrido algún error";
      }
   }

   public function activateUser($id)
   {
      $sql = "UPDATE user SET user_confirmed = 1 WHERE id = $id";
      $this->db->beginTransaction();
      $query = $this->db->prepare($sql);
      $query->execute();
      $this->db->commit();
   }

   public function deactivateUser($id)
   {
      $sql = "UPDATE user SET user_confirmed = 0 WHERE id = $id";
      $this->db->beginTransaction();
      $query = $this->db->prepare($sql);
      $query->execute();
      $this->db->commit();
   }
}
