<?php
/**
 * The Contao Community Alliance translation library allows easy use of various translation string sources.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Translator
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator;

/**
 * This interface describes a translator.
 */
interface TranslatorInterface
{
	/**
	 * Translate a string in a specific domain.
	 *
	 * @param string $string     The translation string.
	 *
	 * @param string $domain     The translation domain.
	 *
	 * @param array  $parameters Parameters used in translation.
	 *
	 * @param string $locale     The translation locale.
	 *
	 * @return string
	 */
	public function translate($string, $domain = null, array $parameters = array(), $locale = null);

	/**
	 * Translate a pluralized string in a specific domain.
	 *
	 * @param string $string     The translation string.
	 *
	 * @param int    $number     The pluralization number.
	 *
	 * @param string $domain     The translation domain.
	 *
	 * @param array  $parameters Parameters used in translation.
	 *
	 * @param string $locale     The translation locale.
	 *
	 * @return string
	 */
	public function translatePluralized($string, $number, $domain = null, array $parameters = array(), $locale = null);
}
