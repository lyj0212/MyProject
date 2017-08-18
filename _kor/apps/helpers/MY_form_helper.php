<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form Value
 *
 * Grabs a value from the POST array for the specified field so you can
 * re-populate an input field or textarea.  If Form Validation
 * is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @return	mixed
*/
if ( ! function_exists('set_value'))
{
	function set_value($field = '', $default = '')
	{
		if ( ! isset($_POST[$field]))
		{
			return form_prep($default);
		}
		return form_prep($_POST[$field], $field);
	}
}

// ------------------------------------------------------------------------

/**
 * Set Select
 *
 * Let's you set the selected value of a <select> menu via data in the POST array.
 * If Form Validation is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	bool
 * @return	string
*/
if ( ! function_exists('set_select'))
{
	function set_select($field = '', $value = '', $default = FALSE)
	{
		if ( ! isset($_POST[$field]))
		{
			if (count($_POST) === 0 AND $default == TRUE)
			{
				return ' selected="selected"';
			}
			return '';
		}

		$field = $_POST[$field];

		if (is_array($field))
		{
			if ( ! in_array($value, $field))
			{
				return '';
			}
		}
		else
		{
			if (($field == '' OR $value == '') OR ($field != $value))
			{
				return '';
			}
		}

		return ' selected="selected"';
	}
}

// ------------------------------------------------------------------------

/**
 * Set Checkbox
 *
 * Let's you set the selected value of a checkbox via the value in the POST array.
 * If Form Validation is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	bool
 * @return	string
*/
if ( ! function_exists('set_checkbox'))
{
	function set_checkbox($field = '', $value = '', $default = '')
	{
		if ( ! isset($_POST[$field]))
		{
			if (count($_POST) === 0 AND $default == TRUE)
			{
				return ' checked="checked"';
			}
			return '';
		}

		$field = $_POST[$field];

		if (is_array($field))
		{
			if ( ! in_array($value, $field))
			{
				return '';
			}
		}
		else
		{
			if (($field == '' OR $value == '') OR ($field != $value))
			{
				return '';
			}
		}

		return ' checked="checked"';
	}
}

// ------------------------------------------------------------------------

/**
 * Set Radio
 *
 * Let's you set the selected value of a radio field via info in the POST array.
 * If Form Validation is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	bool
 * @return	string
*/
if ( ! function_exists('set_radio'))
{
	function set_radio($field = '', $value = '', $default = FALSE)
	{
		if ( ! isset($_POST[$field]))
		{
			if (count($_POST) === 0 AND $default == TRUE)
			{
				return ' checked="checked"';
			}
			return '';
		}

		$field = $_POST[$field];

		if (is_array($field))
		{
			if ( ! in_array($value, $field))
			{
				return '';
			}
		}
		else
		{
			if (($field == '' OR $value == '') OR ($field != $value))
			{
				return '';
			}
		}

		return ' checked="checked"';
	}
}

/* End of file form_helper.php */
/* Location: ./system/helpers/form_helper.php */
