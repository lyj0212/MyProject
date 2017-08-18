<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Image_lib extends CI_Image_lib
{
	private $_enlarge = FALSE;

	public function __construct($props = array())
	{
		parent::__construct($props);
	}

	public function get_enlarge()
	{
		return $this->_enlarge;
	}

	public function set_enlarge($value)
	{
		$this->_enlarge = (bool)$value;
	}

	/**
	 * Resize an image. If an aspects ratio is passed, also adjust the aspect ratio.
	 *
	 * Typical usage:
	 * // Create an array that holds the various image sizes
	 * $configs = array();
	 * $configs[] = array('source_image' => 'original.jpg', 'new_image' => 'thumbs/120/thumb.jpg', 'width' => 120, 'height' => 120);
	 * $configs[] = array('source_image' => 'original.jpg', 'new_image' => 'thumbs/120x120/thumb.jpg', 'width' => 120, 'height' => 120, 'maintain_ratio' => FALSE);
	 * $configs[] = array('source_image' => 'original.jpg', 'new_image' => 'thumbs/160x90/thumb.jpg', 'width' => 160, 'height' => 90, 'maintain_ratio' => FALSE);
	 * $configs[] = array('source_image' => 'original.jpg', 'new_image' => 'thumbs/240/thumb.jpg', 'width' => 240, 'height' => 240);
	 * $configs[] = array('source_image' => 'original.jpg', 'new_image' => 'thumbs/800/thumb.jpg', 'width' => 800, 'height' => 800);
	 *
	 * // Loop through the array to create thumbs
	 * $this->load->library('image_lib');
	 * foreach ($configs as $config) {
	 *    $this->image_lib->thumb($config, FCPATH . 'gfx/');
	 * }
	 *
	 * By default this lib does not resize an image if it is smaller than the new image should be (although this is CI's default behaviour).
	 * Instead, it saves a copy of the original file as the destination image. If you want to enlargen small images, set $this->_enlarge to TRUE, by
	 * typing $this_image_lib->set_enlarge(TRUE);
	 *
	 * @param array $config
	 * @param string $base_path
	 * @return void
	 * @author Joost van Veen
	*/
	public function thumb($config, $base_path = '')
	{
		// Set defaults
		#$config['image_library'] = 'imagemagick';
		#$config['library_path'] = '/usr/bin';
    $config['image_library'] = 'GD2';
    $config['library_path'] = '';
		$config['source_image'] = $base_path . $config['source_image'];
		$config['new_image'] = isset($config['new_image']) ? $base_path . $config['new_image'] : $config['source_image'];
		isset($config['width']) || $config['width'] = 120;
		isset($config['height']) || $config['height'] = 120;
		isset($config['maintain_ratio']) || $config['maintain_ratio'] = TRUE;

		$chk_ext = strtolower(substr(strrchr($config['source_image'], '.'), 1));
		if( ! in_array($chk_ext, array('jpg', 'gif', 'png')))
		{
			return $this->noimg($config['width'], $config['height']);
		}

		if(file_exists($config['new_image']))
		{
			return $config['new_image'];
		}

		$new_path = dirname($config['new_image']);
		if( ! is_dir($new_path))
		{
			mkdir($new_path, 0777, TRUE);
		}

		if($this->_enlarge == FALSE)
		{
			$source_image_data = $this->_get_image_size($config['source_image']);
			if ($source_image_data['width'] <= $config['width'] && $source_image_data['height'] <= $config['height'])
			{
				copy($config['source_image'], $config['new_image']);
				return $config['new_image'];
			}
		}

		do if ($config['maintain_ratio'] == FALSE)
		{
			if ($this->_enlarge == TRUE)
			{
				$source_image_data = $this->_get_image_size($config['source_image']);
			}
			$source_ratio = $source_image_data['width'] / $source_image_data['height'];
			$new_ratio = $config['width'] / $config['height'];

			$conf = array('image_library' => $config['image_library'], 'library_path' => $config['library_path'], 'source_image' => $config['source_image'], 'new_image' => $config['new_image'], 'maintain_ratio' => FALSE);

			if ($new_ratio == $source_ratio)
			{
				break;
			}
			elseif ($new_ratio > $source_ratio || ($new_ratio == 1 && $source_ratio < 1))
			{
				$conf['width'] = $source_image_data['width'];
				$conf['height'] = round($source_image_data['width'] / $new_ratio);
				$conf['y_axis'] = ($source_image_data['height'] - $conf['height']) / 2;
			}
			else
			{
				$conf['width'] = round($source_image_data['height'] * $new_ratio);
				$conf['height'] = $source_image_data['height'];
				$conf['x_axis'] = ($source_image_data['width'] - $conf['width']) / 2;
			}

			$this->initialize($conf);
			$this->crop();
			$this->clear();
			$config['source_image'] = $conf['new_image'];
		}
		while (false);

		$conf = array('image_library' => $config['image_library'], 'library_path' => $config['library_path'], 'source_image' => $config['source_image'], 'new_image' => $config['new_image'], 'maintain_ratio' => TRUE, 'width' => $config['width'], 'height' => $config['height']);
		$this->initialize($conf);
		$this->resize();
		$this->clear();

		return $config['new_image'];
	}

	private function _get_image_size($source_image)
	{
		$source_image_data = getimagesize($source_image);
		$source_image_data['width'] = $source_image_data[0];
		$source_image_data['height'] = $source_image_data[1];

		return $source_image_data;
	}

	// --------------------------------------------------------------------

	/**
	 * Watermark - Graphic Version
	 *
	 * @access	public
	 * @return	bool
	 */
	public function noimg($width, $height)
	{
		if ( ! function_exists('imagecolortransparent'))
		{
			$this->set_error('imglib_gd_required');
			return FALSE;
		}

		$this->width = $width;
		$this->height = $height;

		$config['new_image'] = sprintf('attach/files/noimg/%s_%s.gif', $this->width, $this->height);
		$noimg_path = dirname($config['new_image']);
		if( ! is_dir($noimg_path))
		{
			mkdir($noimg_path, 0777, TRUE);
		}

		if(file_exists($config['new_image']))
		{
			return $config['new_image'];
		}

		$config['image_library'] = 'gd2';
		$config['width'] = $width;
		$config['height'] = $height;
		$config['create_thumb'] = TRUE;
		$config['thumb_marker'] = '';
		$config['wm_overlay_path'] = '../_res/watermark/no_image.png';
		$config['source_image'] = $config['wm_overlay_path'];
		$config['wm_opacity'] = 100;
		$config['wm_vrt_alignment'] = 'middle';
		$config['wm_hor_alignment'] = 'center';
		$config['maintain_ratio'] = FALSE;
		$this->clear();
		$this->initialize($config);

		//  Fetch watermark image properties
		$props			= $this->get_image_properties($this->wm_overlay_path, TRUE);
		$wm_img_type	= $props['image_type'];
		$wm_width		= $props['width'];
		$wm_height		= $props['height'];

		$wm_img  = $this->image_create_gd($this->wm_overlay_path, $wm_img_type);

		/* Create a blank image */
		$src_img = imagecreate($this->width, $this->height);
		imagecolorallocate($src_img, 255, 255, 255);

		// Reverse the offset if necessary
		// When the image is positioned at the bottom
		// we don't want the vertical offset to push it
		// further down.  We want the reverse, so we'll
		// invert the offset.  Same with the horizontal
		// offset when the image is at the right

		$this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
		$this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

		if ($this->wm_vrt_alignment == 'B')
			$this->wm_vrt_offset = $this->wm_vrt_offset * -1;

		if ($this->wm_hor_alignment == 'R')
			$this->wm_hor_offset = $this->wm_hor_offset * -1;

		//  Set the base x and y axis values
		$x_axis = $this->wm_hor_offset + $this->wm_padding;
		$y_axis = $this->wm_vrt_offset + $this->wm_padding;

		//  Set the vertical position
		switch ($this->wm_vrt_alignment)
		{
			case 'T':
				break;
			case 'M':	$y_axis += ($this->height / 2) - ($wm_height / 2);
				break;
			case 'B':	$y_axis += $this->height - $wm_height;
				break;
		}

		//  Set the horizontal position
		switch ($this->wm_hor_alignment)
		{
			case 'L':
				break;
			case 'C':	$x_axis += ($this->width / 2) - ($wm_width / 2);
				break;
			case 'R':	$x_axis += $this->width - $wm_width;
				break;
		}

		//  Build the finalized image
		if ($wm_img_type == 3 AND function_exists('imagealphablending'))
		{
			@imagealphablending($src_img, TRUE);
		}

		// Set RGB values for text and shadow
		$rgba = imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp);
		$alpha = ($rgba & 0x7F000000) >> 24;

		// make a best guess as to whether we're dealing with an image with alpha transparency or no/binary transparency
		if ($alpha > 0)
		{
			// copy the image directly, the image's alpha transparency being the sole determinant of blending
			imagecopy($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height);
		}
		else
		{
			// set our RGB value from above to be transparent and merge the images with the specified opacity
			imagecolortransparent($wm_img, imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp));
			imagecopymerge($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height, $this->wm_opacity);
		}

		//  Output the image
		if ($this->dynamic_output == TRUE)
		{
			$this->image_display_gd($src_img);
		}
		else
		{
			if ( ! $this->image_save_gd($src_img))
			{
				return FALSE;
			}
		}

		imagedestroy($src_img);
		imagedestroy($wm_img);

		return $config['new_image'];
	}

}
