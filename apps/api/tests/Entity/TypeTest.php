<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Type;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Type.
 */
#[
    PA\CoversClass(Type::class),
    PA\UsesClass(Category::class),
    PA\Group('entities'),
    PA\Group('entities_type'),
    PA\Group('type')
]
final class TypeTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $category = new Category('category-name', null, null);
        $type = new Type('type-name', null, null, $category);

        self::assertNull($type->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $category = new Category('category-name', null, null);
        $type = new Type('type-name', null, null, $category);

        self::assertSame('type-name', $type->getName());

        $type->setName('new-name');

        self::assertSame('new-name', $type->getName());
    }


    /**
     * Test que la description soit accessible.
     */
    public function testCanGetAndSetDescription(): void
    {
        $category = new Category('category-name', null, null);
        $type = new Type('type-name', null, null, $category);

        self::assertNull($type->getDescription());

        $type->setDescription('new-description');

        self::assertSame('new-description', $type->getDescription());
    }

    /**
     * Test que la description puisse être nulle.
     */
    public function testCanGetAndSetANullDescription(): void
    {
        $category = new Category('category-name', null, null);
        $type = new Type('type-name', 'type-description', null, $category);

        self::assertSame('type-description', $type->getDescription());

        $type->setDescription(null);

        self::assertNull($type->getDescription());
    }


    /**
     * Test que la photo soit accessible.
     */
    public function testCanGetAndSetPicture(): void
    {
        $category = new Category('category-name', null, null);
        $type = new Type('type-name', null, null, $category);

        self::assertNull($type->getPicture());

        $type->setPicture('new-picture');

        self::assertSame('new-picture', $type->getPicture());
    }

    /**
     * Test que la photo puisse être nul.
     */
    public function testCanGetAndSetANullPicture(): void
    {
        $category = new Category('category-name', null, null);
        $type = new Type('type-name', null, 'type-picture', $category);

        self::assertSame('type-picture', $type->getPicture());

        $type->setPicture(null);

        self::assertNull($type->getPicture());
    }


    /**
     * Test que la categorie soit accessible.
     */
    public function testCanGetAndSetCategory(): void
    {
        $category = new Category('category-name', null, null);
        $type = new Type('type-name', null, null, $category);

        self::assertSame('category-name', $type->getCategory()->getName());

        $newCategory = new Category('category-name-2', null, null);
        $type->setCategory($newCategory);

        self::assertSame('category-name-2', $type->getCategory()->getName());
    }
}
