<?php

class ScheduleModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "schedule";
    }

    public function insertUserInSchedule($sch_id, $date)
    {
        $return = [
            "correct" => true,
            "error" => null
        ];

        try {
            $this->db->beginTransaction();
            $sql = "INSERT INTO  user_in_activity (user_id, schedule_id, date) VALUES (:user_id, :schedule_id, STR_TO_DATE(:date, '%d/%m/%Y'))";
            $query = $this->db->prepare($sql);
            $query->execute([
                "user_id" => $_SESSION["id"],
                "schedule_id" => $sch_id,
                "date" => $date
            ]);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function insertNewTimeSlot($activity_id, $hour_init, $hour_end, $day)
    {
        $return = [
            "correct" => true,
            "error" => null
        ];

        try {
            $this->db->beginTransaction();
            $sql = "INSERT INTO schedule (activity_id, hour_init, hour_end, day, date_cancel) VALUES (:activity_id, STR_TO_DATE(:hour_init, '%H:%i'), STR_TO_DATE(:hour_end, '%H:%i'), :day, null)";
            $query = $this->db->prepare($sql);
            $query->execute([
                "activity_id" => $activity_id,
                "hour_init" => $hour_init,
                "hour_end" => $hour_end,
                "day" => $day
            ]);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function selectAllScheduleFromDay($day)
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT * FROM schedule WHERE day = :day AND date_cancel is null";

        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "day" => $day
            ]);
            $return["data"] = $query->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function selectAllSchedule()
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT `sch`.id id, `sch`.activity_id activity_id, `sch`.hour_init hour_init, `sch`.hour_end hour_end , `sch`.day day, `acts`.name name, `acts`.color color, `acts`.capacity capacity FROM `schedule` `sch` JOIN `activities` `acts` ON `sch`.`activity_id` = `acts`.id WHERE sch.date_cancel is null AND acts.date_out IS NULL";

        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute();
            $return["data"] = $query->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function selectCountUsersInActivity($sch_id, $date)
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT COUNT(user_id) `count` FROM user_in_activity WHERE schedule_id = :schedule_id AND date = STR_TO_DATE(:date,'%d/%m/%Y')";

        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "schedule_id" => $sch_id,
                "date" => $date
            ]);
            $return["data"] = $query->fetch(PDO::FETCH_ASSOC);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function checkUserInActivity($sch_id, $date)
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT COUNT(user_id) `count` FROM user_in_activity WHERE schedule_id = :schedule_id AND date = STR_TO_DATE(:date,'%d/%m/%Y') AND user_id = :user_id";

        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "user_id" => $_SESSION["id"],
                "schedule_id" => $sch_id,
                "date" => $date
            ]);
            $return["data"] = ($query->fetch(PDO::FETCH_ASSOC))["count"] > 0 ? true : false;
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function selectDayFromSchedule($act_id)
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT day FROM schedule WHERE id = :activity_id AND date_cancel is null";

        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "activity_id" => $act_id
            ]);
            $return["data"] = $query->fetch(PDO::FETCH_ASSOC);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }



    public function deleteTimeslot($id)
    {
        $return = [
            "correct" => true,
            "error" => null
        ];

        try {
            $this->db->beginTransaction();
            $sql = "UPDATE schedule SET date_cancel = now() WHERE id = :id";
            $query = $this->db->prepare($sql);
            $query->execute([
                "id" => $id
            ]);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function getUserActivities()
    {
        $return = [
            "correct" => true,
            "data" => null,
            "error" => null
        ];

        $sql = "SELECT ua.id, ua.date, s.hour_init, s.hour_end, s.date_cancel, a.name, a.date_out from user_in_activity ua JOIN schedule s on (ua.schedule_id = s.id) JOIN activities a on (s.activity_id = a.id) where ua.user_id = :user_id";
        if(isset($_GET["status"])){
            switch(filter_input(INPUT_GET, "status", FILTER_SANITIZE_STRING)){
                case "finished":
                    $date = (new DateTime())->format("Y-m-d");
                    $time = (new DateTime())->format("H:i");
                    $sql .= " and (ua.date < DATE(\"" . $date . "\") OR (ua.date = DATE(\"" . $date . "\") and s.hour_init < TIME(\"" . $time . "\"))) and s.date_cancel is null and a.date_out is null";
                    break;
                case "cancelled":
                    $sql .= " and s.date_cancel is not null OR a.date_out is not null";
                    break;
                case "programmed":
                    $date = (new DateTime())->format("Y-m-d");
                    $time = (new DateTime())->format("H:i");
                    $sql .= " and ua.date >= DATE(\"" . $date . "\") and s.hour_init >= TIME(\"" . $time . "\") and s.date_cancel is null and a.date_out is null";
                    break;
            }
        }
        
        if(isset($_GET["orderby"])){
            switch(filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING)){
                case "activity":
                    $sql.= isset($_GET["desc"]) ? " ORDER BY a.name DESC" : " ORDER BY a.name";
                    break;
                case "date":
                    $sql.= isset($_GET["desc"]) ? " ORDER BY ua.date DESC, s.hour_init DESC" :" ORDER BY ua.date, s.hour_init";
                    break;
            }
        }

        try {
            $this->db->beginTransaction();
            $query = $this->db->prepare($sql);
            $query->execute([
                "user_id" => $_SESSION["id"]
            ]);
            $return["data"] = $query->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }

    public function deleteUserFromActivity($id){
        $return = [
            "correct" => true,
            "error" => null
        ];

        try {
            $this->db->beginTransaction();
            $sql = "DELETE FROM user_in_activity WHERE id = :id";
            $query = $this->db->prepare($sql);
            $query->execute([
                "id" => $id
            ]);
            $this->db->commit();
        } catch (PDOException $ex) {
            $return["correct"] = false;
            $return["error"] = $ex;
        }

        return $return;
    }
}
