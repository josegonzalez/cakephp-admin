<?php
/**
 * Template Task can generate templated output Used in other Tasks
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.console.libs.tasks
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppShell', 'Console/Command');

class AdminTemplateTask extends AppShell {

/**
 * variables to add to template scope
 *
 * @var array
 */
	var $templateVars = array();

/**
 * Set variable values to the template scope
 *
 * @param mixed $one A string or an array of data.
 * @param mixed $two Value in case $one is a string (which then works as the key).
 *   Unused if $one is an associative array, otherwise serves as the values to $one's keys.
 * @return void
 */
	function set($one, $two = null) {
		$data = null;
		if (is_array($one)) {
			if (is_array($two)) {
				$data = array_combine($one, $two);
			} else {
				$data = $one;
			}
		} else {
			$data = array($one => $two);
		}

		if ($data == null) {
			return false;
		}

		foreach ($data as $name => $value) {
			$this->templateVars[$name] = $value;
		}
	}

/**
 * Runs the template
 *
 * @param string $directory directory / type of thing you want
 * @param string $filename template name
 * @param string $vars Additional vars to set to template scope.
 * @access public
 * @return contents of generated code template
 */
	function generate($directory, $filename, $vars = null) {
		if ($vars !== null) {
			$this->set($vars);
		}

		$templateFile = $this->_findTemplate($directory, $filename);
		if ($templateFile) {
			extract($this->templateVars);
			ob_start();
			ob_implicit_flush(0);
			include($templateFile);
			$content = ob_get_clean();
			$this->templateVars = array();
			return $content;
		}
		$this->templateVars = array();
		return '';
	}

/**
 * Parses frontmatter from given content and returns the split data
 * as an array of [content, parsed frontmatter]
 *
 * Parsed frontmatter is always an array, whose contents are dependent
 * upon the template in use
 *
 * If frontmatter exists more than once, then this method will return
 * false. Frontmatter may only be specified once per template
 *
 * @param string $content
 * @return void
 */
	function parseMetadata($contents) {
		$results = preg_match('/^(?:---\s*[\n\r]*)(.*)[\n\r]*(?:---\s*[\n\r]*)/ms', $contents, $matches);
		if ($results == 0) return array($contents, array());
		if ($results > 1) return false;

		return array(
			str_replace($matches[0], "\t", $contents),
			$this->json_decode_nice("{{$matches[1]}}")
		);
	}

/**
 * Allows the Json frontmatter to be a bit more lenient and YAML like if necessary
 *
 * @param string $json
 * @param boolean $assoc
 * @return void
 */
	function json_decode_nice($json, $assoc = false) {
		$json = str_replace(array("\n","\r"),"", $json);
		$json = preg_replace('/([{,])(\s*)([^"]+?)\s*:/','$1"$3":',$json);
		return json_decode($json, $assoc);
	}
/**
 * Find a template inside a directory inside a path.
 *
 * @param string $directory Subdirectory to look for ie. 'views', 'objects'
 * @param string $filename lower_case_underscored filename you want.
 * @access public
 * @return string filename will exit program if template is not found.
 */
	function _findTemplate($directory, $filename) {
		$themeFile = $directory . DS . $filename . '.ctp';
		if (file_exists($themeFile)) {
			return $themeFile;
		}

		$this->err(sprintf(__('Could not find template for %s', true), $filename));
		$this->err(sprintf(__('Error in path %s', true), $themeFile));
		return false;
	}
}
