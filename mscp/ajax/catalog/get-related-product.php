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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iIndex = IO::intValue("Index");
?>
				    <div id="Product<?= $iIndex ?>" class="product">
				      <input type="hidden" name="Product[]" value="0" />

				      <table border="0" cellspacing="0" cellpadding="0" width="350">
				        <tr>
				          <td width="30" class="serial"><?= $iIndex ?>.</td>
				          <td><input type="text" name="txtProducts[]" id="txtProducts<?= $iIndex ?>" value="" maxlength="100" size="38" class="textbox" /></td>
				          <td width="50" align="right"><button class="btnRemove" id="<?= $iIndex ?>">Remove</button></td>
				        </tr>
				      </table>

				      <div class="br10"></div>
				    </div>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>