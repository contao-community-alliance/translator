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
 * @copyright  2013-2018 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator;

use function count;
use function explode;
use function is_array;
use function vsprintf;

/**
 * Abstract base implementation of a translator.
 *
 *
 * @psalm-type TChoiceType=object{range: object{from: int, to: int}, string: string}
 * @psalm-type TChoiceList=list<TChoiceType>
 */
abstract class AbstractTranslator implements TranslatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function translate($string, $domain = null, array $parameters = [], $locale = null)
    {
        $newString = $this->getValue($string, $domain, $locale);
        assert(is_string($newString), 'Expected ' . var_export($newString, true) . ' to be a string.');

        if ($newString == $string) {
            return $string;
        }

        if (count($parameters)) {
            $newString = vsprintf($newString, $parameters);
        }

        return $newString;
    }

    /**
     * Retrieve the value.
     *
     * @param string      $string The string to translate.
     * @param string|null $domain The domain to use.
     * @param string|null $locale The locale (otherwise the current default locale will get used).
     *
     * @return array<string, string>|string
     */
    abstract protected function getValue($string, $domain, $locale);

    /**
     * {@inheritdoc}
     */
    public function translatePluralized($string, $number, $domain = null, array $parameters = [], $locale = null)
    {
        $choices = $this->getValue($string, $domain, $locale);

        if (!is_array($choices)) {
            return $string;
        }

        if (null === ($newString = $choices[(string) $number] ?? null)) {
            $array = $this->buildChoiceLookupList($choices);

            $count = count($array);
            for ($i = 0; $i < $count; $i++) {
                $choice = $this->fetchChoice($array, $i, $count);

                if ($number >= $choice->range->from && $number <= $choice->range->to) {
                    $newString = $choice->string;
                    break;
                }
            }
        }

        if (!isset($newString)) {
            return $string;
        }

        if (count($parameters)) {
            $newString = vsprintf($newString, $parameters);
        }

        return $newString;
    }

    /**
     * Build a choice lookup list from the passed language choice array.
     *
     * The input array is something like:
     * [
     *   '1'   => 'an apple',
     *   '2:5' => 'a few apples',
     *   '12'  => 'a dozen of apples',
     *   '13:' => 'a pile of apples'
     * ]
     *
     * @param array<string, string> $choices The language strings.
     *
     * @return TChoiceList
     */
    protected function buildChoiceLookupList($choices)
    {
        $array = [];

        foreach ($choices as $range => $choice) {
            /** @psalm-suppress RedundantCastGivenDocblockType - There is no way to enforce string array key :/ */
            $range = explode(':', (string) $range);

            $array[] = (object) [
                'range' => (object) [
                    'from' => (int) $range[0],
                    'to' => (int) ($range[1] ?? 0),
                ],
                'string' => $choice
            ];
        }

        return $array;
    }

    /**
     * Extract a single choice from the array of choices and sanitize its values.
     *
     * @param TChoiceList $choices The choice array.
     * @param int   $index   The index to extract.
     * @param int   $count   Amount of all choices in the array (passed to prevent calling count() multiple times).
     *
     * @return TChoiceType
     */
    protected function fetchChoice($choices, $index, $count)
    {
        $choice = $choices[$index];

        // Set from number, if not set (notation ":X").
        if (!$choice->range->from) {
            if ($index > 0) {
                $choice->range->from = ($choices[($index - 1)]->range->to + 1);
            } else {
                $choice->range->from = (-PHP_INT_MAX);
            }
        }
        // Set to number, if not set (notation "X" or "X:").
        if (!$choice->range->to) {
            if ($index < ($count - 1)) {
                $choice->range->to = ($choices[($index + 1)]->range->from - 1);
            } else {
                $choice->range->to = PHP_INT_MAX;
            }
        }

        return $choice;
    }
}
