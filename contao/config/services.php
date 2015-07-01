<?php
/**
 * The Contao Community Alliance translation library allows easy use of various translation string sources.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Translator
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

/** @var Pimple $container */
global $container;

$container['translator.factory.default'] = $container->protect(
    function ($container) {
        $translator = new \ContaoCommunityAlliance\Translator\TranslatorChain();
        $translator->add(
            new \ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator($container['event-dispatcher'])
        );
        return $translator;
    }
);

if (!isset($container['translator.factory'])) {
    $container['translator.factory'] = $container->raw('translator.factory.default');
}

$container['translator'] = function ($container) {
    $translator  = $container['translator.factory']($container);
    $initializer = new \ContaoCommunityAlliance\Translator\TranslatorInitializer($container['event-dispatcher']);

    return $initializer->initialize($translator);
};
