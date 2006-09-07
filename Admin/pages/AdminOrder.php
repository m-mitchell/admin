<?php

require_once 'Admin/Admin.php';
require_once 'Admin/AdminUI.php';
require_once 'Admin/pages/AdminPage.php';

/**
 * Generic admin ordering page
 *
 * This class is intended to be a convenience base class. For a fully custom 
 * ordering page, inherit directly from AdminPage instead.
 *
 * @package Admin
 * @copyright silverorange 2004
 */
abstract class AdminOrder extends AdminPage
{
	// init phase
	// {{{ protected function initInternal()

	protected function initInternal()
	{
		parent::initInternal();
		$this->ui->getRoot()->addJavaScript('packages/admin/javascript/admin-order.js',
			Admin::PACKAGE_ID);
		$this->ui->loadFromXML(dirname(__FILE__).'/order.xml');
	}

	// }}}

	// process phase
	// {{{ protected function processInternal()

	protected function processInternal()
	{
		parent::processInternal();
		$form = $this->ui->getWidget('order_form');

		if ($form->isProcessed()) {
			$this->saveData();
			$this->relocate();
		}
	}

	// }}}
	// {{{ protected function saveData()

	protected function saveData()
	{
		$count = 0;
		$order_widget = $this->ui->getWidget('order');
		$options_list = $this->ui->getWidget('options');

		foreach ($order_widget->values as $id) {
			if ($options_list->value == 'custom')
				$count++;

			$this->saveIndex($id, $count);
		}
	}

	// }}}
	// {{{ protected function saveIndex()

	/**
	 * Save index
	 *
	 * This method is called by {@link AdminOrder::saveData()} to save a single 
	 * ordering index. Sub-classes should implement this method and perform 
	 * whatever actions are necessary to store the ordering index. Widgets can
	 * be accessed through the $ui class variable.
	 *
	 * @param integer $id An integer identifier of the data to store.
	 * @param integer $index The ordering index to store.
	 */
	abstract protected function saveIndex($id, $index);

	// }}}
	// {{{ protected function relocate()

	/**
	 * Relocate after process
	 */
	protected function relocate()
	{
		$form = $this->ui->getWidget('order_form');
		$url = $form->getHiddenField(self::RELOCATE_URL_FIELD);
		$this->app->relocate($url);
	}

	// }}}

	// build phase
	// {{{ protected function buildInternal()

	protected function buildInternal()
	{
		parent::buildInternal();
		$this->buildOptionList();
		$this->buildButton();
		$this->buildForm();
		$this->buildNavBar();
		$this->loadData();
	}
	
	// }}}
	// {{{ protected function buildOptionList()

	protected function buildOptionList()
	{
		$options_list = $this->ui->getWidget('options');
		$options_list->addOptionsByArray(array(
			'auto'=>Admin::_('Automatically'),
			'custom'=>Admin::_('Custom')));
	}

	// }}}
	// {{{ protected function buildButton()

	protected function buildButton()
	{
		$button = $this->ui->getWidget('submit_button');
		$button->title = Admin::_('Update Order');
	}
	// }}}
	// {{{ protected function buildForm()

	protected function buildForm()
	{
		$form = $this->ui->getWidget('order_form');
		$form->action = $this->source;

		if ($form->getHiddenField(self::RELOCATE_URL_FIELD) === null) {
			$url = $this->getRefererURL();
			$form->addHiddenField(self::RELOCATE_URL_FIELD, $url);
		}
	}

	// }}}
	// {{{ protected function buildNavBar()

	protected function buildNavBar()
	{
		$this->navbar->createEntry(Admin::_('Change Order'));
	}

	// }}}
	// {{{ protected function loadData()

	/**
	 * Load the data
	 *
	 * This method is called to load data to be edited into the widgets.
	 * Sub-classes should implement this method and perform whatever actions
	 * are necessary to obtain the data. Widgets can be accessed through the
	 * $ui class variable.
	 */
	abstract protected function loadData();

	// }}}
	// {{{ protected function display()

	protected function display()
	{
		parent::display();
		$this->displayJavaScript();
	}

	// }}}
	// {{{ private function displayJavaScript()

	private function displayJavaScript()
	{
		echo '<script type="text/javascript">'."\n";

		printf("AdminOrder('options_custom', order_obj);\n");

		echo '</script>';
	}
}

?>
