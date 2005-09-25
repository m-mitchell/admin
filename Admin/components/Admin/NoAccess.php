<?php

require_once 'Admin/pages/AdminPage.php';
require_once 'Swat/SwatMessageDisplay.php';

/**
 * Administrator Not Access page
 *
 * @package Admin
 * @copyright silverorange 2004
 */
class AdminNoAccess extends AdminPage
{
	private $message = null;

	public function init()
	{
		$this->app->getPage()->navbar->replaceElement(1, Admin::_('No Access'));
		parent::init();
	}

	protected function display()
	{
		$message_display = new SwatMessageDisplay();
		$message_display->title = 'No Access';

		if ($this->message !== null)
			$message_display->add($this->message); 

		$message_display->display();
	}

	public function setMessage($msg)
	{
		$this->message = $msg;
	}
}

?>
