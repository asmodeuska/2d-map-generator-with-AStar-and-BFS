<?php

include_once("QItem.php");

function minDistance($grid, $x, $y)
{
    $arr[] = [0, 0];
    $source = new QItem(0, 0, 0, $arr);
    $visited = [];
    for ($i = 0; $i < $x; $i++) {
        $visited[] = array_fill(0, $y, 0);
    }
    for ($i = 0; $i < $x; $i++) {
        for ($j = 0; $j < $y; $j++) {
            if ($grid[$i][$j] == 8)
                $visited[$i][$j] = true;
            else
                $visited[$i][$j] = false;
            if ($grid[$i][$j] == 1) {
                $source->row = $i;
                $source->col = $j;
            }
        }
    }

    $q = [];
    array_push($q, $source);
    $visited[$source->row][$source->col] = true;
    while (count($q) != 0) {
        $p = $q[0];
        array_shift($q);

        if ($grid[$p->row][$p->col] == 9) {
            array_push($p->prev, [$p->row, $p->col]);
            array_shift($p->prev);
            array_shift($p->prev);
            echo json_encode($p->prev);
        }

        $arr = $p->prev;
        array_push($arr, [$p->row, $p->col]);

        if ($p->row - 1 >= 0 && $visited[$p->row - 1][$p->col] == false) {
            array_push($q, (new QItem($p->row - 1, $p->col, $p->dist + 1, $arr)));
            $visited[$p->row - 1][$p->col] = true;
        }

        if ($p->row + 1 < $y && $visited[$p->row + 1][$p->col] == false) {
            array_push($q, (new QItem($p->row + 1, $p->col, $p->dist + 1, $arr)));
            $visited[$p->row + 1][$p->col] = true;
        }

        if ($p->col - 1 >= 0 && $visited[$p->row][$p->col - 1] == false) {
            array_push($q, (new QItem($p->row, $p->col - 1, $p->dist + 1, $arr)));
            $visited[$p->row][$p->col - 1] = true;
        }

        if ($p->col + 1 < $x && $visited[$p->row][$p->col + 1] == false) {
            array_push($q, (new QItem($p->row, $p->col + 1, $p->dist + 1, $arr)));
            $visited[$p->row][$p->col + 1] = true;
        }

    }
    return;
}
