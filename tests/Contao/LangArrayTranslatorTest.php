<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2018 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2018 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator\Test\Contao;

use ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator;
use ContaoCommunityAlliance\Translator\Test\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Test case that test the LangArrayTranslator class.
 */
class LangArrayTranslatorTest extends TestCase
{
    /**
     * Test that the translator always returns the original string when no translation value can be found.
     *
     * @return void
     */
    public function testReturnUntranslated()
    {
        $translator = $this->mockTranslator(array());

        $this->assertSame('test', $translator->translate('test'));
        $this->assertSame('test', $translator->translate('test', 'default'));
        $this->assertSame('test', $translator->translate('test', 'default', array(), 'default'));
        $this->assertSame('test', $translator->translate('test', 'default', array(), 'de'));

        $this->assertSame('test', $translator->translatePluralized('test', 1));
        $this->assertSame('test', $translator->translatePluralized('test', 1, 'default'));
        $this->assertSame('test', $translator->translatePluralized('test', 1, 'default', array(), 'default'));
        $this->assertSame('test', $translator->translatePluralized('test', 1, 'default', array(), 'de'));
    }

    /**
     * Mock a translator instance that will use the passed language array.
     *
     * @param array $langArray The language strings to use.
     *
     * @return LangArrayTranslator|\PHPUnit_Framework_MockObject_MockObject
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function mockTranslator(array $langArray)
    {
        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMockForAbstractClass();
        $translator = $this
            ->getMockBuilder(LangArrayTranslator::class)
            ->setMethods(['loadDomain'])
            ->setConstructorArgs([$dispatcher])
            ->getMock();

        $translator
            ->expects($this->any())
            ->method('loadDomain')
            ->will($this->returnValue(null));

        $GLOBALS['TL_LANG'] = $langArray;

        return $translator;
    }

    /**
     * Test the default domain handling.
     *
     * @return void
     */
    public function testDefaultDomain()
    {
        $translator = $this->mockTranslator(array(
            'test-default-domain' => 'default-domain-value'
        ));

        $this->assertSame('default-domain-value', $translator->translate('test-default-domain'));
        $this->assertSame('default-domain-value', $translator->translate('test-default-domain', null, array('unused')));

        $translator = $this->mockTranslator(array(
            'test-default-domain' => 'default-domain-value-en'
        ));
        $this->assertSame(
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
        $translator = $this->mockTranslator(array(
            'test-custom-domain' => 'custom-domain-value'
        ));

        $this->assertSame('custom-domain-value', $translator->translate('test-custom-domain', 'custom'));
        $this->assertSame(
            'custom-domain-value',
            $translator->translate('test-custom-domain', 'custom', array('unused'))
        );

        $translator = $this->mockTranslator(array(
            'test-custom-domain' => 'custom-domain-value-en'
        ));

        $this->assertSame(
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
        $translator = $this->mockTranslator(array(
            'apple' => array(
                '1' => 'an apple',
                '2:5' => 'a few apples',
                '12' => 'a dozen of apples',
                '13:' => 'many apples'
            )
        ));

        $this->assertSame('an apple', $translator->translatePluralized('apple', 1));
        $this->assertSame('a few apples', $translator->translatePluralized('apple', 3));
        $this->assertSame('a few apples', $translator->translatePluralized('apple', 5));
        $this->assertSame('a dozen of apples', $translator->translatePluralized('apple', 12));
        $this->assertSame('many apples', $translator->translatePluralized('apple', 13));
        $this->assertSame('many apples', $translator->translatePluralized('apple', 100));
    }
}
