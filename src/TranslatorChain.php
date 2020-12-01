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
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2018 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Translator;

/**
 * This translator is a chain of translators.
 *
 * When a translation is requested, the chain tries all stored translators and returns the first value not equal to the
 * input.
 */
class TranslatorChain implements TranslatorInterface
{
    /**
     * The list of stored translators.
     *
     * @var TranslatorInterface[]
     */
    protected $translators = array();

    /**
     * Keep going over translators, even if a translation was found.
     *
     * @var bool
     */
    protected $keepGoing = false;

    /**
     * Clear the chain.
     *
     * @return TranslatorChain
     */
    public function clear()
    {
        $this->translators = array();

        return $this;
    }

    /**
     * Add all passed translators to the chain.
     *
     * @param array $translators The translators to add.
     *
     * @return TranslatorChain
     */
    public function addAll(array $translators)
    {
        foreach ($translators as $translator) {
            $this->add($translator);
        }

        return $this;
    }

    /**
     * Add a translator to the chain.
     *
     * @param TranslatorInterface $translator The translator to add.
     *
     * @return TranslatorChain
     */
    public function add(TranslatorInterface $translator)
    {
        $hash = spl_object_hash($translator);

        $this->translators[$hash] = $translator;

        return $this;
    }

    /**
     * Remove a translator from the chain.
     *
     * @param TranslatorInterface $translator The translator.
     *
     * @return TranslatorChain
     */
    public function remove(TranslatorInterface $translator)
    {
        $hash = spl_object_hash($translator);

        unset($this->translators[$hash]);

        return $this;
    }

    /**
     * Get an array of all translators.
     *
     * @return array
     */
    public function getAll()
    {
        return array_values($this->translators);
    }

    /**
     * Determinate if keep going is enabled.
     *
     * @return boolean
     */
    public function isKeepGoing()
    {
        return $this->keepGoing;
    }

    /**
     * Set keep going status.
     *
     * @param bool $keepGoing Set the keep going status.
     *
     * @return TranslatorChain
     */
    public function setKeepGoing($keepGoing)
    {
        $this->keepGoing = $keepGoing;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($string, $domain = null, array $parameters = array(), $locale = null)
    {
        $original = $string;

        // phpcs:disable
        for ($translator = reset($this->translators);
             $translator && ($this->keepGoing || $string == $original);
             $translator = next($this->translators)) {
            $string = $translator->translate($string, $domain, $parameters, $locale);
        }
        // phpcs:enable

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    public function translatePluralized($string, $number, $domain = null, array $parameters = array(), $locale = null)
    {
        $original = $string;

        // phpcs:disable
        for ($translator = reset($this->translators);
             $translator && ($this->keepGoing || $string == $original);
             $translator = next($this->translators)) {
            $string = $translator->translatePluralized($string, $number, $domain, $parameters, $locale);
        }
        // phpcs:enable

        return $string;
    }
}
