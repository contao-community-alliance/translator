<?php

/**
 * This file is part of contao-community-alliance/translator.
 *
 * (c) 2013-2017 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/translator
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2017 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/translator/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

error_reporting(E_ALL);

function includeIfExists($file)
{
	return file_exists($file) ? include $file : false;
}

if ((!$loader = includeIfExists(__DIR__.'/../vendor/autoload.php'))
	&& (!$loader = includeIfExists(__DIR__.'/../../../autoload.php'))
)
{
	echo 'You must set up the project dependencies, run the following commands:'.PHP_EOL.
		'curl -sS https://getcomposer.org/installer | php'.PHP_EOL.
		'php composer.phar install'.PHP_EOL;
	exit(1);
}
