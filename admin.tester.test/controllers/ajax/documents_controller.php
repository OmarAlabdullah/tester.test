<?php

use setasign\Fpdi\Fpdi;

function remove()
{
	global $controller, $db;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		$document_ids = $controller['post']['document_ids'];
		
		$return['document_ids'] = $document_ids;
		
		foreach($document_ids as $document_id)
		{
			$document = $db->first('documents', (int)$document_id);
			if($document)
			{
				$return['succes'] = true;
				
				$document['Document']['archived'] = date('Y-m-d H:i:s');
				$db->update($document);
			}
		}
		
		print(json_encode($return));
}

function set_dgt_zipcode_on_pdf($document_id = 0)
{
	$return = array(
		'succes' => false,
		'error' => false
	);
	
	$document_id = (int)$document_id;
	if($document_id > 0)
	{
		global $db;
		
		$document = $db->first('documents', $document_id);
		
		if($document['Document']['client_id'] > 0)
		{
			$client = $db->first('clients', (int)$document['Document']['client_id']);
			
			if($client)
			{
				$new_zipcode_number = strtoupper(str_replace(' ', '', $client['Client']['zipcode'] . $client['Client']['homenumber'] . $client['Client']['addition']));
				$new_filename = $document['Document']['filename'];
				if(stristr($new_filename, '-'))
				{
					$expl = explode('-', $new_filename);
					array_shift($expl);
					$new_filename = implode('-', $expl);
				}
				$new_filename = $new_zipcode_number . ' -' . $new_filename;
				$dest = 'assets/documents/dgt/' . $document['Document']['project_list_id'] . '/' . $new_filename;
				$return['dest'] = $dest;
				if(_overwrite_zipcode_on_pdf('assets/documents/dgt/' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename'], $new_zipcode_number, $dest))
				{
					$document['Document']['filename'] = $new_filename;
					$db->update($document);
					$return['succes'] = true;
				}else
					$return['error'] = 'Kon pdf niet aanpassen (overwrite failed)';
				
			}else
				$return['error'] = 'Adres niet gevonden (client_id not found)';
		}else
			$return['error'] = 'Document heeft geen adres gekoppeld (no client_id)';
	}else
		$return['error'] = 'Document niet gevonden (document_id not found)';
	
	print(json_encode($return));
}

function _overwrite_zipcode_on_pdf($pdf_src = '', $zipcode = '', $dest = '')
{
	if(strlen($pdf_src) > 0)
	{
		require_once('lib/classes/fpdf/fpdf.php');
		require_once('lib/classes/fpdi/autoload.php');
		$pdf = new Fpdi();
		
		$pdf->AddPage();
		$pdf->setSourceFile($pdf_src);
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx, 0, 0, 210);
		
		$pdf->SetFont('Helvetica');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFontSize(10.5);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetXY(180 - 2, 56.4);
		$pdf->Cell(30, 5, '', 0, 0, 'L', 1);
		$pdf->SetXY(180 - 2, 56.4);
		$pdf->Cell(30, 5.5, $zipcode, 0, 0, 'L', 0);
		
		if(strlen($dest) >= 1)
		{
			$pdf->Output('F', $dest);
			return true;
		}else
			$pdf->Output('I', 'generated.pdf');
	}
	
	return false;
}

?>