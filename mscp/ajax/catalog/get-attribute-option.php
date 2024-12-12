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

	$iIndex       = IO::intValue("Index");
	$iAttributeId = IO::intValue("AttributeId");
?>
				    <div id="Option<?= $iIndex ?>" class="option" style="cursor:move;">
				      <input type="hidden" name="Options[]" value="0" />

				      <table border="0" cellspacing="0" cellpadding="0" width="480">
				        <tr>
				          <td width="30" class="serial"><?= $iIndex ?>.</td>
				          <td width="220"><input type="text" name="txtOptions[]" id="txtOption<?= $iIndex ?>" value="" maxlength="100" size="25" class="textbox title" /></td>

				          <td width="200">
<?
	if ($iAttributeId == 4)
	{
?>
						    <select name="ddTypes[]" id="ddType<?= $iIndex ?>" class="type">
							  <option value="S">Standard</option>
							  <option value="C">Custom</option>
						    </select>
<?
	}
		
	else
	{
?>
						    <input type="file" name="filePicture<?= $iIndex ?>" id="filePicture<?= $iIndex ?>" value=""  size="15" class="textbox picture" style="width:90%;" />
<?
	}
?>
						  </td>

				          <td width="30" align="right"><button class="btnRemove" id="<?= $iIndex ?>">Remove</button></td>
				        </tr>
				      </table>

				      <div class="br10"></div>
				    </div>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>