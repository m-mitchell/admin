<?php

require_once 'Admin/AdminUI.php';
require_once 'SwatDB/SwatDB.php';
require_once 'Admin/pages/AdminIndex.php';
require_once 'Admin/AdminTableStore.php';
require_once 'include/HistoryCellRenderer.php';

/**
 * Index page for AdminUsers component
 *
 * @package Admin
 * @copyright 2004-2006 silverorange
 */
class AdminUsersIndex extends AdminIndex
{
	// init phase
	// {{{ protected function initInternal()

	protected function initInternal()
	{
		$this->ui->loadFromXML(dirname(__FILE__).'/index.xml');

		// set a default order on the table view
		$index_view = $this->ui->getWidget('index_view');
		$index_view->setDefaultOrderbyColumn(
			$index_view->getColumn('username'),
			SwatTableViewOrderableColumn::ORDER_BY_DIR_ASCENDING);
	}

	// }}}

	// process phase
	// {{{ protected function processActions()

	protected function processActions(SwatTableView $view, SwatActions $actions)
	{
		$num = count($view->checked_items);
		$msg = null;
		
		switch ($actions->selected->id) {
			case 'delete':
				$this->app->replacePage('AdminUsers/Delete');
				$this->app->getPage()->setItems($view->checked_items);
				break;

			case 'enable':
				SwatDB::updateColumn($this->app->db, 'adminusers', 
					'boolean:enabled', true, 'id', 
					$view->checked_items);

				$msg = new SwatMessage(sprintf(Admin::ngettext("%d user has been enabled.", 
					"%d users have been enabled.", $num), $num));

				break;

			case 'disable':
				SwatDB::updateColumn($this->app->db, 'adminusers', 
					'boolean:enabled', false, 'id', 
					$view->checked_items);

				$msg = new SwatMessage(sprintf(Admin::ngettext("%d user has been disabled.", 
					"%d users have been disabled.", $num), $num));

				break;
		}
		
		if ($msg !== null)
			$this->app->messages->add($msg);
	}

	// }}}

	// build phase
	// {{{ protected function getTableStore()

	protected function getTableStore($view)
	{
		$sql = 'select AdminUser.id, AdminUser.username, AdminUser.name,
					AdminUser.enabled, AdminUserLastLoginView.last_login
				from AdminUser 
				left outer join AdminUserLastLoginView on
					AdminUserLastLoginView.usernum = AdminUser.id
				order by %s';

		$sql = sprintf($sql, $this->getOrderByClause($view, 'AdminUser.username'));

		$store = SwatDB::query($this->app->db, $sql, 'AdminTableStore');

		return $store;
	}

	// }}}
}

?>
