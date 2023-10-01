<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2023 Contao Community Alliance <https://c-c-a.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2013-2023 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @link       https://github.com/contao-community-alliance/translator
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Adds the Contao configuration structure.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     *
     * @psalm-suppress UndefinedMethod
     * @psalm-suppress MixedMethodCall
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cca_translator');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->booleanNode('enable_symfony_bridge')
                    ->defaultValue(true)
                ->end()
            ->end();

        return $treeBuilder;
    }
}
