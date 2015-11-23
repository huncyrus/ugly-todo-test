<?php
namespace todo;

/**
 * Class templateHelper
 * @package todo
 */
class templateHelper {
	/**
	 * @var string
	 */
	protected $templateDir = '';

	/**
	 * @var string
	 */
	protected $defaultTemplate = 'index.html';

	/**
	 * @return string
	 */
	public function getTemplateDir() {
		return $this->templateDir;
	}

	/**
	 * @param string $templateDir
	 */
	public function setTemplateDir($templateDir) {
		$this->templateDir = $templateDir;
	}

	/**
	 * @return string
	 */
	public function getDefaultTemplate() {
		return $this->defaultTemplate;
	}

	/**
	 * @param string $templateDir
	 */
	public function __construct($templateDir) {
		$this->setTemplateDir($templateDir);
	}

	/**
	 * @param string $templateFile
	 * @return null|string
	 */
	public function render($templateFile = '') {
		if ('' == $templateFile) {
			$templateFile = $this->getDefaultTemplate();
		}

		$templateFileWithDir = $this->getTemplateDir() . DS . $templateFile;

		if (file_exists($templateFileWithDir)) {
			$fo = fopen($templateFileWithDir, "r");
			$fr = fread($fo, filesize($templateFileWithDir));
			fclose($fo);

			echo $fr;
		} else {
			echo 'Render error. Template: ' . $templateFile . ', template dir: ' . $templateFileWithDir;
		}
	}
}