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

/**
 * Abstract base implementation of a translator.
 */
abstract class AbstractTranslator implements TranslatorInterface
{
	/**
	 * {@inheritdoc}
	 */
	abstract protected function getValue($string, $domain, $locale);

	/**
	 * {@inheritdoc}
	 */
	public function translate($string, $domain = null, array $parameters = array(), $locale = null)
	{
		$newString = $this->getValue($string, $domain, $locale);

		if ($newString == $string)
		{
			return $string;
		}

		if (count($parameters))
		{
			$newString = vsprintf($newString, $parameters);
		}

		return $newString;
	}

	/**
	 * Build a choice lookup list from the passed language choice array.
	 *
	 * The input array is something like:
	 * array(
	 *   '1'   => 'an apple',
	 *   '2:5' => 'a few apples',
	 *   '12'  => 'a dozen of apples',
	 *   '13:' => 'a pile of apples'
	 * )
	 *
	 * @param array $choices The language strings.
	 *
	 * @return array
	 */
	protected function buildChoiceLookupList($choices)
	{
		$array = array();

		foreach ($choices as $range => $choice)
		{
			$range = explode(':', $range);

			if (count($range) < 2)
			{
				$range[] = '';
			}

			$array[] = (object)array(
				'range' => (object)array(
						'from' => $range[0],
						'to'   => $range[1],
					),
				'string' => $choice
			);
		}

		return $array;
	}

	/**
	 * Extract a single choice from the array of choices and sanitize its values.
	 *
	 * @param array $choices The choice array.
	 *
	 * @param int   $i       The index to extract.
	 *
	 * @param int   $count   Amount of all choices in the array (passed to prevent calling count() multiple times).
	 *
	 * @return object
	 */
	protected function fetchChoice($choices, $i, $count)
	{
		$choice = $choices[$i];

		// Set from number, if not set (notation ":X").
		if (!$choice->range->from)
		{
			if ($i > 0)
			{
				$choice->range->from = ($choices[($i - 1)]->range->to + 1);
			}
			else {
				$choice->range->from = ( - PHP_INT_MAX);
			}
		}
		// Set to number, if not set (notation "X" or "X:").
		if (!$choice->range->to)
		{
			if ($i < ($count - 1))
			{
				$choice->range->to = ($choices[($i + 1)]->range->from - 1);
			}
			else {
				$choice->range->to = PHP_INT_MAX;
			}
		}

		return $choice;
	}

	/**
	 * {@inheritdoc}
	 */
	public function translatePluralized($string, $number, $domain = null, array $parameters = array(), $locale = null)
	{
		$choices = $this->getValue($string, $domain, $locale);

		if (!is_array($choices))
		{
			return $string;
		}

		if (isset($choices[$number]))
		{
			$newString = $choices[$number];
		}
		else {
			$array = $this->buildChoiceLookupList($choices);

			$count = count($array);
			for ($i = 0; $i < $count; $i++)
			{
				$choice = $this->fetchChoice($array, $i, $count);

				if ($number >= $choice->range->from && $number <= $choice->range->to)
				{
					$newString = $choice->string;
					break;
				}
			}
		}

		if (!isset($newString))
		{
			return $string;
		}

		if (count($parameters))
		{
			$newString = vsprintf($newString, $parameters);
		}

		return $newString;
	}
}



