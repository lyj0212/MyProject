<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/Template_/Template_.class.php');

class Template extends Template_
{
	var $compile_dir = '';
	var $compile_ext = 'template';

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function __construct()
	{
		log_message('debug', "Template_ Class Initialized");
		$this->template_dir = FCPATH;
	}

}
