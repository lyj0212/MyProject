<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'helpers/JShrink/Minifier.php');

if( ! function_exists('scriptForLayout'))
{
	function scriptForLayout($data, $type='js', $url_only=FALSE)
	{
		$result = array();

		if($type == 'js')
		{
			if( ! empty($data['js']))
			{
				$pack = array();
				$normal = array();
				foreach($data['js'] as $js)
				{
					$filename = '';
					if(is_array($js))
					{
						$filename = sprintf('%s', $js[0]);
					}
					else
					{
						$filename = sprintf('%s', $js);
					}

					if(strpos($filename, '.') === 0)
					{
						$filename = sprintf('/%s%s', SITE, ltrim($filename, '.'));
					}

					if(is_array($js) AND isset($js[1]) AND $js[1] == FALSE)
					{
						$normal[] = $filename;
					}
					else
					{
						$pack[] = $filename;
					}
				}

				$packed = packedJavascript($pack);
				//$packed = $pack;
				foreach($packed as $script)
				{
					if($url_only == TRUE)
					{
						$result[] = $script;
					}
					else
					{
						$result[] = '<script src="'.$script.'"></script>';
					}
				}

				foreach($normal as $script)
				{
					if($url_only == TRUE)
					{
						$result[] = $script;
					}
					else
					{
						$result[] = '<script src="'.$script.'"></script>';
					}
				}
			}
		}
		else
		{
			if( ! empty($data['css']))
			{
				foreach($data['css'] as $css)
				{
					if(strpos($css, '.') === 0)
					{
						$css = sprintf('/%s%s', SITE, ltrim($css, '.'));
					}
					$result[] = '<link rel="stylesheet" href="'.$css.'">';
				}
			}
		}

		if($url_only == TRUE)
		{
			return $result;
		}
		else
		{
			return implode("\n", $result)."\n";
		}
	}
}

if( ! function_exists('moduleForLayout'))
{
	function moduleForLayout($javascript, $buffer)
	{
		if(empty($javascript)) return FALSE;

		$cache_path = config_item('cache_path');
		$js_dir = dirname(rtrim(FCPATH, '/'));
		$link = sprintf('/%s%s', SITE, ltrim($cache_path, '.'));

		$fileName = _find(_generateFileName($javascript).'_([0-9]{10}).php');

		if( ! empty($fileName))
		{
			$fileName = $fileName[0];
			$packed_ts = filemtime($cache_path.$fileName);
			$latest_ts = 0;
			foreach($javascript as $script)
			{
				$latest_ts = max($latest_ts, filemtime($script));
			}

			if($latest_ts > $packed_ts)
			{
				@unlink($cache_path.$fileName);
				$fileName = NULL;
			}
		}

		if(empty($fileName))
		{
			$ts = time();

			$buffer = preg_replace('|<script(.*)>|i', '', $buffer);
			$buffer = preg_replace('|</(.*)script>|i', '', $buffer);

			$buffer = "<"."?php  header('Content-type: application/javascript'); ?".">".$buffer;

			$scriptBuffer = trim(\JShrink\Minifier::minify($buffer));
			$fileName = _generateFileName($javascript).'_'.$ts.'.php';
			file_put_contents($cache_path.$fileName, $scriptBuffer);
		}

		return '<script src="'.$link.$fileName.'?t='.time().'"></script>'."\n";
	}
}

if( ! function_exists('packedJavascript'))
{
	function packedJavascript($file)
	{
		$cache_path = config_item('cache_path');
		$js_dir = dirname(rtrim(FCPATH, '/'));
		$link = sprintf('/%s%s', SITE, ltrim($cache_path, '.'));

		if( ! is_dir($cache_path))
		{
			mkdir($cache_path, 0777, TRUE);
		}

		$file = array_chunk($file, 7);
		$packed = array();
		foreach($file as $javascript)
		{
			if(count($javascript) > 0)
			{
				$fileName = _find(_generateFileName($javascript).'_([0-9]{10}).js');

				if( ! empty($fileName))
				{
					$fileName = $fileName[0];
					$packed_ts = filemtime($cache_path.$fileName);
					$latest_ts = 0;
					foreach($javascript as $script)
					{
						$latest_ts = max($latest_ts, filemtime($js_dir.$script));
					}

					if($latest_ts > $packed_ts)
					{
						@unlink($cache_path.$fileName);
						$fileName = NULL;
					}
				}

				if(empty($fileName))
				{
					$ts = time();
					$scriptBuffer = '';
					foreach($javascript as $script)
					{
						if(file_exists($js_dir.$script))
						{
							$buffer = file_get_contents($js_dir.$script);
							$buffer = trim(\JShrink\Minifier::minify($buffer));
							$scriptBuffer .= "\n/* {$script} */\n" . $buffer;
						}
					}

					$fileName = _generateFileName($javascript).'_'.$ts.'.js';
					file_put_contents($cache_path.$fileName, $scriptBuffer);
				}

				$packed[] = $link.$fileName;
			}
		}

		return $packed;
	}
}

function _generateFileName($names)
{
	$file_name = md5(str_replace('.', '-', implode('_', $names)));
	return $file_name;
}

function _find($fileName)
{
	$cache_path = config_item('cache_path');
	$files = scandir($cache_path);
	$found = array();
	foreach ($files as $file)
	{
		if(preg_match("/^{$fileName}$/i", $file))
		{
			$found[] = $file;
		}
	}

	return $found;
}
