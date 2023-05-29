<?php
	class image
	{

		function image()
		{
			$this->image = null;
			$this->temp_image = 'temp';
			$this->ext = 'jpg';
			$this->tmp_name = '';
			$this->output_ext = null;
			$this->thumbs = array();
			$this->errors = array();
			$this->i = 0;
			$this->filename = 'Unknown';
			$this->transparentcy = false;
		}

		function thumb($width = 100, $height = 100, $output_url = null, $data = array())
		{
			$cutout = 'crop';
			foreach($data as $dat => $inner_data)
				switch($dat)
				{
					case 'cutout' :
						$cutout = $inner_data;
						break;
				}
			if(is_null($output_url))
				$output_url = time() . $this->i;
			$output_url = substr($this->temp_image, 0, strlen($this->temp_image) - strlen(end(explode('/', $this->temp_image)))) . $output_url;
			switch($this->ext)
			{
				case 'png' :
					$gd_image = @imagecreatefrompng($this->temp_image . '.' . $this->ext);
					break;
				case 'gif' :
					$gd_image = @imagecreatefromgif($this->temp_image . '.' . $this->ext);
					break;
				default :
					$gd_image = @imagecreatefromjpeg($this->temp_image . '.' . $this->ext);
					break;
			}
			if(!$gd_image)
				$this->errors[] = 'Error creating GD image.';
			else
				imagealphablending($gd_image, true);
			switch($cutout)
			{
				case 'max' :
					$r_width = $this->width;
					$r_height = $this->height;
					if($r_width > $width)
					{
						$r_height = $r_height / ($r_width / $width);
						$r_width = $width;
					}
					if($r_height > $height)
					{
						$r_width = $r_width / ($r_height / $height);
						$r_height = $height;
					}
					$gd_thumb = @imagecreatetruecolor($r_width, $r_height);
					imageantialias($gd_thumb, true);
					$white = ImageColorAllocate($gd_thumb, 255, 255, 255);
					ImageFilledRectangle($gd_thumb, 0, 0, $r_width, $r_height, $white);
					if(!$gd_thumb)
						$this->errors[] = 'Error creating GD thumb.';
					$imagecopy = @imagecopyresized($gd_thumb, $gd_image, 0, 0, 0, 0, $r_width, $r_height, $this->width, $this->height);
					break;
				case 'test' :
					$gd_thumb = @imagecreatetruecolor($width, $height);
					imagealphablending($gd_thumb, true);

					$fict_width = $width;
					$multiplier = $fict_width / $this->width;
					$fict_height = $this->height * $multiplier;

					if($fict_height >= $height)
					{
						//gaat goed
						$x = 0;
						$difference = $fict_height - $height;
						$y = $difference / 2;
						$y = $y / $multiplier;
					}else
					{
						//andersom
						$fict_height = $height;
						$multiplier = $fict_height / $this->height;
						$fict_width = $this->width * $multiplier;

						$y = 0;
						$difference = $fict_width - $width;
						$x = $difference / 2;
						$x = $x / $multiplier;
					}
					$x = round($x);
					$y = round($y);
					$fict_width = round($fict_width);
					$fict_height = round($fict_height);

					$this->preserveAlpha($gd_image);

					if($this->transparentcy === true)
					{
						$white = ImageColorAllocate($gd_thumb, 255, 255, 255);
						ImageColorTransparent($gd_thumb, $white);
						ImageFilledRectangle($gd_thumb, 0, 0, $width, $height, $white);
					}

					$imagecopy = @imagecopyresampled($gd_thumb, $gd_image, 0, 0, $x, $y, $fict_width, $fict_height, $this->width, $this->height);
				break;
				default :
					$gd_thumb = @imagecreatetruecolor($width, $height);
					imageantialias($gd_thumb, true);
					$white = ImageColorAllocate($gd_thumb, 255, 255, 255);
					ImageColorTransparent($gd_thumb, $white);
					ImageFilledRectangle($gd_thumb, 0, 0, $width, $height, $white);
					$r_width = $width;
					$r_height = $this->height / ($this->width / $width); // 800 / (600 / 100)
					$r_top = ((($r_height - $height) / 2));
					$r_top *= ($this->width / $width);
					$r_left = 0;
					if($r_height < $height)
					{
						$r_height = $height;
						$r_width = $this->width / ($this->height / $height);
						$r_top = 0;
						$r_left = (floor(($r_width - $width) / 2)); //(110 - 100) / 2
					}
					$imagecopy = @imagecopyresized($gd_thumb, $gd_image, 0, 0, $r_left, $r_top, $r_width, $r_height, $this->width, $this->height);
			}


			//imagecopyresized($gd_thumb, $gd_image, $r_left, $r_top, 0, 0, $r_width, $r_height, $this->width, $this->height)
			if(!$imagecopy)
				$this->errors[] = 'Failed to resize image, imagecopyresized()';
			$try = false;
			if(is_null($this->output_ext))
				$this->output_ext = $this->ext;
			switch($this->output_ext)
			{
				case 'png' :
					$try = @imagepng($gd_thumb, $output_url . '.' . $this->output_ext);
					break;
				case 'gif' :
					$try = @imagegif($gd_thumb, $output_url . '.' . $this->output_ext);
					break;
				default :
					$try = @imagejpeg($gd_thumb, $output_url . '.' . $this->output_ext, 100);
					break;
			}
			if(!$try)
				$this->errors[] = 'Failed to save image: ' . $output_url . '.' . $this->output_ext;
			$this->i++;
		}

		function upload($image = null)
		{
			if(is_null($image))
				$this->errors[] = 'Missing argument, upload(mixed $image)';
			$this->image = $image;
			if(is_array($image))
				if(empty($image['tmp_name']))
					$this->errors[] = 'Invalid value for first argument, key \'tmp_name\' not found, upload(array $image)';
				else
					$this->tmp_name = $image['tmp_name'];
			else
				$this->errors[] = 'Invalid value for first argument, upload(array $image)';
			if(empty($this->tmp_name))
				$this->errors[] = '$this->tmp_name is empty.';
			$this->init();
			if(empty($this->ext))
				$this->errors[] = '$this->ext is empty.';
			if(!is_uploaded_file($this->tmp_name))
				$this->errors[] = 'No file uplaoded.';
			if(!@move_uploaded_file($this->tmp_name, $this->temp_image . '.' . $this->ext))
				$this->errors[] = 'Failed to upload image: move_uploaded_file(' . $this->tmp_name . ', ' . $this->temp_image . '.' . $this->ext . ')';
			//ff orgineel bewaren voor op de beamert
			//@copy($this->temp_image . '.' . $this->ext, 'photos/' . time() . '.' . $this->ext);
		}

		function init()
		{
			$this->filename = $this->image['name'];
			$dimensions = getimagesize($this->tmp_name);
			$this->width = $dimensions[0];
			$this->height = $dimensions[1];
			$this->ext = strtolower(end(explode('.', $this->image['name'])));
			if(substr($this->ext, 0, 3) == 'jpe')
				$this->ext =  'jpg';
		}

		function destroy()
		{
			if(!@unlink($this->temp_image . '.' . $this->ext))
				$this->errors[] = 'Failed to destroy image: unlink(' . $this->temp_image . '.' . $this->ext . ')';
		}

		function errors()
		{
			if(count($this->errors) > 0)
			{
				print('<pre>');
					print_r($this->errors);
				print('</pre>');
			}
		}

		function r($return)
		{
			$return['errors'] = $this->errors;
			return $return;
		}

		function useImage($url = '')
		{
			$ext = end(explode('.', $url));
			$this->temp_image = substr($url, 0, -((strlen($ext)+1)));
			$this->ext = $ext;
			$dims = getimagesize($url);
			$this->width = $dims[0];
			$this->height = $dims[1];
		}

		function preserveAlpha($img)
		{
			imagealphablending($img, false);

			$colorTransparent = imagecolorallocatealpha
			(
				$img,
				255,
				255,
				255,
				0
			);

			imagefill($img, 0, 0, $colorTransparent);
			imagesavealpha($img, true);
		}

	}
?>
