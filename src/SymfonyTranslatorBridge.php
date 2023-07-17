<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2023 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2014-2023 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Translator;

use Symfony\Contracts\Translation\TranslatorInterface as SymfonyTranslator;

final class SymfonyTranslatorBridge implements TranslatorInterface
{
    private SymfonyTranslator $translator;

    public function __construct(SymfonyTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function translate($string, $domain = null, array $parameters = [], $locale = null): string
    {
        if (($string !== $transValue = $this->try($string, $parameters, $domain, $locale))) {
            return  $transValue;
        }
        if (null === $locale) {
            return $string;
        }

        return $this->try($string, $parameters, $domain, null);
    }

    public function translatePluralized(
        $string,
        $number,
        $domain = null,
        array $parameters = [],
        $locale = null
    ): string {
        if (($string !== $transValue = $this->tryPluralized($string, $number, $parameters, $domain, $locale))) {
            return  $transValue;
        }
        if (null === $locale) {
            return $string;
        }

        return $this->tryPluralized($string, $number, $parameters, $domain, null);
    }

    /**
     * This tries to fetch the translation string, if there is no match, return the original string.
     *
     * If there is a match, it will check the parameters and try to interpolate, as long as the parameters are a list.
     * If the parameters are an associative array, they will get passed to the symfony translator instead.
     *
     * @param array<string, string>|list<float|int|string> $parameters
     */
    private function try(string $string, array $parameters, ?string $domain, ?string $locale): string
    {
        if (!empty($domain)) {
            // FIXME: Do we really need to always add contao_ prefix?
            $domain = 'contao_' . $domain;
        }

        // Check if we have an associative array.
        $transParams = array_is_list($parameters) ? [] : $parameters;

        if (($string === $transValue = $this->translator->trans($string, $transParams, $domain, $locale))) {
            return  $string;
        }
        if ([] === $transParams && [] !== $parameters) {
            /** @var list<float|int|string> $parameters */
            return vsprintf($transValue, $parameters);
        }
        return $transValue;
    }

    /**
     * This tries to fetch the translation string, if there is no match, return the original string.
     *
     * If there is a match, it will check the parameters and try to interpolate, as long as the parameters are a list.
     * If the parameters are an associative array, they will get passed to the symfony translator instead.
     *
     * @param array<string, string>|list<float|int|string> $parameters
     */
    private function tryPluralized(
        string $string,
        int $count,
        array $parameters,
        ?string $domain,
        ?string $locale
    ): string {
        if (!empty($domain)) {
            // FIXME: Do we really need to always add contao_ prefix?
            $domain = 'contao_' . $domain;
        }

        // Check if we have an associative array.
        $transParams = array_is_list($parameters) ? [] : $parameters;
        $transParams['%count%'] = $count;

        if (($string === $transValue = $this->translator->trans($string, $transParams, $domain, $locale))) {
            return  $string;
        }
        if (['%count%' => $count] === $transParams && [] !== $parameters) {
            /** @var list<float|int|string> $parameters */
            return vsprintf($transValue, $parameters);
        }
        return $transValue;
    }
}
