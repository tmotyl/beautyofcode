<?php
namespace TYPO3\Beautyofcode\Highlighter\Configuration;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Prism
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\Highlighter\Configuration
 */
class Prism extends AbstractConfiguration {

	/**
	 * Failsafe brush alias map
	 *
	 * @var array
	 */
	protected $failSafeBrushAliasMap = array(
		'SyntaxHighlighter' => array(
			'applescript' => 'javascript',
			'actionscript3' => 'javascript',
			'coldfusion' => 'markup',
			'delphi' => 'plain',
			'diff' => 'plain',
			'erlang' => 'plain',
			'javafx' => 'java',
			'perl' => 'c',
			'powershell' => 'bash',
			'sass' => 'scss',
			'scala' => 'java',
			'vb' => 'plain',
			'xml' => 'markup',
		),
	);


	/**
	 * A CSS class/label map for the select box
	 *
	 * Key is the brush string from TS Setup; Value is an array with the CSS
	 * class in key 0 and the label for the select box in key 1
	 *
	 * @var array
	 */
	protected $brushIdentifierAliasLabelMap = array(
		'bash' => array('bash', 'Bash / Shell'),
		'c' => array('c', 'C / C++'),
		'clike' => array('clike', 'C-Like'),
		'coffeescript' => array('coffeescript', 'Coffeescript'),
		'cpp' => array('cpp', 'C / C++'),
		'csharp' => array('csharp', 'C#'),
		'css' => array('css', 'CSS'),
		'gherkin' => array('gherkin', 'Gherkin'),
		'go' => array('go', 'Go'),
		'groovy' => array('groovy', 'Groovy'),
		'http' => array('http', 'HTTP'),
		'java' => array('java', 'Java'),
		'javascript' => array('javascript', 'JavaScript'),
		'markup' => array('markup', 'XML / XSLT / XHTML / HTML'),
		'php' => array('php', 'PHP'),
		'python' => array('python', 'Python'),
		'ruby' => array('ruby', 'Ruby'),
		'scss' => array('scss', 'SCSS'),
		'sql' => array('sql', 'SQL'),
		'typoscript' => array('typoscript', 'TypoScript'),
	);

	/**
	 * GetAutoloaderBrushMap
	 *
	 * The Prism highlighter doesn't have any autoloader, but as this method
	 * needs to be implemented, it returns an empty array.
	 *
	 * @return array
	 */
	public function getAutoloaderBrushMap() {
		return array();
	}

	/**
	 * GetClassAttributeString
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform Flexform
	 *
	 * @return string
	 */
	public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform) {
		$configurationItems = array();
		$classAttributeConfigurationStack = array(
			'data-line' => \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($flexform->getCHighlight()),
		);

		foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
			if (TRUE === in_array($configurationValue, array('', 'auto'))) {
				continue;
			}

			$configurationItems[] = sprintf('%s="%s"', $configurationKey, $configurationValue);
		}

		return ' ' . implode(' ', $configurationItems);
	}
}
