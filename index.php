<?php

include "Cell.php";
include "Grid.php";

$colNum = 25;
$rowNum = 25;

$grid = new Grid($rowNum, $colNum);

$aliveCells = [
    [11, 12],
    [12, 13],
    [13, 13],
    [13, 12],
    [13, 11],
];
$grid->seed($aliveCells);
$grid->render();

while (true) {
    $grid->tick();
    $grid->render();

    usleep(1000);
}
