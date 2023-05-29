<?php

	if(include('lib/classes/SimpleXLSX.php'))
	{
		if($xlsx = SimpleXLSX::parse('files/projectlijst.xlsx'))
		{
			//pr($xlsx->rows());
		}
	}

?>
