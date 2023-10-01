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
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2014-2018 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator;

use InvalidArgumentException;

/**
 * Static in memory translator implementation.
 *
 * This translator holds all values in memory.
 *
 * It is to be populated via the public setValue method.
 *
 * @psalm-type
 */
class StaticTranslator extends AbstractTranslator
{
    /**
     * The translation values.
     *
     * @var array<string, array<string, array<string, string|array<string, string>>>>
     */
    protected array $values = [];

    /**
     * Compare function for the pluralization array to be used as sorting callback.
     *
     * See documentation of usort in the php manual for details.
     *
     * @param string $a The first value.
     * @param string $b The second value.
     *
     * @return int
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected static function sortPluralizedCompare($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        /** @psalm-suppress RedundantCastGivenDocblockType - There is no way to enforce string array key :/ */
        $rangeA = explode(':', (string) $a);
        /** @psalm-suppress RedundantCastGivenDocblockType - There is no way to enforce string array key :/ */
        $rangeB = explode(':', (string) $b);

        $rangeA0 = !empty($rangeA[0] ?? null);
        $rangeB0 = !empty($rangeB[0] ?? null);
        // Both range starts provided.
        if ($rangeA0 && $rangeB0) {
            return strcmp($rangeA[0], $rangeB[0]);
        }

        // Only second range has a starting point.
        if ($rangeB0) {
            return -1;
        }

        // Only first range has a starting point.
        if ($rangeA0) {
            return 1;
        }

        $rangeA1 = !empty($rangeA[1] ?? null);
        $rangeB1 = !empty($rangeB[1] ?? null);
        // Both are an open start range.
        if ($rangeA1 && $rangeB1) {
            /** @psalm-suppress PossiblyUndefinedArrayOffset The array keys must exist due to above checks */
            return strcmp($rangeA[1], $rangeB[1]);
        }

        // Only second range is open => First is first.
        if ($rangeB1) {
            return 1;
        }

        // Only first range is open => Second is first.
        if ($rangeA1) {
            return -1;
        }

        // Just here to make the IDEs happy - is already handled above as early exit point.
        return 0;
    }

    /**
     * Set a pluralized value in the translator.
     *
     * @param string      $string The translation string.
     * @param string      $value  The translation value.
     * @param int|null    $min    The minimum value of the range (optional - defaults to null).
     * @param int|null    $max    The maximum value of the range (optional - defaults to null).
     * @param string|null $domain The domain (optional - defaults to null).
     * @param string|null $locale The locale  (optional - defaults to null).
     *
     * @return StaticTranslator
     */
    public function setValuePluralized($string, $value, $min = null, $max = null, $domain = null, $locale = null)
    {
        if (null === $domain) {
            $domain = 'default';
        }

        if (null === $locale) {
            $locale = 'default';
        }

        $this->ensureArrayStructure($locale, $domain);

        if (!is_array($lang = $this->values[$locale][$domain][$string] ?? null)) {
            // NOTE: we kill any value previously stored as there is no way to tell which value to use.
            $lang = [];
        }

        $lang[$this->determineKey($min, $max)] = $value;
        $lang = $this->sortPluralized($lang);

        $this->setValue($string, $lang, $domain, $locale);

        return $this;
    }

    /**
     * Determine the correct pluralization key.
     *
     * @param int|null $min The minimum value.
     *
     * @param int|null $max The maximum value.
     *
     * @return string
     *
     * @throws InvalidArgumentException When both, min and max, are null.
     */
    protected function determineKey($min, $max)
    {
        $minGiven = ($min !== null);
        $maxGiven = ($max !== null);

        if (!$minGiven && !$maxGiven) {
            throw new InvalidArgumentException('You must specify a valid value for min, max or both.');
        }

        if (!$maxGiven) {
            // Open end range.
            return (string) $min . ':';
        } elseif (!$minGiven) {
            // Open start range.
            return ':' . (string) $max;
        }

        if ($min === $max) {
            // Exact number.
            return (string) $min;
        }

        // Full defined range.
        return (string) $min . ':' . (string) $max;
    }

    /**
     * Sort the given array for pluralization.
     *
     * @param array<string, string> $lang The language array to be sorted.
     *
     * @return array<string, string>
     */
    protected function sortPluralized($lang)
    {
        uksort($lang, [__CLASS__, 'sortPluralizedCompare']);

        return $lang;
    }

    /**
     * Set a translation value in the translator.
     *
     * @param string                       $string The string to translate.
     * @param string|array<string, string> $value The value to store.
     * @param string                       $domain The domain to use.
     * @param string                       $locale The locale (otherwise the current default locale will get used).
     *
     * @return StaticTranslator
     */
    public function setValue($string, $value, $domain = null, $locale = null)
    {
        if (!$domain) {
            $domain = 'default';
        }

        if (!$locale) {
            $locale = 'default';
        }

        $this->ensureArrayStructure($locale, $domain);

        $this->values[$locale][$domain][$string] = $value;

        return $this;
    }

    protected function getValue($string, $domain, $locale)
    {
        if (null === $domain) {
            $domain = 'default';
        }

        if (null === $locale) {
            $locale = 'default';
        }

        if (!isset($this->values[$locale][$domain][$string])) {
            return $string;
        }

        return $this->values[$locale][$domain][$string];
    }

    private function ensureArrayStructure(string $locale, string $domain): void
    {
        if (!(isset($this->values[$locale]) && is_array($this->values[$locale]))) {
            $this->values[$locale] = [];
        }

        if (!(isset($this->values[$locale][$domain]) && is_array($this->values[$locale][$domain]))) {
            $this->values[$locale][$domain] = [];
        }
    }
}
