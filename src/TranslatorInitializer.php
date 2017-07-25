<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2017 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2017 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator;

use ContaoCommunityAlliance\Translator\Event\CreateTranslatorEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TranslatorInitializer emits an event to initialize a translator.
 *
 * @package ContaoCommunityAlliance\Translator
 */
class TranslatorInitializer
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Construct.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Initialize the translator.
     *
     * @param TranslatorInterface $translator The translator being initiliazed.
     *
     * @return TranslatorInterface
     */
    public function initialize(TranslatorInterface $translator)
    {
        $event = new CreateTranslatorEvent($translator);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        return $translator;
    }
}
