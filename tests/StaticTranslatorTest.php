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

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator\Test;

use ContaoCommunityAlliance\Translator\StaticTranslator;

/** @covers \ContaoCommunityAlliance\Translator\StaticTranslator */
class StaticTranslatorTest extends TestCase
{
    /**
     * Test that the translator returns the own instance for the methods where it should.
     */
    public function testReturnsSelf(): void
    {
        $translator = new StaticTranslator();

        self::assertSame($translator, $translator->setValue('', ''));
        self::assertSame($translator, $translator->setValuePluralized('', '', 1, 1));
    }

    public function translateProvider()
    {
        $translator = new StaticTranslator();

        yield 'returns the original string 1' => [$translator, 'test', ['test']];
        yield 'returns the original string 2' => [$translator, 'test', ['test', 'default']];
        yield 'returns the original string 3' => [$translator, 'test', ['test', 'default', [], 'default']];
        yield 'returns the original string 4' => [$translator, 'test', ['test', 'default', [], 'de']];

        $translator = new StaticTranslator();
        $translator->setValue('test-default-domain', 'default-domain-value');
        $translator->setValue('test-default-domain', 'default-domain-value-en', null, 'en');

        yield 'uses the default domain 1' => [$translator, 'default-domain-value', ['test-default-domain']];
        yield 'uses the default domain 2' =>
            [$translator, 'default-domain-value', ['test-default-domain', null, ['unused']]];
        yield 'uses the default domain 3' =>
            [$translator, 'default-domain-value-en', ['test-default-domain', null, ['unused'], 'en']];

        $translator = new StaticTranslator();
        $translator->setValue('test-custom-domain', 'custom-domain-value', 'custom');
        $translator->setValue('test-custom-domain', 'custom-domain-value-en', 'custom', 'en');

        yield 'reads from custom domain 1' => [$translator, 'custom-domain-value', ['test-custom-domain', 'custom']];
        yield 'reads from custom domain 2' =>
            [$translator, 'custom-domain-value', ['test-custom-domain', 'custom', ['unused']]];
        yield 'reads from custom domain 3' =>
            [$translator, 'custom-domain-value-en', ['test-custom-domain', 'custom', ['unused'], 'en']];
    }

    /** @dataProvider translateProvider */
    public function testTranslate(StaticTranslator $translator, string $expected, array $arguments): void
    {
        self::assertSame($expected, call_user_func_array([$translator, 'translate'], $arguments));
    }

    public function translatePluralizedProvider()
    {
        $translator = new StaticTranslator();

        yield 'returns the original string 1' => [$translator, 'test', ['test', 1]];
        yield 'returns the original string 2' => [$translator, 'test', ['test', 1, 'default']];
        yield 'returns the original string 3' => [$translator, 'test', ['test', 1, 'default', [], 'default']];
        yield 'returns the original string 4' => [$translator, 'test', ['test', 1, 'default', [], 'de']];

        $translator = new StaticTranslator();
        $translator->setValuePluralized('apple', 'an apple', 1, 1);
        $translator->setValuePluralized('apple', 'a few apples', null, 5);
        $translator->setValuePluralized('apple', 'a dozen of apples', 12, 12);
        $translator->setValuePluralized('apple', 'many apples', 13);

        yield 'reads "an apple" for 1' => [$translator, 'an apple', ['apple', 1]];
        yield 'reads "a few apples" for 3' => [$translator, 'a few apples', ['apple', 3]];
        yield 'reads "a few apples" for 5' => [$translator, 'a few apples', ['apple', 5]];
        yield 'reads "a dozen of apples" for 12' => [$translator, 'a dozen of apples', ['apple', 12]];
        yield 'reads "many apples" for 13' => [$translator, 'many apples', ['apple', 13]];
        yield 'reads "many apples" for 100' => [$translator, 'many apples', ['apple', 100]];
    }

    /** @dataProvider translatePluralizedProvider */
    public function testTranslatePluralized(StaticTranslator $translator, string $expected, array $arguments): void
    {
        self::assertSame($expected, call_user_func_array([$translator, 'translatePluralized'], $arguments));
    }
}
