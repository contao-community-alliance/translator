Translator
==========
[![Build Status](https://github.com/contao-community-alliance/translator/actions/workflows/diagnostics.yml/badge.svg)](https://github.com/contao-community-alliance/translator/actions)

The Contao Community Alliance translation library allows easy use of various translation string sources.

It ships with a collection of various translation string providers:

* Static values that get populated during runtime by code (StaticTranslator).
* Adapter for [Contao CMS](https://github.com/contao/core) language string arrays (LangArrayTranslator).

In addition it also provides a translator chain using which various translators can be stacked.

Usage:
------

It integrates into the Contao CMS providing a translator service. To use it, just get the service from the 
[dependency container](https://github.com/contao-community-alliance/dependency-container):

```
<?php

/** @var ContaoCommunityAlliance\Translator\TranslatorInterface */
$translator = $GLOBALS['container']['translator'];

// Get the translation of yes from the MSC domain.
$translated = $translator->translate('yes', 'MSC');

// Sub arrays known in Contao can be accessed usng the dot as separator.
$translated = $translator->translate('title.0', 'tl_content');  
```

Known limitations:
------------------

* We have no manual so far. Bummer! :/
* We have not tested it all yet, so please give it a try yourself.
