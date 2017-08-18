<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Widget
*
* @version:     0.1
* $copyright     Copyright (c) Wiredesignz 2009-03-24
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/
class Widget
{
	function __construct()
	{
		log_message('debug', "Widget Class Initialized");

		$this->_assign_libraries();
	}

	function run($name)
	{
		$args = func_get_args();

		require_once FCPATH.$this->config->item('widget_path').'/'.$name.EXT;

		$name = basename($name);
		$name = ucfirst($name);

		$widget_foo = new $name();
		$widget = &$widget_foo;

		return call_user_func_array(array(&$widget, 'run'), array_slice($args, 1));
	}

	function render($view, $data = array())
	{
		extract($data);
		include FCPATH.$this->config->item('widget_path').'/'.$view.EXT;
	}

	function load($object)
	{
		$this->$object =& load_class(ucfirst($object));
	}

	function _assign_libraries()
	{
		$ci =& get_instance();
		foreach (get_object_vars($ci) as $key => $object) {
			$this->$key =& $ci->$key;
		}
	}
}
