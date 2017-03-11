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
				<textarea name="content" id="editor">', $this->data['body'], '</textarea>
			</form>

			<script type="text/javascript">
				var simplemde = new SimpleMDE({
					element: document.getElementById("editor"),
					tabSize: 8
				});
			</script>';
	}
}