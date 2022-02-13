<?php

class Cell
{
    public const STATE_ALIVE = 1;
    public const STATE_DEAD = 0;

    /** @var int */
    private $x;

    /** @var int */
    private $y;

    /** @var int */
    private $state = self::STATE_DEAD;

    /** @var int */
    private $nextState = self::STATE_DEAD;

    /** @var array|self[] */
    private $neighbors = [];

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): void
    {
        $this->state = $state;
    }

    public function isAlive(): bool
    {
        return $this->state === self::STATE_ALIVE;
    }

    public function isDead(): bool
    {
        return false === $this->isAlive();
    }

    public function addNeighbor(Cell $cell): void
    {
        if ($cell === $this) {
            return;
        }

        if (false === array_search($cell, $this->neighbors)) {
            $this->neighbors[] = $cell;

            $cell->addNeighbor($this);
        }
    }

    public function getAliveNeighborsCount(): int
    {
        return count(array_filter($this->neighbors, function (Cell $cell) {
            return $cell->isAlive();
        }));
    }

    public function getNextState(): int
    {
        return $this->nextState;
    }

    public function setNextState(int $newState): void
    {
        $this->nextState = $newState;
    }
}
