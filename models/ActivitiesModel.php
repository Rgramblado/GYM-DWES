<?php

/**
 *   Clase 'MessagesModel' que implementa el modelo de mensajes de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla messages
 */
class ActivitiesModel extends BaseModel
{
    private $id;
    private $name;
    private $date_in;
    private $date_out;
    private $capacity;
    private $color;

    public function __construct()
    {
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

    public function selectActiveActivities(){
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT * FROM activities WHERE date_out is null";

        try{
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute();
            $return["data"] = $query->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
        }catch(PDOException $ex){
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function insertActivity(){
        $return = [
            "correct" => true,
            "error" => null
        ];

        $sql = "INSERT INTO activities (name, color, capacity) VALUES (:name, :color, :capacity)";

        try{
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "name" => $this->name,
                "color" => $this->color,
                "capacity" => $this->capacity
            ]);
            $this->db->commit();
        }catch(PDOException $ex){
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function deleteActivity(){
        $return = [
            "correct" => true,
            "error" => null
        ];

        $sql = "UPDATE activities set date_out = current_timestamp  WHERE id = :id";

        try{
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                
                "id" => $this->id
            ]);
            $this->db->commit();
        }catch(PDOException $ex){
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }
}
