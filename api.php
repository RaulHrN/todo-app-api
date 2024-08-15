<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-AllowMethods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: access, Content-Type, Authorization, X-requested-With");

include_once 'db.php';
include_once 'Task.php';

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);

$resquest_method = $_SERVER["REQUEST_METHOD"];

switch ($resquest_method) {
    case "GET":
        if (!empty($_GET["id"])) {
            $task->id = intval($_GET["id"]);
            $result = $task->readOne();
        } else {
            $result = $task->read();
        }
        $num = $result->rowCount();

        if ($num > 0) {
            $task_arr = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $task_item = array(
                    "id" => $id,
                    "title" => $title,
                    "level" => $level,
                );
                array_push($task_arr, $task_item);
            }
            echo json_encode($task_arr);
        } else {
            echo json_encode(array("message" => "No tasks found."));
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"));
        $task->title = $data->title;
        $task->level = $data->level;

        if ($data->create()) {
            echo json_encode(array("message" => "Task created."));
        } else {
            echo json_encode(array("message" => "Task could not be created."));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $task->id = $data->id;
        $task->title = $data->title;
        $task->level = $data->level;

        if ($task->update()) {
            echo json_encode(array("message" => "Task updated."));
        } else {
            echo json_encode(array("message" => "Task could not be updated."));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $task->id = $data->id;

        if ($task->delete()) {
            echo json_encode(array("message" => "Task cdelete."));
        } else {
            echo json_encode(array("message" => "Task could not be deleted."));
        }
        break;

        default:
        echo json_encode(array("message" => "Invalid Request."));
}
?>