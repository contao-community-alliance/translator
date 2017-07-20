<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2017 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2017 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/translator/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/translator
 * @filesource
 */

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

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function createService()
    {
        $translator = new TranslatorChain();

        $translator->add(new LangArrayTranslator($this->dispatcher));

        $initializer = new TranslatorInitializer($this->dispatcher);

        return $initializer->initialize($translator);
    }
}
