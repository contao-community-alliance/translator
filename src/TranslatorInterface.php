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
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2013-2024 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator;

/**
 * This interface describes a translator.
 *
 * @psalm-type TParameterArray=array<string, string>|list<float|int|string>
 */
interface TranslatorInterface
{
    /**
     * Translate a string in a specific domain.
     *
     * @param string          $string     The translation string.
     * @param string|null     $domain     The translation domain.
     * @param TParameterArray $parameters Parameters used in translation.
     * @param string|null     $locale     The translation locale.
     *
     * @return string
     */
    public function translate($string, $domain = null, array $parameters = [], $locale = null);

    /**
     * Translate a pluralized string in a specific domain.
     *
     * @param string          $string     The translation string.
     * @param int             $number     The pluralization number.
     * @param string|null     $domain     The translation domain.
     * @param TParameterArray $parameters Parameters used in translation.
     * @param string|null     $locale     The translation locale.
     *
     * @return string
     */
    public function translatePluralized($string, $number, $domain = null, array $parameters = [], $locale = null);
}
