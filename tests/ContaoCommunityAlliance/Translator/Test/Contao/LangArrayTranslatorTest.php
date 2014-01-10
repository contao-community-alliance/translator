<?php
/**
 * The Contao Community Alliance translation library allows easy use of various translation string sources.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Translator\Test
 * @subpackage Tests
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator\Test\Contao;

use ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator;
use ContaoCommunityAlliance\Translator\Test\TestCase;

/**
 * Test case that test the LangArrayTranslator class.
 */
class LangArrayTranslatorTest
	extends TestCase
{
	/**
	 * Mock a translator instance that will use the passed language array.
	 *
	 * @param array $langArray The language strings to use.
	 *
	 * @return LangArrayTranslator
	 */
	protected function mockTranslator(array $langArray)
	{
		$dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

		$translator = $this->getMock(
			'ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator',
			array('loadDomain'),
			array($dispatcher)
		);

		$translator
			->expects($this->any())
			->method('loadDomain')
			->will($this->returnValue(null));

		$GLOBALS['TL_LANG'] = $langArray;

		return $translator;
	}

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
		$this->assertSame('custom-domain-value', $translator->translate('test-custom-domain', 'custom', array('unused')));

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
				'1'   => 'an apple',
				'2:5' => 'a few apples',
				'12'  => 'a dozen of apples',
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
