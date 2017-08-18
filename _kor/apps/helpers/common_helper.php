<?php
/**
 *
 *
 * @todo 2009-09-01, constructed
 * @keyword description
 *
 * @author Shin dong-uk <uks@plani.co.kr>
 * @copyright Copyright (c) 2009, Plani
**/

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function module_path()
{
  $CI =& get_instance();
  return sprintf('/modules/%s', $CI->router->fetch_module());
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function get_field($var)
{
	$CI =& get_instance();
	return decode_url($CI->link->get_segment($var, FALSE));
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function is_ajax()
{
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function message($message, $type='success')
{
	static $flash_message = array();

	$CI =& get_instance();
	$flash_message[$type][] = $message;
	$CI->session->set_userdata('flash_message', $flash_message);
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function notification($params=array())
{
	$CI =& get_instance();

	$option['ismember'] = '';
	$option['sender'] = $CI->account->get('id');
	$option['title'] = '창조경제혁신센터';
	$option['message'] = '';
	$option['name'] = $CI->account->get('name');
	$option['picture'] = $CI->account->get('thumb.mini');
	$option['link'] = '';
	$option['attr'] = '';

	$CI->_set($option, $params);

	$data['ismember'] = $option['ismember'];
	$data['sender'] = $option['sender'];
	$data['message'] = str_replace('&nbsp;', ' ', strip_tags($option['message']));
	$data['link'] = $option['link'];
	$data['attr'] = $option['attr'];

	$model = array(
		'from' => 'feed',
		'data' => $data
	);
	$feed_id = $CI->save_row($model);

	$notification_data = array(
		'id' => $data['ismember'],
		'title' => html_entity_decode($option['title']),
		'message' => addslashes(str_cut(html_entity_decode($data['message']), 70)),
		'name' => $option['name'],
		'picture' => $option['picture'],
		'link' => sprintf('/feed/feed/redirect/id/%s', $feed_id),
		'attr' => $option['attr']
	);

	$___ELEPHANTIO_DIR = APPPATH.'libraries/ElephantIO';
	require_once $___ELEPHANTIO_DIR.'/Client.php';
	require_once $___ELEPHANTIO_DIR.'/AbstractPayload.php';
	require_once $___ELEPHANTIO_DIR.'/EngineInterface.php';
	require_once $___ELEPHANTIO_DIR.'/Engine/AbstractSocketIO.php';
	require_once $___ELEPHANTIO_DIR.'/Engine/SocketIO/Session.php';
	require_once $___ELEPHANTIO_DIR.'/Engine/SocketIO/Version1X.php';
	require_once $___ELEPHANTIO_DIR.'/Exception/MalformedUrlException.php';
	require_once $___ELEPHANTIO_DIR.'/Exception/ServerConnectionFailureException.php';
	require_once $___ELEPHANTIO_DIR.'/Exception/SocketException.php';
	require_once $___ELEPHANTIO_DIR.'/Exception/UnsupportedActionException.php';
	require_once $___ELEPHANTIO_DIR.'/Exception/UnsupportedTransportException.php';
	require_once $___ELEPHANTIO_DIR.'/Payload/Decoder.php';
	require_once $___ELEPHANTIO_DIR.'/Payload/Encoder.php';

	static $EIO;

	if(empty($EIO))
	{
		$EIO = new ElephantIO\Client(
			new ElephantIO\Engine\SocketIO\Version1X('114.108.180.142:5675', ['timeout' => 5])
		);
		$EIO->initialize();
	}

	$EIO->emit('whisper', $notification_data);
	$EIO->close();

	$CI->cache->delete(sprintf('feed_%s', $data['ismember']));
	$CI->cache->delete(sprintf('feed_total_%s', $data['ismember']));
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function script($mode, $msg='', $url='', $exit=TRUE, $head=TRUE, $return=FALSE)
{
	if(is_ajax() AND $return != TRUE)
	{
		echo $msg;
		exit;
	}

	$script =  ($head==TRUE) ? sprintf('<meta charset="%s">', 'UTF-8') : '';
	$script .= '<script type="text/javascript">';
	switch($mode)
	{
		case 'ment' :
			//echo "$.jGrowl('$msg');";
			$script .= "alert('$msg');";
			break;
		case 'go' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "document.location.href='$url';";
			break;
		case 'parentgo' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "parent.location.href = '$url';";
			break;
		case 'close' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "try{ parent.$.colorbox.close(); } catch(e) { self.close(); }";
			break;
		case 'reload' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "document.location.reload();";
			break;
		case 'parentreload' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "parent.location.reload();";
			break;
		case 'back' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "history.back();";
			break;
		case 'confirm' :
			$script .= "if(confirm('$msg')) location.href='$url'; else history.back();";
			break;
		case 'redirect' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "document.location.replace('$url');";
			break;
		case 'topredirect' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "top.location.replace('$url');";
			break;
		case 'login' :
			$r_url = encode_url();
			if(!empty($msg)) $script .= "alert('$msg');";

			$CI =& get_instance();
			$script .= "document.location.replace('".$CI->menu->login_page."');";
			break;
		case 'popup' :
			if(!empty($msg)) $script .= "alert('$msg');";
			$script .= "$(document).ready(function() { showBox('$url', 'iframe', '500'); });";
			break;
	}
	$script .= '</script>';

	if($return) return $script;
	else echo $script;

	if($exit) exit();
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function str_cut($value, $len, $suffix='...')
{
	if($len > 0)
	{
		$str = mb_strimwidth($value, 0, $len, $suffix, 'utf-8');
	}
	else
	{
		$str = $value;
	}
	return $str;
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function ext($ext='')
{
	$ext = strtolower(str_replace('.', '', $ext));
	if(empty($ext))
	{
		$icon = 'blank.gif';
	}
	else
	{
		if(file_exists(sprintf('../_res/ext/%s.gif', $ext)))
		{
			$icon = sprintf('%s.gif', $ext);
		}
		else
		{
			$icon = 'default.gif';
		}
	}

	return sprintf('<img src="/_res/ext/%s" width="16" height="16" alt="%s" class="file_ext" />', $icon, $ext);
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function encode_url($url='')
{
	if(empty($url) AND ! is_array($url)) $url = sprintf('/%s', uri_string());
	return strtr(base64_encode(addslashes(gzcompress(serialize($url), 9))), '+/=', '-_.');
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function decode_url($url)
{
	if ( !empty($url))
	{
		return @unserialize(gzuncompress(stripslashes(base64_decode(strtr($url, '-_.', '+/=')))));
	}
	else
	{
		return FALSE;
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
function debug($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function human_filesize($bytes)
{
	/* 1GB = 1.024MB, 1MB = 1.024KB, 1KB = 1.024B */
	$kb = 1024;
	$mb = 1048576;
	$gb = 1073741824;
	$tb = 1099511627776;
	if(!$bytes) $bytes = 0;
	if($bytes < $kb) return $bytes." Byte";
	elseif($bytes < $mb) return round($bytes/$kb,1)." KB";
	elseif($bytes < $gb) return round($bytes/$mb,1)." MB";
	elseif($bytes < $tb) return round($bytes/$gb,1)." GB";
	else return round($bytes/$tb,1)." TB";
}


// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function convert_ip($ip)
{
	list($w, $x, $y, $z) = explode('.', $ip);
	$ipnum = (16777216*$w) + (65536*$x) + (256*$y) + $z;
	return $ipnum;
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function array_unique_recursive($array)
{
	$result = array_map('unserialize', array_unique(array_map('serialize', $array)));

	foreach($result as $key => $value)
	{
		if(is_array($value))
		{
			$result[$key] = array_unique_recursive($value);
		}
	}

	return $result;
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function object2array($object)
{
	return json_decode(json_encode($object), 1);
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function number2hangul($number)
{
	if(empty($number))
	{
		return FALSE;
	}

	$num = array('', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구');
	$unit4 = array('', '만', '억', '조', '경');
	$unit1 = array('', '십', '백', '천');

	$res = array();

	$number = str_replace(',', '', $number);
	$split4 = str_split(strrev((string)$number), 4);

	for($i=0; $i<count($split4); $i++)
	{
		$temp = array();
		$split1 = str_split((string)$split4[$i], 1);
		for($j=0; $j<count($split1); $j++)
		{
			$u = (int)$split1[$j];
			if($u > 0) $temp[] = $num[$u].$unit1[$j];
		}
		if(count($temp) > 0) $res[] = implode('', array_reverse($temp)).$unit4[$i];
	}

	return implode('', array_reverse($res));
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function js_unescape($s)
{
	$s= preg_replace('/%u(....)/', '&#x$1;', $s);
	$s= preg_replace('/%(..)/', '&#x$1;', $s);
	return html_entity_decode($s, ENT_COMPAT, 'utf-8');
}

// --------------------------------------------------------------------

/**
 * Initialize the Controller Preferences
 *
 * @access  private
 * @params array
 * @return  void
 */
function sha512($s)
{
    // for dbmail, sha512에서 sha256으로 변경
    return hash('sha256', $s);
    //return base64_encode(hash('sha512', $s, $s));
}

// --------------------------------------------------------------------
// --------------------------------------------------------------------
/**
 * Initialize the Controller Preferences
 *
 * @access	private
 * @params array
 * @return	void
 */
function number_format_down($num, $d=0)
{
	return sgn($num)*p_floor(abs($num), $d);
}

function p_floor($val, $d)
{
	return floor($val*pow(10, $d))/pow(10, $d);
}

function sgn($x)
{
	return $x ? ($x>0 ? 1 : -1) : 0;
}