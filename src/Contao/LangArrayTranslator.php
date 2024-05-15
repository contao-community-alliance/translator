<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2024 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2013-2024 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

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
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * Create a new instance.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher to use.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Retrieve the value.
     *
     * @param mixed       $string The string to translate.
     * @param string|null $domain The domain to use.
     * @param string|null $locale The locale (otherwise the current default locale will get used).
     *
     * @return array<string, string>|string
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     *
     * @psalm-suppress MixedInferredReturnType
     */
    protected function getValue($string, $domain, $locale)
    {
        if (false === \is_string($string)) {
            // @codingStandardsIgnoreStart
            @\trigger_error('Only string allowed to translate!', E_USER_DEPRECATED);
            // @codingStandardsIgnoreEnd
            return '';
        }

        if (null === $domain) {
            $domain = 'default';
        }

        if (null === $locale) {
            $locale = 'en';
        }

        $this->loadDomain($domain, $locale);

        /** @var array $globalLang */
        $globalLang = $GLOBALS['TL_LANG'];
        // We have to treat 'languages', 'default', 'modules' etc. domains differently.
        if (substr($domain, 0, 3) !== 'tl_') {
            $lang = $globalLang;
        } else {
            if (!is_array($globalLang[$domain] ?? null)) {
                return $string;
            }
            /** @psalm-suppress MixedAssignment */
            $lang = $globalLang[$domain];
        }

        $chunks = explode('.', $string);

        while (($chunk = array_shift($chunks)) !== null) {
            if (!is_array($lang) || !array_key_exists($chunk, $lang)) {
                return $string;
            }
            /** @psalm-suppress MixedAssignment */
            $lang = $lang[$chunk];
        }

        /** @psalm-suppress MixedReturnStatement */
        return $lang;
    }

    /**
     * Load the language strings for the given domain in the passed locale.
     *
     * @param string $domain The domain to load.
     * @param string $locale The locale to use.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    protected function loadDomain($domain, $locale)
    {
        $event = new LoadLanguageFileEvent($domain, $locale);

        $this->eventDispatcher->dispatch($event, ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE);
    }
}
