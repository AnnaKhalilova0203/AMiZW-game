<?php

declare(strict_types=1);


namespace App\ValueObject;


class Monster
{
    private int $maxHp;
    public function __construct(
        private string $name,
        private int $hp,
        private int $experience,
    )
    {
        $this->maxHp = $hp;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getMaxHp(): int
    {
        return $this->maxHp;
    }
    public function getHp(): int
    {
        return $this->hp;
    }

    public function takeDmg(int $hp): void
    {
        $this->hp -= $hp;
    }
    public function getExperience(): int
    {
        return $this->experience;
    }
}
