<?php
/**
 * The Contao Community Alliance translation library allows easy use of various translation string sources.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Translator\Test
 * @subpackage Tests
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
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
