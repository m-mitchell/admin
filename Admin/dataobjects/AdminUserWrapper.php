<?php

require_once 'SwatDB/SwatDBRecordsetWrapper.php';
require_once 'Admin/dataobjects/AdminUser.php';

/**
 * A recordset wrapper class for AdminUser objects
 *
 * @package   Admin
 * @copyright 2007 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @see       AdminUser
 */
class AdminUserWrapper extends SwatDBRecordsetWrapper
{
	// {{{ protected function init()

	protected function init()
	{
		parent::init();
		$this->row_wrapper_class = 'AdminUser';
		$this->index_field = 'id';
	}

	// }}}
}

?>
