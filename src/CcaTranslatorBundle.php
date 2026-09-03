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

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This is the bundle for the legacy event dispatcher.
 *
 * @psalm-suppress DeprecatedInterface Bundle implements the deprecated BundleInterface under
 *     Symfony 8, but Symfony\Component\DependencyInjection\Kernel\AbstractBundle is not a drop-in
 *     replacement here: its getContainerExtension() does not do the classic reflection-based
 *     lookup of a "<Namespace>\DependencyInjection\<Name>Extension" class that
 *     CcaTranslatorExtension relies on, so swapping the base class silently stops that extension
 *     (and all its services) from ever loading.
 */
class CcaTranslatorBundle extends Bundle
{
}
