<?php
class Todo
{
    public $userId;
    public $id;
    public $title;
    public $completed;

    public function __construct($userId = "", $id = "", $title = "", $completed = "")
    {
        $this->userId = $userId;
        $this->id = $id;
        $this->title = $title;
        $this->completed = $completed;
    }

    public static function fromJson($rawTodo)
    {
        $userId = (int) $rawTodo["userId"];
        $id = (int) $rawTodo["id"];
        $title = (string) $rawTodo["title"];
        $completed = (bool) $rawTodo["completed"];
        return new Todo($userId, $id, $title, $completed);
    }
}
