<?php

declare(strict_types=1);


namespace App\ValueObject;


class Player
{
    public const SCALE_LEVELS = [
        1 => 100,
        2 => 250,
        3 => 500,
        4 => 1100,
        5 => 2500
    ];
    public function __construct(private int $hp, private int $level, private int $experience)
    {
    }

    public function getHp(): int
    {
        return $this->hp;
    }

    public function takeDmg(int $dmg): void
    {
        $this->hp -= $dmg;
    }
    public function setHp(int $hp): void
    {
        $this->hp = $hp;
    }
    public function heal(int $hp): void {
        $this->hp += $hp;
    }
    public function addLevel(): void
    {
        $this->level ++;
    }
    public function getLevel(): int{
        return $this->level;
    }
    public function addExperience(int $experience): void
    {
        $this->experience += $experience;
    }
    public function getExperience(): int{

    return $this->experience;
    }
}
