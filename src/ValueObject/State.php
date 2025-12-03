<?php

declare(strict_types=1);


namespace App\ValueObject;


class State
{
    public function __construct(
        private readonly Player $player,
        private Monster $monster,
        private int $wave,
        private readonly int $gold,
        private readonly int $healsLeft,
        private bool $over,
        private array $log)
    {
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getMonster(): Monster
    {
        return $this->monster;
    }

    public function getWave(): int
    {
        return $this->wave;
    }

    public function getGold(): int
    {
        return $this->gold;
    }

    public function getHealsLeft(): int
    {
        return $this->healsLeft;
    }

    public function isOver(): bool
    {
        return $this->over;
    }

    public function getLog(): array
    {
        return $this->log;
    }

    public function nextWave(): void
    {
        ++$this->wave;
    }

    public function setMonster(Monster $monster): void
    {
        $this->monster = $monster;
    }

    public function endGame(): void
    {
        $this->over = true;
    }
    public function addLog(string$log): void{
        $this->log[] = $log;
    }
}
