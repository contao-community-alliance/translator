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

namespace ContaoCommunityAlliance\Translator\Event;

use ContaoCommunityAlliance\Translator\TranslatorInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CreateTranslatorEvent is emitted when the translator is created.
 *
 * @package ContaoCommunityAlliance\Translator\Event
 */
class CreateTranslatorEvent extends Event
{
    /**
     * The event name.
     *
     * @var string
     */
    const NAME = 'contao-community-alliance.translator.create-translator';

    /**
     * The translator being initialized.
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Construct.
     *
     * @param TranslatorInterface $translator The translator.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Get the translator.
     *
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }
}
