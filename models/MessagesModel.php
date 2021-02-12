<?php

/**
 *   Clase 'MessagesModel' que implementa el modelo de mensajes de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla messages
 */
class MessagesModel extends BaseModel
{
    private $id;
    private $user_from;
    private $user_to;
    private $content;
    private $date;

    public function __construct()
    {
        parent::__construct();
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

    public function get_messages_with()
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT * from messages where (user_from = :user AND user_to = :other) OR (user_from = :other AND user_to = :user)";
        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "user" => $_SESSION["id"],
                "other" => $this->user_to
            ]);
            $return["data"] = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!$return["data"]) {
                $return["data"] = null;
                $return["correct"] = false;
                $return["error"] = "No messages";
            }
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }
        return $return;
    }

    public function sendMessageTo(){
        $return = [
            "correct" => true,
            "error" => null
        ];

        $sql = "INSERT INTO messages (user_from, user_to, content) VALUES (:from, :to, :content)";
        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "from" => $_SESSION["id"],
                "to" => $this->user_to,
                "content" => $this->content
            ]);
            
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }
}
