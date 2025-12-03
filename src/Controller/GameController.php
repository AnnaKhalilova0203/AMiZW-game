<?php

declare(strict_types=1);


namespace App\Controller;


use App\Dictionary\ActionType;
use App\Helper\DmgHelper;
use App\ValueObject\Monster;
use App\ValueObject\Player;
use App\ValueObject\State;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game')]
class GameController extends AbstractController
{
    #[Route('', name: 'game', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('state')) {
            $this->resetState($session);
        }
$state =  $session->get('state');
        return $this->render('game/index.html.twig', [
                'state' => $state,
                'log'=>$state-> getLog()
        ]);
    }

    #[Route('/action/{actionName}', name: 'game_action', methods: ['POST'])]
    public function action(Request $req, string $actionName): Response
    {
        $session = $req->getSession();
        if (!$session->has('state'))
        {
            $this->resetState($session);
        }
        /** @var State $state */
        $state = $session->get('state');

        if ($state->isOver()) {
            return $this->redirectToRoute('game');
        }

        switch ($actionName) {
            case ActionType::ATTACK->value:
                $dmg = DmgHelper::calculateDamagewithlevel(8,18,$state->getPlayer()->getLevel());
                $state->getMonster()->takeDmg($dmg);
                $state -> addLog(sprintf("gracz zadal %d obrazen", $dmg));
                break;

            case ActionType::HEAL->value:
                $heal = 30;
                $state ->getPlayer()->heal($heal);
                $state -> addLog(sprintf("gracz wyleczyl sie za %d hp", $heal));
                break;

            case ActionType ::HEAVY ->value:
                $dmg = DmgHelper::calculateDamagewithlevel(14,25,$state->getPlayer()->getLevel());
                $state->getMonster()->takeDmg($dmg);
                $state -> addLog(sprintf("gracz zadal %d obrazen", $dmg));
                break;
            
            case ActionType ::RUN ->value:
                $state -> nextWave();
                $state -> addLog(sprintf("gracz uciekl z pola bitwy"));
                break;
            default:
                // wrong action
                break;
        }

        if ($state->getMonster()->getHp() <= 0) {
            $state->getPlayer()->addExperience($state->getMonster()->getExperience());
            if ($state ->getPlayer() ->getExperience() < Player::SCALE_LEVELS[1]){
                    $state ->getPlayer()->addLevel();
            }
            $state->nextWave();
            $state->setMonster($this->spawnMonster($state->getWave()));
            $session->set('state', $state);
            return $this->redirectToRoute('game');
        }


        if (!$state->isOver()) {
            $monsterDmg = DmgHelper::calculateDamage(6,14);
            $state -> addLog(sprintf("potwór zadal %d obrazen", $monsterDmg));
            $state->getPlayer()->takeDmg($monsterDmg);
            if ($state->getPlayer()->getHp() <= 0) {
                $state->getPlayer()->setHp(0);
                $state->endGame();
            }
        }

        $session->set('state', $state);
        return $this->redirectToRoute('game');
    }

    #[Route('/reset', name: 'game_reset', methods: ['POST'])]
    public function reset(Request $req): Response
    {
        $this->resetState($req->getSession());
        return $this->redirectToRoute('game');
    }

    private function resetState(Session $session): void
    {
        $session->set('state', new State(
            new Player(100,1,0),
            new Monster('Goblin', 50,15),
            1,
            0,
            3,
            false,
            [['['.date('H:i:s').']', 'Bitwa się zaczyna!']]
            )
        );
    }

    private function spawnMonster(int $wave): Monster
    {
        if ($wave % 3 === 0) {
            return new Monster('BOSS Troll', 120 + ($wave-3)*15,15*($wave/3));
        }
        if ($wave >= 2) {
            return new Monster('Ork', 80 + ($wave-2)*12,12*($wave/7));
        }
        return new Monster('Goblin', 50 + ($wave-1)*10,10*($wave/10));
    }
}
