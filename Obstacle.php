<?php

class Obstacle
{

    private $obstacles = array(
        array(
            "xxxx",
            " x  ",
            "  x ",
            "   x"
        ),
        array(
            "xxxx",
            "  x ",
            " x  ",
            "x   "
        ),
        array(
            "x  x",
            "    ",
            "x xx"
        ),
        array(
            " xx",
            "  x",
            "xx "
        ),

        array(
            " xx",
            "x  "
        ),
        array(
            "  x",
            " x "
        ),
        array(
            " xx",
            " x "
        ),
        array(
            "x"
        ),
    );

    public $coords = array();

    private $currentX;
    private $currentY;
    private $maxX;
    private $maxY;


    public function __construct($currentX, $currentY,$maxX,$maxY){
        $this->currentX = $currentX;
        $this->currentY = $currentY;
        $this->maxX = $maxX;
        $this->maxY = $maxY;
        if($maxX-$currentX>=4 && $maxY-$currentY>=4){
            while(!$this->getCoordinates($this->pickObstacle(4,4))){
                $this->getCoordinates($this->pickObstacle(4,4));
            }
        }
        else if($maxX-$currentX>=4 && $maxY-$currentY>=3){
            while(!$this->getCoordinates($this->pickObstacle(4,3))){
                $this->getCoordinates($this->pickObstacle(4,3));
            }
        }
        else if($maxX-$currentX>=3 && $maxY-$currentY>=3){
            while(!$this->getCoordinates($this->pickObstacle(3,3))){
                $this->getCoordinates($this->pickObstacle(3,3));
            }
        }
        else if($maxX-$currentX>=3 && $maxY-$currentY>=2){
            while(!$this->getCoordinates($this->pickObstacle(3,2))){
                $this->getCoordinates($this->pickObstacle(3,2));
            }
        }
        
    }

    private function getCoordinates($obs){
        if(is_null($obs))
            return false;
        for($i=0; $i<count($obs); $i++){
            $charArray = str_split($obs[$i],1);
            for($j=0; $j<count($charArray); $j++){
                if($charArray[$j] == 'x'){
                    $coord = array($this->currentX+$i,$this->currentY+$j);
                    array_push($this->coords,$coord);
                }
            }
        }
        return true;
    }


    private function pickObstacle($x, $y){
        $res = null;
        if($x==4 && $y==4){
            $res = rand(0,count($this->obstacles)-1);
        }
        else if($x==4 && $y==3){
            $res = rand(2,7);
        }
        else if($x==3 && $y==3){
            $res = rand(3,7);
        }
        else if($x==3 && $y==2){
            $res = rand(4,7);
        }
        else if($x==2 && $y==2){
            $res = rand(5,7);
        }
        else if($x==1 && $y==1){
            $res = rand(7,7);
        }
        if (!is_null($res)){
            return $this->obstacles[$res];
        }
        return null;
    }
}
