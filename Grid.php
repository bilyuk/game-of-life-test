<?php

class Grid
{
    /** @var array */
    private $data = [];

    /** @var int */
    private $rowNum;

    /** @var int */
    private $colNum;

    public function __construct(int $rowNum, int $colNum)
    {
        $this->rowNum = $rowNum;
        $this->colNum = $colNum;

        $this->data = [];

        // initialize cells
        for ($i = 0; $i < $rowNum; $i ++) {
            $row = [];
            for ($j = 0; $j < $colNum; $j++) {
                $row[] = new Cell($i, $j);
            }

            $this->data[] = $row;
        }

        // set neighbors
        foreach ($this->data as $rowIndex => $row) {
            foreach ($row as $columnIndex => $cell) {
                /** @var Cell $cell */
                for ($i=-1; $i <= 1; $i++) {
                    for ($j=-1; $j <= 1; $j++) {
                        $neighborRowIndex = ($rowIndex + $i + $this->rowNum) % $this->rowNum;
                        $neighborColumnIndex = ($columnIndex + $j + $this->colNum) % $this->colNum;
                        $cell->addNeighbor($this->data[$neighborRowIndex][$neighborColumnIndex]);
                    }
                }
            }
        }
    }

    public function seed(array $aliveCells)
    {
        foreach ($aliveCells as $cell) {
            [$row, $col] = $cell;

            /** @var Cell $cell */
            $cell = $this->data[$row][$col];
            $cell->setState(Cell::STATE_ALIVE);
        }
    }

    public function tick(): void
    {
        foreach ($this->data as $row) {
            foreach ($row as $cell) {
                $this->computeNextState($cell);
            }
        }

        foreach ($this->data as $row) {
            foreach ($row as $cell) {
                /** @var Cell $cell */
                $cell->setState($cell->getNextState());
            }
        }
    }

    private function computeNextState(Cell $cell): void
    {
        $neighborCount = $cell->getAliveNeighborsCount();

        switch (true) {
            case $cell->isAlive() && $neighborCount > 3:
            case $cell->isAlive() && $neighborCount < 2:
                $cell->setNextState(Cell::STATE_DEAD);
                break;
            case $cell->isDead() && $neighborCount === 3:
            case $cell->isAlive() && ($neighborCount === 2 || $neighborCount === 3):
                $cell->setNextState(Cell::STATE_ALIVE);
                break;
            default:
                $cell->setNextState($cell->getState());
                break;
        }
    }

    public function render()
    {
        system('clear');

        foreach ($this->data as $row) {
            foreach ($row as $item) {
                /** @var Cell $item */
                if ($item->isAlive()) {
                    echo '*';
                } else {
                    echo '.';
                }
            }

            echo PHP_EOL;
        }
    }
}
