<?php
include 'Todo.php';
class Database
{
    protected $pdo;

    const datasource = "mysql:host=localhost;dbname=todo_demo";
    const username = "root";
    const password = "";

    public function __construct()
    {
        try {
            $this->pdo = new PDO(self::datasource, self::username, self::password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            echo "
            {
                \"error\": \"PDOException in Database.php->__construct()\",
                \"message\": \"$msg\"
            }";
        }
    }
    public function __destruct()
    {
        $this->pdo = null;
    }

    public function getTodos($limit = -1)
    {
        if ($this->pdo == null) {
            return;
        }
        if ($limit == -1) {
            $query = "SELECT * FROM `todos`";
        } else {
            $query = "SELECT * FROM `todos` LIMIT $limit";
        }
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            // Not properly formatted, create todo objects
            // print_r(json_encode($result));
            $array = [];
            foreach ($result as $row) {
                $array[] = Todo::fromJson($row);
            }
            $json = json_encode($array);
            echo $json;
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            echo "
            {
                \"error\": \"PDOException in Database.php->getTodos()\",
                \"message\": \"$msg\"
            }";
        }
    }
    public function postTodo($userId, $title, $completed)
    {
        if ($this->pdo == null) {
            return;
        }
        $compVar = 0;
        if ($completed) {
            $compVar = 1;
        }
        $query = "INSERT INTO `todos`(`userId`, `title`, `completed`) VALUES (?, ?, ?)";
        try {
            $statement = $this->pdo->prepare($query);
            $res = $statement->execute(array($userId, $title, $compVar));
            if ($res) {
                // echo "Success";
                // var_dump(http_response_code(201));

                $newTodo = new Todo($userId, (int) $this->pdo->lastInsertId(), $title, $completed);
                header('HTTP/1.1 201');
                http_response_code(201);
                echo json_encode($newTodo);
            } else {
                // echo "Failed to insert";
                // var_dump(http_response_code(500));
                header('HTTP/1.1 501 Server Error');
                $arr = array('error' => 'Internal server error');
                echo json_encode($arr);
            }
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            echo "
            {
                \"error\": \"PDOException in Database.php->postTodo()\",
                \"message\": \"$msg\"
            }";
        }
    }
}
