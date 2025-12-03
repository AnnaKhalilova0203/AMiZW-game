<?php

declare(strict_types=1);


namespace App\Dictionary;


enum ActionType: string
{
    case ATTACK = 'attack';
    case HEAL = 'heal';
    case HEAVY = 'heavy';
    case RUN = 'run';
    

}
