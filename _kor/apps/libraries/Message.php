<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message
{
	private $CI;

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
		log_message('debug', 'Message Class Initialized');
		$this->CI =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function send($ismember, $message)
	{
		$receive_data = array(
			'type' => 'R',
			'member_id' => $ismember,
			'sender_id' => $this->CI->account->get('id'),
			'message' => $message
		);
		$model = array(
			'from' => 'message',
			'data' => $receive_data
		);
		$receive_id = $this->CI->save_row($model);

		return $receive_id;
	}

}
