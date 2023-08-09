<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2018 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2018 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/translator/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/translator
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator\Contao;

use ContaoCommunityAlliance\Translator\SymfonyTranslatorBridge;
use ContaoCommunityAlliance\Translator\TranslatorChain;
use ContaoCommunityAlliance\Translator\TranslatorInitializer;
use ContaoCommunityAlliance\Translator\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface as SymfonyTranslator;

/**
 * This class create the contao translator service.
 */
class ContaoTranslatorFactory
{
    /**
     * Create a new instance.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher to use.
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        private readonly ?SymfonyTranslator $translator = null
    ) {
    }

    /**
     * Create the translator service.
     *
     * @return TranslatorInterface
     */
    public function createService(): TranslatorInterface
    {
        $translator = new TranslatorChain();

        if (null !== $this->translator) {
            $translator->add(new SymfonyTranslatorBridge($this->translator));
        }
        $translator->add(new LangArrayTranslator($this->dispatcher));
        $initializer = new TranslatorInitializer($this->dispatcher);
        $initializer->initialize($translator);

        return $translator;
    }
}
