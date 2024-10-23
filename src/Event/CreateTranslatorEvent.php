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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2013-2024 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator\Event;

use ContaoCommunityAlliance\Translator\TranslatorInterface;
use Symfony\Contracts\EventDispatcher\Event;

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
     *
     * @psalm-suppress MissingClassConstType
     */
    public const NAME = 'contao-community-alliance.translator.create-translator';

    /**
     * The translator being initialized.
     *
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

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
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
}
