<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\ActionType;
use App\Entity\Type;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité ActionType.
 */
#[
    PA\CoversClass(ActionType::class),
    PA\Group('entities'),
    PA\Group('entities_actionType'),
    PA\Group('actionType')
]
final class ActionTypeTest extends TestCase
{
    // Méthodes :

    /**
     * Renvoie un substitut de l'entité Type.
     * @return \App\Entity\Type un substitut de l'entité Type.
     */
    private function getMockForType(): Type
    {
        return $this->getMockBuilder(Type::class)
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
        $type = $this->getMockForType();
        $actionType = new ActionType('actionType-name', null, $type);

        self::assertNull($actionType->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $type = $this->getMockForType();
        $actionType = new ActionType('actionType-name', null, $type);

        self::assertSame('actionType-name', $actionType->getName());

        $actionType->setName('new-name');

        self::assertSame('new-name', $actionType->getName());
    }


    /**
     * Test que la description soit accessible.
     */
    public function testCanGetAndSetDescription(): void
    {
        $type = $this->getMockForType();
        $actionType = new ActionType('actionType-name', null, $type);

        self::assertNull($actionType->getDescription());

        $actionType->setDescription('new-description');

        self::assertSame('new-description', $actionType->getDescription());
    }

    /**
     * Test que la description puisse être nulle.
     */
    public function testCanGetAndSetANullDescription(): void
    {
        $type = $this->getMockForType();
        $actionType = new ActionType('actionType-name', 'actionType-description', $type);

        self::assertSame('actionType-description', $actionType->getDescription());

        $actionType->setDescription(null);

        self::assertNull($actionType->getDescription());
    }


    /**
     * Test que le type soit accessible.
     */
    public function testCanGetAndSetType(): void
    {
        $type = $this->getMockForType();
        $actionType = new ActionType('actionType-name', null, $type);

        self::assertSame($type, $actionType->getType());

        $otherType = $this->getMockForType();
        $actionType->setType($otherType);

        self::assertSame($otherType, $actionType->getType());
    }
}
