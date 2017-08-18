<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Plani Base Module
 *
 * PHP 5.1.6 or newer AND MYSQL 5.0 or newer
 *
 * @package	Plani Module
 * @author	Shin Donguk
 * @copyright	Copyright (c) 2012, Plani, Inc.
 * @link	http://plani.co.kr
 * @since	 Version 2.0
 * @filesource
 */

require_once(dirname(dirname(dirname(__FILE__))).'/controller'.EXT);

// ------------------------------------------------------------------------

/**
 * File Class
 *
 * 게시판 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Files extends PL_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->output->enable_profiler(FALSE);
		$this->load->config('attachment');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function upload()
	{
		if(empty($_FILES['Filedata']) OR ! is_array($_FILES['Filedata']))
		{
			$this->_exception('첨부파일이 존재하지 않습니다.');
		}

		$token = $this->input->post('token');
		$timestamp = $this->input->post('timestamp');
		$pid = $this->input->post('pid');
		$target = $this->input->post('target');
		$html5 = $this->input->post('html5');

		if($this->encrypt->decode($token) != $timestamp)
		{
			$this->_exception('잘못된 접근 입니다.');
		}

		$config['upload_path'] = sprintf('attach/files/%s/', date('Ymd'));
		if( ! is_dir($config['upload_path']))
		{
			if( ! @mkdir($config['upload_path'], 0777, TRUE))
			{
				$this->_exception('디렉토리를 생성할 수 없습니다. 생성 권한 또는 디스크 용량을 확인해 주세요.');
			}
		}

		$config['encrypt_name'] = TRUE;
		$config['allowed_types'] = '*';

		$config_allow_ext = config_item('attachment_allow_ext');
		$allow_ext = array();
		foreach($config_allow_ext as $exts)
		{
			$allow_ext = array_merge($allow_ext, $exts);
		}

		$chk_ext = strtolower(substr(strrchr($_FILES['Filedata']['name'], '.'), 1));
		if( ! in_array($chk_ext, $allow_ext))
		{
			$this->_exception('허용되지 않은 확장자 입니다.');
		}

		if(empty($pid))
		{
			if( ! ($pid = $this->session->userdata('sequence')))
			{
				$pid = $this->_get_sequence();
			}
		}
		$this->session->set_userdata('sequence', $pid);

		$this->load->library('upload');
		$this->upload->initialize($config);

		if( ! $this->upload->do_upload('Filedata'))
		{
			$this->_exception($this->upload->display_errors());
		}
		else
		{
			$upload_data = $this->upload->data();

			$data = array(
				'pid' => $pid,
				'target' => $target,
				'orig_name' => $upload_data['orig_name'],
				'raw_name' => $upload_data['raw_name'],
				'file_ext' => $upload_data['file_ext'],
				'file_size' => $upload_data['file_size']*1024,
				'upload_path' => $config['upload_path']
			);

			$model = array(
				'from' => 'files',
				'data' => $data
			);
			$insert_id = $this->save_row($model);

			$image_ext = $config_allow_ext['이미지'];
			$media_ext = $config_allow_ext['미디어'];
			$ext = str_replace('.', '', strtolower($data['file_ext']));
			if(in_array($ext, $image_ext))
			{
				$type = 'image';
			}
			elseif(in_array($ext, $media_ext))
			{
				$type = 'media';
			}
			else
			{
				$type = 'file';
			}

			$result = array(
				'jsonrpc' => '2.0',
				'result' => NULL,
				'id' => 'id',
				'file' => array(
					'id' => $insert_id,
					'type' => $type,
					'orig_name' => $data['orig_name'],
					'file_url' => ($type != 'file') ? site_url($data['upload_path'].$data['raw_name'].$data['file_ext']) : NULL,
					'download_link' => $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$insert_id), TRUE)
				)
			);
			echo json_encode($result);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function down()
	{
		$id = $this->link->get_segment('id', FALSE);
		if(empty($id))
		{
			script('back', '잘못된 접근 입니다.');
		}

		$model = array(
			'from' => 'files',
			'conditions' => array('where' => array('id' => $id))
		);
		$row = $this->get_row($model);

		if(empty($row['data']['id']))
		{
			script('back', '파일이 존재하지 않습니다.');
		}

		if(strstr($this->input->server('HTTP_USER_AGENT'), 'MSIE') OR strstr($this->input->server('HTTP_USER_AGENT'), 'Trident/'))
		{
			$filename = rawurlencode($row['data']['orig_name']);
			$filename = preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1);
		}
		else
		{
			$filename = $row['data']['orig_name'];
		}

		$uploaded_filename = sprintf('%s%s%s', $row['data']['upload_path'], $row['data']['raw_name'], $row['data']['file_ext']);
		if( ! file_exists($uploaded_filename))
		{
			script('back', '파일이 존재하지 않습니다.');
		}

		$fp = fopen($uploaded_filename, 'rb');
		if( ! $fp)
		{
			script('back', '파일이 존재하지 않습니다.');
		}

		header("Cache-Control: ");
		header("Pragma: ");
		header("Content-Type: application/octet-stream");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		//header("Content-Length: " .(string)($row['data']['file_size']));
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		header("Content-Transfer-Encoding: binary");

		// if file size is lager than 10MB, use fread function (#18675748)
		if($row['data']['file_size'] > 1024 * 1024)
		{
			while( ! feof($fp))
			{
				echo fread($fp, 1024);
			}
			fclose($fp);
		}
		else
		{
			fpassthru($fp);
		}

		$model = array(
			'from' =>'files',
			'conditions' => array('where' => array('id' => $id)),
			'set' => array('download_count' => 'download_count+1')
		);
		$this->save_row($model);

		exit;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function zipDown()
	{
		$pid = $this->link->get_segment('pid', FALSE);
		if(empty($pid))
		{
			script('back', '잘못된 접근 입니다.');
		}

		$model = array(
			'from' => 'files',
			'conditions' => array('where' => array('pid' => $pid))
		);
		$data = $this->get_entries($model);

		if( ! empty($data['data']))
		{
			header_remove('Content-Encoding');

			$this->load->library('Directzip');
			$this->directzip->open(sprintf('%s 외 %s개.zip', substr($data['data'][0]['orig_name'], 0, strrpos($data['data'][0]['orig_name'], ".")), count($data['data'])-1));
			foreach($data['data'] as $file)
			{
				$uploaded_filename = sprintf('%s%s%s', $file['upload_path'], $file['raw_name'], $file['file_ext']);
				$this->directzip->addFile($uploaded_filename, $file['orig_name']);
			}
			$this->directzip->close();
		}
		else
		{
			script('back', '파일이 존재하지 않습니다.');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function viewer()
	{
		$id = $this->link->get_segment('id', FALSE);
		if(empty($id))
		{
			exit('File does not exist.');
		}

		$model = array(
			'from' => 'files',
			'conditions' => array('where' => array('id' => $id))
		);
		$row = $this->get_row($model);

		if(empty($row['data']['id']))
		{
			exit('File does not exist.');
		}

		$uploaded_filename = sprintf('%s%s%s', $row['data']['upload_path'], $row['data']['raw_name'], $row['data']['file_ext']);
		if( ! file_exists($uploaded_filename))
		{
			exit('File does not exist.');
		}

		header("Cache-Control: ");
		header("Pragma: ");
		header("Content-Type: application/octet-stream");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Content-Transfer-Encoding: binary");

		echo file_get_contents($uploaded_filename);

		$model = array(
			'from' =>'files',
			'conditions' => array('where' => array('id' => $id)),
			'set' => array('download_count' => 'download_count+1')
		);
		$this->save_row($model);

		exit;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function delete()
	{
		$id = $this->input->post('id');
		if(empty($id))
		{
			script('back', '잘못된 접근 입니다.');
		}

		$model = array(
			'from' => 'files',
			'conditions' => array('where' => array('id' => $id))
		);
		$row = $this->get_row($model);

		$filename = sprintf('%s%s%s', $row['data']['upload_path'], $row['data']['raw_name'], $row['data']['file_ext']);

		if(file_exists($filename))
		{
			unlink($filename);
		}

		$this->delete_row($model);

		script('back', 'ok');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _error_exists()
	{
		script('back', '게시물이 존재하지 않습니다.');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _exception($log)
	{
		log_message('error', $log);
		die(sprintf('{"jsonrpc" : "2.0", "error" : {"code": 500, "message": "%s"}, "id" : "id"}', strip_tags($log)));
	}

}
