<?php

/**
 * The Contao Community Alliance translation library allows easy use of various translation string sources.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Translator
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

use ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator;
use ContaoCommunityAlliance\Translator\TranslatorChain;
use ContaoCommunityAlliance\Translator\TranslatorInitializer;
use DependencyInjection\Container\PimpleGate;

/** @var PimpleGate $container */

// Contao 4 code.
if ($container->isContao4()) {
    $container->provideSymfonyService('translator');
    return;
}

$container['translator'] = function ($container) {
    $translator = new TranslatorChain();

    $translator->add(
        new LangArrayTranslator($container['event-dispatcher'])
    );

    $initializer = new TranslatorInitializer($container['event-dispatcher']);

    return $initializer->initialize($translator);
};
