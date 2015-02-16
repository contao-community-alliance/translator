<?php
/**
 * The Contao Community Alliance translation library allows easy use of various translation string sources.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Translator
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator;

use \InvalidArgumentException;

/**
 * Static in memory translator implementation.
 *
 * This translator holds all values in memory.
 *
 * It is to be populated via the public setValue method.
 */
class StaticTranslator extends AbstractTranslator
{
    /**
     * The translation values.
     *
     * @var mixed[]
     */
    protected $values;

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
     */
    protected static function sortPluralizedCompare($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        $rangeA = explode(':', $a);
        $rangeB = explode(':', $b);

        // Both range starts provided.
        if (isset($rangeA[0]) && isset($rangeB[0])) {
            return strcmp($rangeA[0], $rangeB[0]);
        }

        // Only second range has a starting point.
        if (!isset($rangeA[0]) && isset($rangeB[0])) {
            return -1;
        }

        // Only first range has a starting point.
        if (isset($rangeA[0]) && !isset($rangeB[0])) {
            return 1;
        }

        // Both are an open start range.
        if (isset($rangeA[1]) && isset($rangeB[1])) {
            return strcmp($rangeA[1], $rangeB[1]);
        }

        // Only second range is open => First is first.
        if (!isset($rangeA[1]) && isset($rangeB[1])) {
            return 1;
        }

        // Only first range is open => Second is first.
        if (isset($rangeA[1]) && !isset($rangeB[1])) {
            return -1;
        }

        // Just here to make the IDEs happy - is already handled above as early exit point.
        return 0;
    }

    /**
     * Set a pluralized value in the translator.
     *
     * @param string   $string The translation string.
     *
     * @param string   $value  The translation value.
     *
     * @param int|null $min    The minimum value of the range (optional - defaults to null).
     *
     * @param int|null $max    The maximum value of the range (optional - defaults to null).
     *
     * @param string   $domain The domain (optional - defaults to null).
     *
     * @param string   $locale The locale  (optional - defaults to null).
     *
     * @return StaticTranslator
     */
    public function setValuePluralized($string, $value, $min = null, $max = null, $domain = null, $locale = null)
    {
        if (!$domain) {
            $domain = 'default';
        }

        if (!$locale) {
            $locale = 'default';
        }

        if (!(isset($this->values[$locale]) && is_array($this->values[$locale]))) {
            $this->values[$locale] = array();
        }

        if (!(isset($this->values[$locale][$domain]) && is_array($this->values[$locale][$domain]))) {
            $this->values[$locale][$domain] = array();
        }

        if (isset($this->values[$locale][$domain][$string]) && is_array($this->values[$locale][$domain][$string])) {
            $lang = $this->values[$locale][$domain][$string];
        } else {
            // NOTE: we kill any value previously stored as there is no way to tell which value to use.
            $lang = array();
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
        $maxGiven = ($min !== null);

        if (!$minGiven && !$maxGiven) {
            throw new InvalidArgumentException('You must specify a valid value for min, max or both.');
        }

        if ($minGiven && !$maxGiven) {
            // Open end range.
            return $min . ':';
        } elseif (!$minGiven && $maxGiven) {
            // Open start range.
            return ':' . $max;
        }

        if ($min === $max) {
            // Exact number.
            return $min;
        }

        // Full defined range.
        return $min . ':' . $max;
    }

    /**
     * Sort the given array for pluralization.
     *
     * @param array $lang The language array to be sorted.
     *
     * @return array
     */
    protected function sortPluralized($lang)
    {
        uksort($lang, array(__CLASS__, 'sortPluralizedCompare'));

        return $lang;
    }

    /**
     * Set a translation value in the translator.
     *
     * @param string $string The string to translate.
     *
     * @param mixed  $value  The value to store.
     *
     * @param string $domain The domain to use.
     *
     * @param string $locale The locale (otherwise the current default locale will get used).
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

        if (!(isset($this->values[$locale]) && is_array($this->values[$locale]))) {
            $this->values[$locale] = array();
        }

        if (!(isset($this->values[$locale][$domain]) && is_array($this->values[$locale][$domain]))) {
            $this->values[$locale][$domain] = array();
        }

        $this->values[$locale][$domain][$string] = $value;

        return $this;
    }

    /**
     * Retrieve the value.
     *
     * @param string $string The string to translate.
     *
     * @param string $domain The domain to use.
     *
     * @param string $locale The locale (otherwise the current default locale will get used).
     *
     * @return mixed
     */
    protected function getValue($string, $domain, $locale)
    {
        if (!$domain) {
            $domain = 'default';
        }

        if (!$locale) {
            $locale = 'default';
        }

        if (!isset($this->values[$locale][$domain][$string])) {
            return $string;
        }

        return $this->values[$locale][$domain][$string];
    }
}
