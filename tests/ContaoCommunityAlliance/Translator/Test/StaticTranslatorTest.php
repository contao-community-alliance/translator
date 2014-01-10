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

namespace ContaoCommunityAlliance\Translator\Test;

use ContaoCommunityAlliance\Translator\StaticTranslator;

/**
 * Test case that test the StaticTranslator class.
 */
class StaticTranslatorTest
	extends TestCase
{
	/**
	 * Test that the translator returns the own instance for the methods where it should.
	 *
	 * @return void
	 */
	public function testReturnsSelf()
	{
		$translator = new StaticTranslator();

		$this->assertSame($translator, $translator->setValue('', ''));
		$this->assertSame($translator, $translator->setValuePluralized('', '', 1, 1));
	}

	/**
	 * Test that the translator always returns the original string when no translation value can be found.
	 *
	 * @return void
	 */
	public function testReturnUntranslated()
	{
		$translator = new StaticTranslator();
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
		$translator = new StaticTranslator();

		$translator->setValue('test-default-domain', 'default-domain-value');
		$translator->setValue('test-default-domain', 'default-domain-value-en', null, 'en');

		$this->assertSame('default-domain-value', $translator->translate('test-default-domain'));
		$this->assertSame('default-domain-value', $translator->translate('test-default-domain', null, array('unused')));
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
		$translator = new StaticTranslator();

		$translator->setValue('test-custom-domain', 'custom-domain-value', 'custom');
		$translator->setValue('test-custom-domain', 'custom-domain-value-en', 'custom', 'en');

		$this->assertSame('custom-domain-value', $translator->translate('test-custom-domain', 'custom'));
		$this->assertSame('custom-domain-value', $translator->translate('test-custom-domain', 'custom', array('unused')));
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
		$translator = new StaticTranslator();

		$translator->setValuePluralized('apple', 'an apple', 1, 1);
		$translator->setValuePluralized('apple', 'a few apples', null, 5);
		$translator->setValuePluralized('apple', 'a dozen of apples', 12, 12);
		$translator->setValuePluralized('apple', 'many apples', 13);

		$this->assertSame('an apple', $translator->translatePluralized('apple', 1));
		$this->assertSame('a few apples', $translator->translatePluralized('apple', 3));
		$this->assertSame('a few apples', $translator->translatePluralized('apple', 5));
		$this->assertSame('a dozen of apples', $translator->translatePluralized('apple', 12));
		$this->assertSame('many apples', $translator->translatePluralized('apple', 13));
		$this->assertSame('many apples', $translator->translatePluralized('apple', 100));
	}
}
