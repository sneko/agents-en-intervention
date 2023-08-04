<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Action;
use App\Entity\ActionType;
use App\Entity\User;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Action.
 */
#[
    PA\CoversClass(Action::class),
    PA\Group('entities'),
    PA\Group('entities_action'),
    PA\Group('action')
]
final class ActionTest extends TestCase
{
    // Traits :
    use InterventionMock;


    // Méthodes :

    /**
     * Renvoie un substitut de l'entité User.
     * @return \App\Entity\User un substitut de l'entité User.
     */
    private function getMockForParticipant(): User
    {
        return $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Renvoie un substitut de l'entité ActionType.
     * @return \App\Entity\ActionType un substitut de l'entité ActionType.
     */
    private function getMockForActionType(): ActionType
    {
        return $this->getMockBuilder(ActionType::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }


    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $action = new Action(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-02 00:00:00'),
            $this->getMockForActionType(),
            $this->getMockForIntervention(),
            $this->getMockForParticipant()
        );

        self::assertNull($action->getId());
    }


    /**
     * Test que la date de création soit accessible.
     */
    public function testCanGetAndSetBeginAt(): void
    {
        $action = new Action(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-02 00:00:00'),
            $this->getMockForActionType(),
            $this->getMockForIntervention(),
            $this->getMockForParticipant()
        );

        self::assertSame(
            '2023-01-01 00:00:00',
            $action->getBeginAt()->format('Y-m-d H:i:s')
        );

        $action->setBeginAt(new \DateTimeImmutable('2023-02-01 00:00:00'));

        self::assertSame(
            '2023-02-01 00:00:00',
            $action->getBeginAt()->format('Y-m-d H:i:s')
        );
    }


    /**
     * Test que la date de fin soit accessible.
     */
    public function testCanGetAndSetEndAt(): void
    {
        $action = new Action(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForActionType(),
            $this->getMockForIntervention(),
            $this->getMockForParticipant()
        );

        self::assertNull($action->getEndAt());

        $action->setEndAt(new \DateTimeImmutable('2023-01-02 00:00:00'));

        self::assertSame(
            '2023-01-02 00:00:00',
            $action->getEndAt()->format('Y-m-d H:i:s')
        );
    }

    /**
     * Test que la date de fin puisse être nulle.
     */
    public function testCanGetAndSetANullEndAt(): void
    {
        $action = new Action(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-02 00:00:00'),
            $this->getMockForActionType(),
            $this->getMockForIntervention(),
            $this->getMockForParticipant()
        );

        self::assertSame(
            '2023-01-02 00:00:00',
            $action->getEndAt()->format('Y-m-d H:i:s')
        );

        $action->setEndAt(null);

        self::assertNull($action->getEndAt());
    }


    /**
     * Test que le type d'action soit accessible.
     */
    public function testCanGetAndSetActionType(): void
    {
        $actionType = $this->getMockForActionType();
        $action = new Action(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-02 00:00:00'),
            $actionType,
            $this->getMockForIntervention(),
            $this->getMockForParticipant()
        );

        self::assertSame($actionType, $action->getActionType());

        $otherActionType = $this->getMockForActionType();
        $action->setActionType($otherActionType);

        self::assertSame($otherActionType, $action->getActionType());
    }


    /**
     * Test que l'intervention soit accessible.
     */
    public function testCanGetAndSetIntervention(): void
    {
        $intervention = $this->getMockForIntervention();
        $action = new Action(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-02 00:00:00'),
            $this->getMockForActionType(),
            $intervention,
            $this->getMockForParticipant()
        );

        self::assertSame($intervention, $action->getIntervention());

        $otherIntervention = $this->getMockForIntervention();
        $action->setIntervention($otherIntervention);

        self::assertSame($otherIntervention, $action->getIntervention());
    }


    /**
     * Test que l'intervention soit accessible.
     */
    public function testCanGetAndSetParticipant(): void
    {
        $participant = $this->getMockForParticipant();
        $action = new Action(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-02 00:00:00'),
            $this->getMockForActionType(),
            $this->getMockForIntervention(),
            $participant
        );

        self::assertSame($participant, $action->getParticipant());

        $otherParticipant = $this->getMockForParticipant();
        $action->setParticipant($otherParticipant);

        self::assertSame($otherParticipant, $action->getParticipant());
    }
}
