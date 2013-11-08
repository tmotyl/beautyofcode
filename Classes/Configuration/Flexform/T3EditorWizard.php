<?php
namespace FNagel\Beautyofcode\Service;

/***************************************************************
 * Copyright notice
 *
 * (c) 2010-2013 Felix Nagel (info@felixnagel.com)
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Add t3editor in flexform
 *
 * This file was developed and tested with TYPO3 4.7.7 and its t3editor extension
 *
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */

class FlexformT3EditorWizardService {

	/**
	 *
	 * @var \TYPO3\CMS\T3Editor\T3Editor
	 */
	protected $t3editor;

	/**
	 *
	 * @var array
	 */
	protected $flexformData = array();

	/**
	 * renders a t3editor instance and applies all necessary stuff for highlighting
	 *
	 * @param array $parameters
	 * @param object $pObj
	 * @return void|string
	 */
	public function main($parameters, $pObj) {
		$this->initializeT3Editor();

		if (TRUE === is_null($this->t3editor) || !$this->t3editor->isEnabled()) {
			return;
		}

		// get flexform content
		$this->flexformData = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($parameters['row'][$parameters['field']]);

		$this->setT3EditorMode();

		$content = $this->flexformData['data']['sDEF']['lDEF']['cCode']['vDEF'];

		$doc = $GLOBALS['SOBE']->doc;

		$textareaAttributes = $this->getTextareaAttributes(
			$parameters['table'],
			$parameters['field'],
			$parameters['fieldChangeFunc']['TBE_EDITOR_fieldChanged']
		);

		$parameters['item'] = '';
		$parameters['item'] .= $this->t3editor->getCodeEditor(
			$parameters['itemName'],
			'fixed-font enable-tab',
			$content,
			$textareaAttributes,
			sprintf('%s > %s', $parameters['table'], $parameters['field']),
			array(
				'target' => intval($pObj->target)
			)
		);
		$parameters['item'] .= $this->t3editor->getJavascriptCode($doc);

		return '';
	}

	/**
	 * initializes the t3editor
	 *
	 * @return void
	 */
	protected function initializeT3Editor() {
		// check if t3editor should be loaded at all
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['beautyofcode']);

		$enableT3Editor = (boolean) $extensionConfiguration['enable_t3editor'];
		$t3EditorLoaded = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3editor');

		if ($enableT3Editor && $t3EditorLoaded) {
			$this->t3editor = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\T3Editor\\T3Editor');
		}
	}

	/**
	 * sets the language mode of the T3Editor
	 *
	 * @return void
	 */
	protected function setT3EditorMode() {
		$language = $this->flexformData['data']['sDEF']['lDEF']['cLang']['vDEF'];

		// set code type
		// TODO: check if more available at sysext\t3editor\classes\class.tx_t3editor.php
		switch($language){
			case 'php':
				$this->t3editor->setMode(\TYPO3\CMS\T3Editor\T3Editor::MODE_PHP);
				break;
			case 'xml':
				$this->t3editor->setMode(\TYPO3\CMS\T3Editor\T3Editor::MODE_XML);
				break;
			case 'javascript':
				$this->t3editor->setMode(\TYPO3\CMS\T3Editor\T3Editor::MODE_JAVASCRIPT);
				break;
			case 'css':
				$this->t3editor->setMode(\TYPO3\CMS\T3Editor\T3Editor::MODE_CSS);
				break;
			case 'typoscript':
				$this->t3editor->setMode(\TYPO3\CMS\T3Editor\T3Editor::MODE_TYPOSCRIPT);
				break;
			default:
				$this->t3editor->setMode(\TYPO3\CMS\T3Editor\T3Editor::MODE_MIXED);
		}
	}

	/**
	 * returns a string of additional textarea attributes
	 *
	 * @param string $table the table name to fetch rows/cols attributes
	 * @param field $field the field name to fetch rows/cols attributes
	 * @param string $onChangeFunction the content of the onchange attribute
	 * @return string
	 */
	protected function getTextareaAttributes($table, $field, $onChangeFunction) {
		$config = $GLOBALS['TCA'][$table]['columns'][$field]['config'];

		return 'rows="' . $config['rows'] . '" ' .
			'cols="' . $config['cols'] . '" ' .
			'wrap="off" ' .
			'style="width:98%; height: 100%" ' .
			'onchange="' . $onChangeFunction . '" ';

	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_t3editor.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_t3editor.php']);
}
?>