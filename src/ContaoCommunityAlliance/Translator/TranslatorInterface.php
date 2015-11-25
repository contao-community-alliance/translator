<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2015 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  2014-2015 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator;

/**
 * This interface describes a translator.
 */
interface TranslatorInterface
{
    /**
     * Translate a string in a specific domain.
     *
     * @param string $string     The translation string.
     *
     * @param string $domain     The translation domain.
     *
     * @param array  $parameters Parameters used in translation.
     *
     * @param string $locale     The translation locale.
     *
     * @return string
     */
    public function translate($string, $domain = null, array $parameters = array(), $locale = null);

    /**
     * Translate a pluralized string in a specific domain.
     *
     * @param string $string     The translation string.
     *
     * @param int    $number     The pluralization number.
     *
     * @param string $domain     The translation domain.
     *
     * @param array  $parameters Parameters used in translation.
     *
     * @param string $locale     The translation locale.
     *
     * @return string
     */
    public function translatePluralized($string, $number, $domain = null, array $parameters = array(), $locale = null);
}
