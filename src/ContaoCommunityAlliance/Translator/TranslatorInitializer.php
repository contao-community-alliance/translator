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
