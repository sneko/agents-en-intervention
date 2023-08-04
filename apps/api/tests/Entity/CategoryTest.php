<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Category.
 */
#[
    PA\CoversClass(Category::class),
    PA\Group('entities'),
    PA\Group('entities_category'),
    PA\Group('category')
]
final class CategoryTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $category = new Category('category-name', null, null);

        self::assertNull($category->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $category = new Category('category-name', null, null);

        self::assertSame('category-name', $category->getName());

        $category->setName('new-name');

        self::assertSame('new-name', $category->getName());
    }


    /**
     * Test que la description soit accessible.
     */
    public function testCanGetAndSetDescription(): void
    {
        $category = new Category('category-name', null, null);

        self::assertNull($category->getDescription());

        $category->setDescription('new-description');

        self::assertSame('new-description', $category->getDescription());
    }

    /**
     * Test que la description puisse être nulle.
     */
    public function testCanGetAndSetANullDescription(): void
    {
        $category = new Category('category-name', 'category-description', null);

        self::assertSame('category-description', $category->getDescription());

        $category->setDescription(null);

        self::assertNull($category->getDescription());
    }


    /**
     * Test que la photo soit accessible.
     */
    public function testCanGetAndSetPicture(): void
    {
        $category = new Category('category-name', null, null);

        self::assertNull($category->getPicture());

        $category->setPicture('new-picture');

        self::assertSame('new-picture', $category->getPicture());
    }

    /**
     * Test que la photo puisse être nul.
     */
    public function testCanGetAndSetANullPicture(): void
    {
        $category = new Category('category-name', null, 'category-picture');

        self::assertSame('category-picture', $category->getPicture());

        $category->setPicture(null);

        self::assertNull($category->getPicture());
    }
}
