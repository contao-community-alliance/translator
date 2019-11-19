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

namespace ContaoCommunityAlliance\Translator;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\System;
use Symfony\Component\Translation\TranslatorInterface as SymfonyTranslatorInterface;

/**
 * This class is a back-ported version of the translator contained in Contao 4.5 to ease the migration phase.
 *
 * This class is courtesy of Contao and has been adapted to work in PHP 5.6 and Contao 4.4.
 * Original author is Leo Feyer.
 */
class BackportedTranslator implements SymfonyTranslatorInterface
{
    /**
     * The symfony translator to use as fallback.
     *
     * @var SymfonyTranslatorInterface
     */
    private $translator;

    /**
     * The Contao framework.
     *
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * Constructor.
     *
     * @param SymfonyTranslatorInterface $translator The translator to decorate.
     * @param ContaoFrameworkInterface   $framework  The framework to use.
     */
    public function __construct(SymfonyTranslatorInterface $translator, ContaoFrameworkInterface $framework)
    {
        $this->translator = $translator;
        $this->framework  = $framework;
    }

    /**
     * {@inheritdoc}
     *
     * Gets the translation from Contao’s $GLOBALS['TL_LANG'] array if the message domain starts with
     * "contao_". The locale parameter is ignored in this case.
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        // Forward to the default translator
        if (null === $domain || 0 !== strncmp($domain, 'contao_', 7)) {
            return $this->translator->trans($id, $parameters, $domain, $locale);
        }
        $domain = substr($domain, 7);
        $this->framework->initialize();
        $this->loadLanguageFile($domain);
        $translated = $this->getFromGlobals($id);
        if (null === $translated) {
            return $id;
        }
        if (!empty($parameters)) {
            $translated = vsprintf($translated, $parameters);
        }
        return $translated;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        return $this->translator->setLocale($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->translator->getLocale();
    }

    /**
     * Returns the labels from the $GLOBALS['TL_LANG'] array.
     *
     * @param string $id Message id, e.g. "MSC.view".
     *
     * @return string|null
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    private function getFromGlobals($id)
    {
        // Split the ID into chunks allowing escaped dots (\.) and backslashes (\\)
        preg_match_all('/(?:\\\\[\\\\.]|[^.])++/', $id, $matches);
        $parts = preg_replace('/\\\\([\\\\.])/', '$1', $matches[0]);
        $item  = &$GLOBALS['TL_LANG'];
        foreach ($parts as $part) {
            if (!isset($item[$part])) {
                return null;
            }
            $item = &$item[$part];
        }

        return $item;
    }

    /**
     * Loads a Contao framework language file.
     *
     * @param string $name The name of the language file to load.
     *
     * @return void
     */
    private function loadLanguageFile($name)
    {
        /** @var System $system */
        $system = $this->framework->getAdapter(System::class);
        $system->loadLanguageFile($name);
    }
}
