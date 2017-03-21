<?php
/**
 * HURRICANE
 * DraiWiki default theme
 *
 * @author		Robert Monden
 * @copyright	DraiWiki development team, 2017
 * @version		1.0 Alpha 1
 */

namespace DraiWiki\views\templates;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\views\Template;

class Editor extends Template {

	public function __construct() {
		$this->loadLocale();
		$this->locale->loadFile('editor');
	}

	public function showContent() {
		echo '
			<h1>', $this->locale->read('editor', 'edit_article'), ' ', $this->data['title'], '</h1>
			<form action="', $this->data['action'], '" method="post">
				<input type="hidden" name="id" value="', $this->data['ID'], '" />

				<label 	for="title" class="text_bold">',
						$this->locale->read('editor', 'title'), '
				</label>
				<input 	type="text"
						name="title"
						value="', $this->data['title'], '"
						class="wide" /><br /><br />

				<textarea id="editor" name="body">', $this->data['body'], '</textarea>
				<input type="submit" value="', $this->locale->read('editor', 'save'), '" />
			</form>

			<script type="text/javascript">
				var simplemde = new SimpleMDE({
					element: document.getElementById("editor"),
					tabSize: 8
				});
			</script>';
	}
}
