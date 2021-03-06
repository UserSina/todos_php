<?php
include "./Database.php";
header("Content-Type: application/json; charset=UTF-8");
$database = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["_limit"])) {
        $limit = $_GET["_limit"];
    } else {
        $limit = -1;
    }
    $database->getTodos($limit);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST
    // echo file_get_contents('php://input');
    // print_r($data);
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data["userId"];
    $title = $data["title"];
    $completed = $data["completed"];
    $database->postTodo($userId, $title, $completed);
}
