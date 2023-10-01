<?php

/**
 * This file is part of contao-community-alliance/dependency-container.
 *
 * (c) 2013-2023 Contao Community Alliance <https://c-c-a.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/dependency-container
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2013-2023 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/dependency-container/blob/master/LICENSE LGPL-3.0
 * @link       https://github.com/contao-community-alliance/dependency-container
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator\DependencyInjection;

use ContaoCommunityAlliance\Translator\Contao\ContaoTranslatorFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages the bundle configuration
 */
class CcaTranslatorExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if (null === ($configuration = $this->getConfiguration($configs, $container))) {
            return;
        }

        $config = $this->processConfiguration($configuration, $configs);
        if (!$config['enable_symfony_bridge']) {
            $factory = $container->getDefinition(ContaoTranslatorFactory::class);
            $factory->replaceArgument(1, null);
        }
    }
}
