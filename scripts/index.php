<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Lulusar                                                                                  **
	**  Version 1.0                                                                              **
	**                                                                                           **
	**  http://www.lulusar.com                                                                   **
	**                                                                                           **
	**  Copyright 2005-16 (C) SW3 Solutions                                                      **
	**  http://www.sw3solutions.com                                                              **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtshahzad@sw3solutions.com                                                  **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@ob_start( );

	@ini_set('display_errors', 0);
	//@error_reporting(E_ALL);

	@header("Content-type: text/javascript; charset=UTF-8");


	@require_once("../requires/jsmin.class.php");
	@require_once("files.php");


	foreach($sFiles as $sFile)
	{
		if ($sFile["Minified"] == FALSE)
		{
			if ($sFile["Name"] == "common.js")
			{
?>
				var sAbout = "%%%%%Dgjpq%?%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%///////%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%O%Hjaj%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%Fju|wlbmq%7550(46%�%VR6%Vjipqljkv%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%A`s`iju`w%?%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%///////////%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%Kdh`%?%Hpmdhhda%Qdmlw%Vmdmda%%%%%%%%%%%%%%%%%%%%%@hdli%%?%hqdmlwvmdmdaEmjqhdli+fjh%%%%%%%%%%%%%%%%PWI%%%%?%mqqu?**rrr+hqvmdmda+fjh%%%%%%%%%%%%";
<?
			}

			print JSMin::minify(@file_get_contents($sFile["Name"]));
		}

		else
			print @file_get_contents($sFile["Name"]);
	}



	$hFile = @fopen("default.js", "w");

	@fwrite($hFile, @ob_get_contents( ));
	@fclose($hFile);


	@ob_end_flush( );
?>