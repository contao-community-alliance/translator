<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2018 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2013-2019 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator;

use ContaoCommunityAlliance\Translator\DependencyInjection\Compiler\RegisterBackportTranslatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This is the bundle for the legacy event dispatcher.
 */
class CcaTranslatorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterBackportTranslatorPass());
    }
}
