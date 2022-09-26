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

use ContaoCommunityAlliance\Translator\TranslatorChain;
use ContaoCommunityAlliance\Translator\TranslatorInitializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class create the contao translator service.
 */
class ContaoTranslatorFactory
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Create a new instance.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher to use.
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Create the translator service.
     *
     * @return \ContaoCommunityAlliance\Translator\TranslatorInterface
     */
    public function createService()
    {
        $translator = new TranslatorChain();

        $translator->add(new LangArrayTranslator($this->dispatcher));

        $initializer = new TranslatorInitializer($this->dispatcher);

        return $initializer->initialize($translator);
    }
}
