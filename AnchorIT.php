<?php
/*
Plugin Name: Anchor IT
Plugin URI:
Description:
Version: 1.0
Author: Developer-G0947
Author URI:
License:
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class AnchorIT {

	private $_optionKeyWords = "gs_anchor_it_words";
	private $_optionKeylink  = "gs_anchor_it_link";

	//** Constructor **//
	function __construct() {
		// add_action('admin_footer', array( &$this, 'addScript' ));
		add_action('admin_menu', array(&$this, 'addMenu') );
		add_action('the_content', array(&$this, 'anchorFileter') );
	}

	function addMenu() {
		add_menu_page( 'Anchor IT', 'Anchor IT', 'manage_options', 'anchorit', array(&$this	, 'settingPage'), 'dashicons-editor-unlink', 26 );
	}

	function settingPage(){
		if( isset($_POST['udpateData']) ){
			$result = $this->updateSettings( $_POST );
			if( $result ){
				echo "<h2>Settings updated.</h2>";
			}
		}

	?>	<div class="wrap">
			<h1>AnchorIt Settings.</h1>
			<form action="" method="post" class="gs-form">
				<div class="form-group">
					<label for="link">Enter link</label><br/>
					<input type="text" class="from-control" name="link" value="<?php echo $this->getlink()?>" />
				</div>
				<div class="form-group">
					<label for="words">Tags words</label><br/>
					<textarea name="words" id="words" cols="30" rows="10" class="from-control"><?php echo $this->getWords()?></textarea>
				</div>
				<input type="hidden" Value="1" name="udpateData" />
				<input type="submit" Value="save settings" />
			</form>
		</div>

	<?php
	}

	/* filter function */
	function anchorFileter( $content ){
		$this->getWords();
		$patterns		=	array();
		$replacements	=	array();

		$terms  = explode(',', $this->getWords());
		$anchor = $this->getlink();

		foreach ($terms as $term) {
			$patterns[]     = "/ ".trim($term)."\b/i";
			$replacements[] = ' <a href="'.$anchor.'">'.trim($term).'</a>';
		}

		$updateContent  = preg_replace($patterns, $replacements, $content );

		return $updateContent;
	}

	/* Update Options */
	private function updateSettings( $data ) {

		$optionKeyWords = $this->_optionKeyWords;
		$optionKeylink  = $this->_optionKeylink;

		$optionValuesWords = trim($data['words']);
		$optionValuesLinks = trim($data['link']);

		update_option( $optionKeyWords, $optionValuesWords );
		update_option( $optionKeylink, $optionValuesLinks );

		return true;
	}

	private function getWords(){
		return get_option( $this->_optionKeyWords,true);
	}

	private function getLink(){
		return get_option( $this->_optionKeylink,true);
	}

}//** Class ends here. **//

$anchorIT = new AnchorIT;

?>