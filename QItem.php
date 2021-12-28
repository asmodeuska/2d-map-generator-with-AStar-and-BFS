<?php
class QItem {
     

    public $row;
    public $col;
    public $dist;
    public $prev;

    public function __construct($x, $y, $w, $prev)
    {
        $this->row = $x;
        $this->col = $y;
        $this->dist = $w;
        $this->prev = $prev;
    }
};