<?php	error_reporting(E_ALL);

	use setasign\Fpdi\Fpdi;
	
	function addresses()
	{
		global $db;
		
	}
	
	function pdf()
	{
		require_once('lib/classes/fpdf/fpdf.php');
		//pr('1');
		require_once('lib/classes/fpdi/autoload.php');
		//pr('2');
		$pdf = new Fpdi();
		//pr($pdf);
		
		$pdf->AddPage();
// set the source file
$pdf->setSourceFile('assets/documents/5165AT19 - G-12 sterktebeproeving (2020-07-17 09-40-27).pdf');
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 0, 0, 210);

// now write some text above the imported page
$pdf->SetFont('Helvetica');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFontSize(10.5);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetXY(180, 56.4);
$pdf->Cell(30, 5, '', 0, 0, 'L', 1);
$pdf->SetXY(180, 56.4);
$pdf->Cell(30, 5.5, '2394XL11', 0, 0, 'L', 0);

$pdf->Output('I', 'generated.pdf');
	}
	
	function upload()
	{
		
	}
	
	function read_file_from_zip()
	{
		global $controller;
		
		$controller['layout'] = null;
		
		//pr($controller['get']);
		
		$pathinfo = pathinfo($controller['get']['zip_filename']);
		if(strtolower($pathinfo['extension']) == 'pdf')
		{
			header("Content-type:application/pdf");
			print(file_get_contents($controller['get']['zip_filename']));
		}else
		{
			
			$zip = new ZipArchive();
			if($zip->open($controller['get']['zip_filename'], ZipArchive::CREATE) === true)
			{
				$fp = $zip->getStream($controller['get']['filename']);
		    if(!$fp) exit("failed\n");
				
				$contents = '';
		    while (!feof($fp)) {
		        $contents .= fread($fp, 2);
		    }
				
				header("Content-type:application/pdf");
				print($contents);
				
		    fclose($fp);
				
			}
		}
	}
	
	function removed_photos()
	{
		global $controller, $db;
		
		$start_dir = '/var/www/vhosts/drs-infra.nl/app.drs-infra.nl/photos/';
		$raw_contents = (array_slice(scandir($start_dir, SCANDIR_SORT_ASCENDING), 1));
		
		$data = array();
		
		foreach($raw_contents as $file_or_dir)
		{
			if($file_or_dir != '.' && $file_or_dir != '..')
			{
				if(is_dir($start_dir . $file_or_dir))
				{
					$data['dirs'][] = $file_or_dir;
					
					$dir_contents = (array_slice(scandir($start_dir . $file_or_dir . '/', SCANDIR_SORT_ASCENDING), 1));
					
					foreach($dir_contents as $file)
					{
						if($file != '.' && $file != '..')
						{
							if(!is_dir($start_dir . $file_or_dir . '/' . $file))
							{
								$pathinfo = pathinfo($file);
								$photo_id = (int)$pathinfo['basename'];
								
								$photo = $db->first('photos', $photo_id);
								
								$data['files'][] = array(
									'project_list_id' => (int)$file_or_dir,
									'filename' => $file,
									'photo_id' => $photo_id,
									'filetime' => filemtime($start_dir . $file_or_dir . '/' . $file),
									'filesize' => filesize($start_dir . $file_or_dir . '/' . $file),
									'removed' => $photo['Photo']['archived'] != '0000-00-00 00:00:00',
									'in_db' => ($photo !== false),
									'created' => $photo['Photo']['created']
								);
							}
						}
					}
					
				}else
				{
					$data['root_files'][] = $file_or_dir;
				}
			}
		}
		
		//pr($data);
		set('data', $data);
	}
	function _kb($bytes)
	{
		if($bytes <= 1023)
			return $bytes . ' B';
		
		if($bytes <= (1023 * 1023))
			return number_format($bytes / 1023, 1) . ' KB';
		
		if($bytes <= (1023 * 1023 * 1023))
			return number_format($bytes / (1023 * 1023), 1) . ' MB';
		
		return $bytes;
	}
	
	function zp()
	{
		$filename = 'files/dgt_reports(14) (1).zip';
		
		pr(file_exists($filename));
		
		$zip = new ZipArchive();
		pr($zip->open($filename));
		if($zip->open($filename) === true || true)
		{
			pr($zip->numFiles);
			for($i = 0; $i < $zip->numFiles; $i++)
			{
				$zip_filename = $zip->getNameIndex($i);
				
				$pathinfo = pathinfo($zip_filename);
				
				if(isset($pathinfo['extension']))
				{
					$ext = strtolower($pathinfo['extension']);
					if($ext == 'pdf')
					{
						if(substr($pathinfo['dirname'], 0, 1) != '_' && substr($pathinfo['basename'], 0, 1) != '_' && substr($pathinfo['basename'], 0, 1) != '.')
						{
							pr($pathinfo['dirname'] . '/' . $pathinfo['basename'] . ' (' . $zip_filename . ')');
						}
					}
				}
				
				
			}
		}else
		{
			pr('zip_open() ' . zip_open($filename));
		}
	}
	
?>