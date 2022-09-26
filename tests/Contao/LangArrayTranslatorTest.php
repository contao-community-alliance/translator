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

namespace ContaoCommunityAlliance\Translator\Test\Contao;

use ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator;
use ContaoCommunityAlliance\Translator\Test\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @covers \ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator
 */
class LangArrayTranslatorTest extends TestCase
{
    public function returnUntranslatedProvider(): iterable
    {
        yield ['test', ['test']];
        yield ['test', ['test']];
        yield ['test', ['test', 'default']];
        yield ['test', ['test', 'default', [], 'default']];
        yield ['test', ['test', 'default', [], 'de']];
    }

    /**
     * Test that the translator always returns the original string when no translation value can be found.
     * @dataProvider returnUntranslatedProvider
     */
    public function testReturnUntranslated(string $expected, array $arguments): void
    {
        $translator = $this->mockTranslator([]);

        self::assertSame($expected, call_user_func_array([$translator, 'translate'], $arguments));
    }

    public function returnUntranslatedPluralizedProvider(): iterable
    {
        yield ['test', ['test', 1]];
        yield ['test', ['test', 1, 'default']];
        yield ['test', ['test', 1, 'default', [], 'default']];
        yield ['test', ['test', 1, 'default', [], 'de']];
    }

    /**
     * Test that the translator always returns the original string when no translation value can be found.
     * @dataProvider returnUntranslatedPluralizedProvider
     */
    public function testReturnUntranslatedPluralized(string $expected, array $arguments): void
    {
        $translator = $this->mockTranslator([]);

        self::assertSame($expected, call_user_func_array([$translator, 'translatePluralized'], $arguments));
    }

    public function defaultDomainProvider(): iterable
    {
        $translations = ['test-default-domain' => 'default-domain-value'];
        yield ['default-domain-value', $translations, ['test-default-domain']];
        yield ['default-domain-value', $translations, ['test-default-domain', null, ['unused']]];
        $translations = ['test-default-domain' => 'default-domain-value-en'];
        yield ['default-domain-value-en', $translations, ['test-default-domain', null, ['unused'], 'en']];
    }

    /**
     * Test the default domain handling.
     * @dataProvider defaultDomainProvider
     */
    public function testDefaultDomain(string $expected, array $translations, array $arguments): void
    {
        $translator = $this->mockTranslator($translations);

        self::assertSame($expected, call_user_func_array([$translator, 'translate'], $arguments));
    }

    public function customDomainProvider(): iterable
    {
        $translations = ['test-custom-domain' => 'custom-domain-value'];
        yield ['custom-domain-value', $translations, ['test-custom-domain', 'custom']];
        yield ['custom-domain-value', $translations, ['test-custom-domain', 'custom', ['unused']]];
        $translations = ['test-custom-domain' => 'custom-domain-value-en'];
        yield ['custom-domain-value-en', $translations, ['test-custom-domain', 'custom', ['unused'], 'en']];
    }

    /**
     * Test the custom domain handling.
     * @dataProvider customDomainProvider
     */
    public function testCustomDomain(string $expected, array $translations, array $arguments): void
    {
        $translator = $this->mockTranslator($translations);
        self::assertSame($expected, call_user_func_array([$translator, 'translate'], $arguments));
    }

    public function pluralizationProvider(): iterable
    {
        yield ['an apple', ['apple', 1]];
        yield ['a few apples', ['apple', 3]];
        yield ['a few apples', ['apple', 5]];
        yield ['a dozen of apples', ['apple', 12]];
        yield ['many apples', ['apple', 13]];
        yield ['many apples', ['apple', 100]];
    }

    /**
     * Test pluralization.
     * @dataProvider pluralizationProvider
     */
    public function testPluralization(string $expected, array $arguments): void
    {
        $translator = $this->mockTranslator([
            'apple' => [
                '1' => 'an apple',
                '2:5' => 'a few apples',
                '12' => 'a dozen of apples',
                '13:' => 'many apples'
            ]
        ]);

        self::assertSame($expected, call_user_func_array([$translator, 'translatePluralized'], $arguments));
    }

    /**
     * Mock a translator instance that will use the passed language array.
     *
     * @param array $langArray The language strings to use.
     *
     * @return LangArrayTranslator|MockObject
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    private function mockTranslator(array $langArray): LangArrayTranslator
    {
        $dispatcher = $this->getMockForAbstractClass(EventDispatcherInterface::class);
        $translator = $this
            ->getMockBuilder(LangArrayTranslator::class)
            ->onlyMethods(['loadDomain'])
            ->setConstructorArgs([$dispatcher])
            ->getMock();

        $translator
            ->expects(self::any())
            ->method('loadDomain')
            ->willReturn(null);

        $GLOBALS['TL_LANG'] = $langArray;

        return $translator;
    }
}
