<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2018 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2018 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/translator/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/translator
 * @filesource
 */

declare(strict_types=1);

use DependencyInjection\Container\PimpleGate;

/**
 * @psalm-suppress UndefinedDocblockClass - We do not actively test against legacy Pimple. Use on your own risk.
 * @var PimpleGate $container
 */
$container->provideSymfonyService('translator', 'cca.translator.contao_translator');
