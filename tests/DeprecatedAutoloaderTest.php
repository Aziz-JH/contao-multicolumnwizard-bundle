<?php

/**
 * This file is part of menatwork/contao-multicolumnwizard-bundle.
 *
 * (c) 2012-2019 MEN AT WORK.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    menatwork/contao-multicolumnwizard-bundle
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  2011 Andreas Schempp
 * @copyright  2011 certo web & design GmbH
 * @copyright  2013-2019 MEN AT WORK
 * @license    https://github.com/menatwork/contao-multicolumnwizard-bundle/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MenAtWork\MultiColumnWizardBundle\Test;

use MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard as MultiColumnWizardBundle;
use PHPUnit\Framework\TestCase;

/**
 * This tests the color picker event listener.
 */
class DeprecatedAutoloaderTest extends TestCase
{
    /**
     * Aliases of old classes to the new one.
     *
     * @var array
     */
    private static $classes = [
        'MultiColumnWizard' => MultiColumnWizardBundle::class,
    ];

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // Some mapping for the system.
        self::aliasContaoClass('TemplateInheritance');
        self::aliasContaoClass('System');
        self::aliasContaoClass('Controller');
    }

    /**
     * Mapping between root namespace of contao and the contao namespace.
     * Can map class, interface and trait.
     *
     * @param string $class The name of the class
     *
     * @return void
     */
    protected static function aliasContaoClass($class)
    {
        // Class.
        if (!\class_exists($class, true) && \class_exists('\\Contao\\' . $class, true)) {
            if (!\class_exists($class, false)) {
                \class_alias('\\Contao\\' . $class, $class);
            }

            return;
        }

        // Trait.
        if (!\trait_exists($class, true) && \trait_exists('\\Contao\\' . $class, true)) {
            if (!\trait_exists($class, false)) {
                \class_alias('\\Contao\\' . $class, $class);
            }

            return;
        }

        // Interface.
        if (!\interface_exists($class, true) && \interface_exists('\\Contao\\' . $class, true)) {
            if (!\interface_exists($class, false)) {
                \class_alias('\\Contao\\' . $class, $class);
            }

            return;
        }
    }

    /**
     * Provide the alias class map.
     *
     * @return array
     */
    public function provideAliasClassMap()
    {
        $values = [];
        foreach (static::$classes as $alias => $class) {
            $values[] = [$alias, $class];
        }

        return $values;
    }

    /**
     * Test if the deprecated classes are aliased to the new one.
     *
     * @param string $oldClass Old class name.
     *
     * @param string $newClass New class name.
     *
     * @dataProvider provideAliasClassMap
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testDeprecatedClassesAreAliased($oldClass, $newClass)
    {
        $this->assertTrue(class_exists($oldClass), sprintf('Class alias "%s" is not found.', $oldClass));
        $oldClassReflection = new \ReflectionClass($oldClass);
        $newClassReflection = new \ReflectionClass($newClass);
        $this->assertSame($newClassReflection->getFileName(), $oldClassReflection->getFileName());
    }
}