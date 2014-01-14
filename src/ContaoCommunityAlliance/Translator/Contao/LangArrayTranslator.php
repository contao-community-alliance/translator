<?php
/**
 * The Contao Community Alliance translation library allows easy use of various translation string sources.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Translator\Contao
 * @subpackage Contao
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator\Contao;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Translator\AbstractTranslator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class LangArrayTranslator.
 *
 * Contao language array translator implementation.
 */
class LangArrayTranslator extends AbstractTranslator
{
	/**
	 * The event dispatcher to use.
	 *
	 * @var EventDispatcherInterface
	 */
	protected $eventDispatcher;

	/**
	 * Create a new instance.
	 *
	 * @param EventDispatcherInterface $eventDispatcher The event dispatcher to use.
	 */
	public function __construct($eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * Load the language strings for the given domain in the passed locale.
	 *
	 * @param string $domain The domain to load.
	 *
	 * @param string $locale The locale to use.
	 *
	 * @return void
	 *
	 * @codeCoverageIgnore
	 */
	protected function loadDomain($domain, $locale)
	{
		$event = new LoadLanguageFileEvent($domain, $locale);

		$this->eventDispatcher->dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $event);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getValue($string, $domain, $locale)
	{
		if (!$domain)
		{
			$domain = 'default';
		}

		$this->loadDomain($domain, $locale);

		// We have to treat 'languages', 'default', 'modules' etc. domains differently.
		if (!(isset($GLOBALS['TL_LANG'][$domain]) && is_array($GLOBALS['TL_LANG'][$domain]))
			&& (substr($domain, 0, 2) != 'tl_'))
		{
			$lang = $GLOBALS['TL_LANG'];
		}
		else
		{
			if (!is_array($GLOBALS['TL_LANG'][$domain]))
			{
				return $string;
			}
			$lang = $GLOBALS['TL_LANG'][$domain];
		}

		$chunks = explode('.', $string);

		while (($chunk = array_shift($chunks)) !== null)
		{
			if (!array_key_exists($chunk, $lang))
			{
				return $string;
			}

			$lang = $lang[$chunk];
		}

		return $lang;
	}
}
