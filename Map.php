<?php
require_once 'vendor/autoload.php';
require_once 'Obstacle.php';
require_once 'BFS.php';

class Map
{
    private $start = START;
    private $end = END;
    private $obstacle = OBSTACLE;
    public $x = 0;
    public $y = 0;
    public $table;
    private $startX;
    private $endX;
    private $startY;
    private $endY;

    public function __construct($conn)
    {
        $sql = "SELECT * FROM info";
        $result = mysqli_query($conn, $sql);
        $info = [];
        while ($rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $info[] = $rows;
        }
        $this->x = $info[0]['x'];
        $this->y = $info[0]['y'];

        $this->generateTableArray($conn);

        echo json_encode($info);
    }

    public function getTable($conn)
    {
        $sql = "SELECT * FROM map";
        $result = mysqli_query($conn, $sql);

        $arr = [];
        while ($rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $arr[] = $rows;
        }

        if (count($arr) == 0) {
            return;
        } else {
            echo json_encode($arr);
        }
    }



    public function create($x, $y, $conn)
    {
        $this->x = $x;
        $this->y = $y;
        $this->obstacleRate = $x * $y / (20);

        $this->updateMapDB($conn);
        $this->generateTableArray($conn);
        $this->generataStart($conn);
        $this->generateEnd($conn);
        $this->updateInfoDB($conn);

        $sql = "SELECT * FROM map";
        $result = mysqli_query($conn, $sql);
        $arr = [];
        while ($rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $arr[] = $rows;
        }

        echo json_encode($arr);
    }

    public function solveBFS()
    {
        echo (minDistance($this->table, $this->x, $this->y));
    }

    public function solveAStar()
    {
        //TODO
    }

    private function generateTableArray($conn)
    {

        $row = array_fill(0, $this->y, 0);
        $this->table = array_fill(0, $this->x, $row);
        $sql = "SELECT * FROM map";
        $result = mysqli_query($conn, $sql);
        $temp = [];
        while ($rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $temp[] = $rows;
        }

        $this->table = [];
        $counter = 0;
        for ($i = 0; $i < $this->x; $i++) {
            for ($j = 0; $j < $this->y; $j++) {
                $this->table[$i][] = $temp[$counter++]['field'];
            }
            $this->table[] = [];
        }
        array_pop($this->table);
    }

    private function updateMapDB($conn)
    {
        $sql = "DROP TABLE IF EXISTS map";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $sql = "CREATE TABLE map (
                x int(3),
                y int(3),
                field int(1) DEFAULT 0
            )";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);



        for ($i = 0; $i < $this->x; $i++) {
            for ($j = 0; $j < $this->y; $j++) {
                $sql = "INSERT INTO map VALUES
                    (" . $i . "," . $j . ", DEFAULT)";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_execute($stmt);
            }
        }

        for ($i = 0; $i < $this->obstacleRate; $i++) {
            $randX = rand(0, $this->x - 3);
            $randY = rand(0, $this->y - 3);

            $obstacle = new Obstacle($randX, $randY, $this->x, $this->y);
            foreach ($obstacle->coords as $value) {
                $sql = "UPDATE map SET
                    field = ?
                    WHERE x = ? AND y = ? ";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "iii", $this->obstacle, $value[0], $value[1]);
                mysqli_stmt_execute($stmt);
                $this->table[$value[0]][$value[1]] = $this->obstacle;
            }
        }
    }


    private function updateInfoDB($conn)
    {
        $sql = "DROP TABLE IF EXISTS info";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $sql = "CREATE TABLE info (
            x int(3),
            y int(3),
            startX int(3),
            startY int(3),
            endX int(3),
            endY int(3)
        )";

        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $sql = "INSERT INTO info VALUES(
            ?, ?, ?, ?, ?, ?
        )";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "iiiiii", $this->x, $this->y, $this->startX, $this->startY, $this->endX, $this->endY);
        mysqli_stmt_execute($stmt);
    }

    private function generataStart($conn)
    {
        do {
            $this->startX = rand(0, $this->x - 1);
            $this->startY = rand(0, $this->y - 1);
        } while ($this->table[$this->startX][$this->startY] == $this->obstacle);
        $this->table[$this->startX][$this->startY] = $this->start;
        $sql = "UPDATE map SET
        field = ?
        WHERE x = ? AND y = ? ";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $this->start, $this->startX, $this->startY);
        mysqli_stmt_execute($stmt);
    }

    private function generateEnd($conn)
    {
        do {
            $this->endX = rand(0, $this->x - 1);
            $this->endY = rand(0, $this->y - 1);
        } while ($this->table[$this->endX][$this->endY] == $this->obstacle || $this->table[$this->endX][$this->endY] == $this->start);
        $this->table[$this->endX][$this->endY] = $this->end;
        $sql = "UPDATE map SET
        field = ?
        WHERE x = ? AND y = ? ";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $this->end, $this->endX, $this->endY);
        mysqli_stmt_execute($stmt);
    }


}
