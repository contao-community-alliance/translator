<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2018 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2018 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator\Test;

use ContaoCommunityAlliance\Translator\TranslatorChain;

/**
 * Test case that test the StaticTranslator class.
 */
class TranslatorChainTest extends TestCase
{

    public function testChain()
    {
        $translator = new TranslatorChain();

        $this->markTestIncomplete();
    }
}
