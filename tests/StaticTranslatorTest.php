<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2021 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2021 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator\Test;

use ContaoCommunityAlliance\Translator\StaticTranslator;

/**
 * Test case that test the StaticTranslator class.
 */
class StaticTranslatorTest extends TestCase
{
    /**
     * Test that the translator returns the own instance for the methods where it should.
     *
     * @return void
     */
    public function testReturnsSelf()
    {
        $translator = new StaticTranslator();

        self::assertSame($translator, $translator->setValue('', ''));
        self::assertSame($translator, $translator->setValuePluralized('', '', 1, 1));
    }

    /**
     * Test that the translator always returns the original string when no translation value can be found.
     *
     * @return void
     */
    public function testReturnUntranslated()
    {
        $translator = new StaticTranslator();
        self::assertSame('test', $translator->translate('test'));
        self::assertSame('test', $translator->translate('test', 'default'));
        self::assertSame('test', $translator->translate('test', 'default', array(), 'default'));
        self::assertSame('test', $translator->translate('test', 'default', array(), 'de'));

        self::assertSame('test', $translator->translatePluralized('test', 1));
        self::assertSame('test', $translator->translatePluralized('test', 1, 'default'));
        self::assertSame('test', $translator->translatePluralized('test', 1, 'default', array(), 'default'));
        self::assertSame('test', $translator->translatePluralized('test', 1, 'default', array(), 'de'));
    }

    /**
     * Test the default domain handling.
     *
     * @return void
     */
    public function testDefaultDomain()
    {
        $translator = new StaticTranslator();

        $translator->setValue('test-default-domain', 'default-domain-value');
        $translator->setValue('test-default-domain', 'default-domain-value-en', null, 'en');

        self::assertSame('default-domain-value', $translator->translate('test-default-domain'));
        self::assertSame('default-domain-value', $translator->translate('test-default-domain', null, array('unused')));
        self::assertSame(
            'default-domain-value-en',
            $translator->translate('test-default-domain', null, array('unused'), 'en')
        );
    }

    /**
     * Test the custom domain handling.
     *
     * @return void
     */
    public function testCustomDomain()
    {
        $translator = new StaticTranslator();

        $translator->setValue('test-custom-domain', 'custom-domain-value', 'custom');
        $translator->setValue('test-custom-domain', 'custom-domain-value-en', 'custom', 'en');

        self::assertSame('custom-domain-value', $translator->translate('test-custom-domain', 'custom'));
        self::assertSame(
            'custom-domain-value',
            $translator->translate('test-custom-domain', 'custom', array('unused'))
        );
        self::assertSame(
            'custom-domain-value-en',
            $translator->translate('test-custom-domain', 'custom', array('unused'), 'en')
        );
    }

    /**
     * Test pluralization.
     *
     * @return void
     */
    public function testPluralization()
    {
        $translator = new StaticTranslator();

        $translator->setValuePluralized('apple', 'an apple', 1, 1);
        $translator->setValuePluralized('apple', 'a few apples', null, 5);
        $translator->setValuePluralized('apple', 'a dozen of apples', 12, 12);
        $translator->setValuePluralized('apple', 'many apples', 13);

        self::assertSame('an apple', $translator->translatePluralized('apple', 1));
        self::assertSame('a few apples', $translator->translatePluralized('apple', 3));
        self::assertSame('a few apples', $translator->translatePluralized('apple', 5));
        self::assertSame('a dozen of apples', $translator->translatePluralized('apple', 12));
        self::assertSame('many apples', $translator->translatePluralized('apple', 13));
        self::assertSame('many apples', $translator->translatePluralized('apple', 100));
    }
}
