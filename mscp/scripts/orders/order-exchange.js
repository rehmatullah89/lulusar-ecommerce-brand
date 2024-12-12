
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

$(document).ready(function( )
{
	$("#frmRecord #BtnAdd").button({ icons:{ primary:'ui-icon-plus' } });


	
	$("#frmRecord").submit(function( )
	{
		var objFV     = new FormValidator("frmRecord", "RecordMsg");
		var bReturned = false;

		
		$("#OriginalCart .product .ddQuantity").each(function( )
		{
			if ($(this).val( ) > 0)
			{
				bReturned = true;
				
				return false;
			}
		});
		
		
		if (bReturned == false)
		{
			showMessage("#RecordMsg", "info", "Please select a Returned Product.");
			
			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
	
	
	
	$("#BtnAdd").click(function( )
	{
		if (parseInt($("#Quantity").val( )) == 0)
		{
			$("#Quantity").focus( );

			return false;
		}

		else
		{
			var bExists = false;

			$("#Cart .txtProduct").each(function(iIndex)
			{
				if ($(this).val( ) == (objProduct.id + "," + objProduct.option1_id + "," + objProduct.option2_id + "," + objProduct.option3_id))
					bExists = true;
			});


			if (bExists == true)
			{
				$("#Product").val("");
				$("#Quantity").html("");
				$("#Quantity").get(0).options[0] = new Option("-", "0", false, false);

				return false;
			}
			


			$("#Cart .none").hide( );

			var iIndex    = $("#Cart .product").length;
			var sProduct  = '';
			var fPrice    = parseFloat(objProduct.price.replace(",", ""));
			var fDiscount = 0;

			if ($("#Quantity").val( ) >= objProduct.orderQty)
				fDiscount = Math.floor(($("#Quantity").val( ) / objProduct.orderQty) * objProduct.discount);
			
			if (isNaN(fDiscount))
				fDiscount = 0;
			

			sProduct += ('<div class="product" id="Product' + iIndex + '">');
			sProduct += ('<input type="hidden" name="txtProduct[]" id="txtProduct' + iIndex + '" value="' + objProduct.id + "," + objProduct.option1_id + "," + objProduct.option2_id + "," + objProduct.option3_id + '" class="txtProduct" />')
			sProduct += '<table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">';
			sProduct += '<tr class="' + (((iIndex % 2) == 0) ? 'even' : 'odd') + '" valign="top">';
			sProduct += '<td width="44%">'

			sProduct += '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			sProduct += '<tr valign="top">';
			sProduct += ('<td width="70"><div style="float:left; border:solid 1px #888888; padding:1px;"><a href="' + objProduct.url + '" target="_blank"><img src="' + objProduct.picture + '" width="48" height="78" alt="" title="" /></a></div></td>');
			sProduct += '<td>';
			sProduct += ('<b><a href="' + objProduct.url + '" target="_blank">' + objProduct.product + '</a></b><br />');
			sProduct += ('<small>Code: ' + objProduct.code + '</small><br />');

			if (parseInt(objProduct.option1_id) > 0)
				sProduct += ('<small>- ' + objProduct.attribute1 + ': ' + objProduct.option1 + '</small><br />');

			if (parseInt(objProduct.option2_id) > 0)
				sProduct += ('<small>- ' + objProduct.attribute2 + ': ' + objProduct.option2 + '</small><br />');
			
			if (parseInt(objProduct.option3_id) > 0)
				sProduct += ('<small>- ' + objProduct.attribute3 + ': ' + objProduct.option3 + '</small><br />');

			sProduct += '</td>';
			sProduct += '</tr>';
			sProduct += '</table>';

			sProduct += '</td>';

			sProduct += ('<td width="8%" align="center"><button class="btnRemove" id="' + iIndex + '">Remove</button></td>');
			sProduct += ('<td width="12%" align="center"><select name="ddQuantity[]" id="ddQuantity' + iIndex + '" class="ddQuantity" discount="' + objProduct.discount + '" orderQty="' + objProduct.orderQty + '">');

			for (var i = 1; i <= parseInt(objProduct.quantity); i ++)
				sProduct += ('<option value="' + i + '"' + ((i == $("#Quantity").val( )) ? " selected" : "") + '>' + i + '</option>');

			sProduct += '</select></td>';

			sProduct += ('<td width="12%" align="right"><input type="text" name="txtPrice[]" id="txtPrice' + iIndex + '" value="' + fPrice.toFixed(0) + '" size="6" maxlength="8" readonly class="textbox txtPrice" /></td>');
			sProduct += ('<td width="12%" align="right"><input type="text" name="txtDiscount[]" id="txtDiscount' + iIndex + '" value="' + fDiscount.toFixed(0) + '" size="6" maxlength="8" class="textbox txtDiscount" /></td>');
			sProduct += ('<td width="12%" align="right">' + $("#Currency").val( ) + ' <span id="SubTotal' + iIndex + '">' + ((fPrice * parseInt($("#Quantity").val( ))) - fDiscount).formatNumber( ) + '</span></td>');
			sProduct += '</tr>';
			sProduct += '</table>';
			sProduct += '</div>';

			
			$("#Cart").append(sProduct);


			$("#Product").val("");
			$("#Quantity").html("");
			$("#Quantity").get(0).options[0] = new Option("-", "0", false, false);

			
			updateTax( );
			updateTotal( );
		}


		$("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false }).attr("disabled", false);


		objProduct = null;

		return false;
	});
	
	
	$(document).on("click", ".btnRemove", function( )
	{
		var iIndex = $(this).attr("id");

		$("#Cart #Product" + iIndex).remove( );

		if ($("#Cart .product").length == 0)
			$("#Cart .none").show( );

		else
		{
			$("#Cart .product").each(function(iIndex)
			{
				$(this).find(".txtProduct").attr("id", ("txtProduct" + (iIndex + 1)));
				$(this).find(".txtPrice").attr("id", ("txtPrice" + (iIndex + 1)));
				$(this).find(".ddQuantity").attr("id", ("ddQuantity" + (iIndex + 1)));
				$(this).find(".btnRemove").attr("id", (iIndex + 1));
			});
		}
		

		updateTax( );
		updateTotal( );

		return false;
	});
	
	
	$(document).on("change", "#Cart .ddQuantity", function( )
	{
		var iIndex    = $(this).attr("id").replace("ddQuantity", "");
		var fDiscount = $(this).attr("discount");
		var iOrderQty = $(this).attr("orderQty");
		var iCartQty  = $(this).val( );
		
		if (fDiscount > 0)
		{
			if (iCartQty >= iOrderQty)
				fDiscount = Math.floor((iCartQty / iOrderQty) * fDiscount);			
			
			else
				fDiscount = 0;
			
			$("#Cart #txtDiscount" + iIndex).val(fDiscount.toFixed(0));
		}
		
		
		updateTax( );
		updateTotal( );
	});
	
	
	$(document).on("change", ".ddQuantity, .txtPrice, .txtDiscount", function( )
	{
		updateTax( );
		updateTotal( );
	});
	
	
	$(document).on("blur", "#txtCouponDiscount", function( )
	{	
		var iCouponDiscount     = parseInt($("#txtCouponDiscount").attr("rel"));
		var iAdjustableDiscount = parseInt($("#txtCouponDiscount").val( ));

		
		if (isNaN(iCouponDiscount))
			iCouponDiscount = 0;

		if (isNaN(iAdjustableDiscount))
			iAdjustableDiscount = 0;
		
		
		if (iCouponDiscount > 0)
		{
			if (iAdjustableDiscount > iCouponDiscount || iAdjustableDiscount < 0)
			{
				$("#txtCouponDiscount").val("0");
				$("#txtAdjustableCoupon").val("0");
			}
			
			else
				$("#txtAdjustableCoupon").val(iAdjustableDiscount);
		}
	});
	
	
	$(document).on("blur", "#txtPromotionDiscount", function( )
	{	
		var iPromotionDiscount   = parseInt($("#txtPromotionDiscount").attr("rel"));
		var iAdjustablePromotion = parseInt($("#txtPromotionDiscount").val( ));

		
		if (isNaN(iPromotionDiscount))
			iPromotionDiscount = 0;

		if (isNaN(iAdjustablePromotion))
			iAdjustablePromotion = 0;
		
		
		if (iPromotionDiscount > 0)
		{
			if (iAdjustablePromotion > iPromotionDiscount || iAdjustablePromotion < 0)
			{
				$("#txtPromotionDiscount").val("0");
				$("#txtAdjustablePromotion").val("0");
			}
			
			else
				$("#txtAdjustablePromotion").val(iAdjustablePromotion);
		}
	});	
	
	
	$(document).on("blur", "#txtDeliveryCharges, #txtTax, #txtCouponDiscount, #txtPromotionDiscount", function( )
	{	
		updateTotal( );
	});


	$(document).on("focus", "#Product", function( )
	{
		$(this).autocomplete(
		{
		    minLength  :  2,
		    source     :  "ajax/orders/get-order-products.php",

		    select     :  function(event, ui)
						  {
								objProduct = ui.item;

								$(this).val(ui.item.product + ((ui.item.sku == "") ? "" : (" (" + ui.item.sku + ")")));


								var iQuantity = parseInt(ui.item.quantity);

								$("#Quantity").html("");

								if (iQuantity == 0)
									$("#Quantity").get(0).options[0] = new Option("Out of Stock", "0", false, false);

								else
								{
									for (var i = 1; i <= iQuantity; i ++)
										$("#Quantity").get(0).options[(i - 1)] = new Option(i, i, false, false);
								}


								return false;
						  }
		}).data("ui-autocomplete")._renderItem = function(ul, item)
		{
		    return $("<li>")
				.append("<a style='display:block; height:78px; cursor:pointer; padding-right:10px;'><img src='" + item.picture + "' width='48' height='78' alt='' title='' align='left' style='margin:0px 8px 2px 0px;' /><b>" + item.product + " (Stock: " + item.quantity + ")" + "</b><br />" + ((item.option1 != "") ? item.option1 : "") + ((item.option1 != "" && item.option2 != "") ? " - " : "") + ((item.option2 != "") ? item.option2 : "") + ((item.option2 != "" && item.option3 != "") ? " - " : "") + ((item.option3 != "") ? item.option3 : "") + ((item.option1 != "" || item.option2 != "" || item.option3 != "") ? " / " : "") + item.type + " / " + item.sku + " (" + $("#Currency").val( ) + " " + item.price + ")<br />" + item.category + "</a></div>" )
				.appendTo(ul);
		};
	}).on("blur", "#Product", function( )
	{
		if ($(this).hasClass("ui-autocomplete-input"))
			$(this).autocomplete("destroy");
	}).on("keydown", "#Product", function(e)
	{
		if (e.which == 8 || e.which == 46)
		{
			$("#Product").val("");

			$("#Quantity").html("");
			$("#Quantity").get(0).options[0] = new Option("-", "0", false, false);

			objProduct = null;
		}
	});
});


function updateTax( )
{
	var iProducts          = parseInt($("#Cart .product").length);
	var fCouponDiscount    = parseFloat($("#txtCouponDiscount").val( ));	
	var fPromotionDiscount = parseFloat($("#txtPromotionDiscount").val( ));
	var sTaxType           = $("#txtTax").attr("taxType");
	var fTaxRate           = parseFloat($("#txtTax").attr("taxRate"));
	var fTotal             = 0;
	var fTax               = 0;

	
	if (isNaN(fCouponDiscount))
		fCouponDiscount = 0;
	
	if (isNaN(fPromotionDiscount))
		fPromotionDiscount = 0;
	

	for (var i = 0; i < iProducts; i ++)
	{
		var iQuantity = parseInt($("#Cart #ddQuantity" + i).val( ));
		var fPrice    = parseFloat($("#Cart #txtPrice" + i).val( ));
		var fDiscount = parseFloat($("#Cart #txtDiscount" + i).val( ));
		
		if (isNaN(iQuantity))
			iQuantity = 0;
		
		if (isNaN(fPrice))
			fPrice = 0;
		
		if (isNaN(fDiscount))
			fDiscount = 0;
		
		fTotal += (iQuantity * fPrice);
		
		if (iQuantity > 0)
			fTotal -= fDiscount;
	}
	
	
	if (sTaxType == "P")
		fTax = Math.floor((fTotal / (100 + fTaxRate)) * fTaxRate);
		//fTax = Math.round((fTotal / 100) * fTaxRate);
		
	else
		fTax = fTaxRate;
	
	
	$("#txtTax").val(fTax.toFixed(0))
}


function updateTotal( )
{
	var iOriginalProducts         = parseInt($("#OriginalCart .product").length);
	var iNewProducts              = parseInt($("#Cart .product").length);
	var fTax                      = parseFloat($("#txtTax").val( ));
	var fDeliveryCharges          = parseFloat($("#txtDeliveryCharges").val( ));
	var fCouponDiscount           = parseFloat($("#txtCouponDiscount").attr("rel"));	
	var fCouponDiscountRevoked    = parseFloat($("#txtCouponDiscount").val( ));	
	var fAdjustableDiscount       = parseFloat($("#txtAdjustableCoupon").val( ));	
	var fPromotionDiscount        = parseFloat($("#txtPromotionDiscount").attr("rel"));
	var fPromotionDiscountRevoked = parseFloat($("#txtPromotionDiscount").val( ));
	var fAdjustablePromotion      = parseFloat($("#txtAdjustablePromotion").val( ));	
	var fAdjustableAmount         = 0;
	var fTotal                    = 0;

	
	if (isNaN(fDeliveryCharges))
		fDeliveryCharges = 0;
	
	if (isNaN(fTax))
		fTax = 0;
	
	if (isNaN(fCouponDiscount))
		fCouponDiscount = 0;
	
	if (isNaN(fCouponDiscountRevoked))
		fCouponDiscountRevoked = 0;
	
	if (isNaN(fAdjustableDiscount))
		fAdjustableDiscount = 0;
	
	if (isNaN(fPromotionDiscount))
		fPromotionDiscount = 0;
	
	if (isNaN(fPromotionDiscountRevoked))
		fPromotionDiscountRevoked = 0;
	
	if (isNaN(fAdjustablePromotion))
		fAdjustablePromotion = 0;
	

	for (var i = 0; i < iOriginalProducts; i ++)
	{
		var iOrderQty = parseInt($("#OriginalCart #ddQuantity" + i).attr("rel"));
		var iQuantity = parseInt($("#OriginalCart #ddQuantity" + i).val( ));
		var fPrice    = parseFloat($("#OriginalCart #txtPrice" + i).val( ));
		var fDiscount = parseFloat($("#OriginalCart #txtDiscount" + i).val( ));
		
		if (isNaN(iQuantity))
			iQuantity = 0;
		
		if (isNaN(fPrice))
			fPrice = 0;
		
		if (isNaN(fDiscount))
			fDiscount = 0;


		var fSubTotal = (iQuantity * fPrice);
		
		if (fDiscount > 0)
		{
			if (iQuantity < iOrderQty)
			{
				var fPerPieceDiscount = Math.round(fDiscount / iOrderQty);
			
				fSubTotal -= (fPerPieceDiscount * iQuantity);
			}
			
			else
				fSubTotal -= fDiscount;
		}
		
		
		$("#OriginalCart #SubTotal" + i).html(fSubTotal.formatNumber( ));
		

		fAdjustableAmount += fSubTotal;
	}
	

	fAdjustableAmount -= fCouponDiscountRevoked;
	fAdjustableAmount -= fPromotionDiscountRevoked;

	
	if ($("#txtCouponDiscount").length == 1)
		$("#txtCouponDiscount").val(fCouponDiscountRevoked.toFixed(0));

	if ($("#txtPromotionDiscount").length == 1)
		$("#txtPromotionDiscount").val(fPromotionDiscountRevoked.toFixed(0));

	$("#AdjustableAmount").html(fAdjustableAmount.formatNumber( ));	
	

	
	
	for (var i = 0; i < iNewProducts; i ++)
	{
		var iQuantity = parseInt($("#Cart #ddQuantity" + i).val( ));
		var fPrice    = parseFloat($("#Cart #txtPrice" + i).val( ));
		var fDiscount = parseFloat($("#Cart #txtDiscount" + i).val( ));
		
		if (isNaN(iQuantity))
			iQuantity = 0;
		
		if (isNaN(fPrice))
			fPrice = 0;
		
		if (isNaN(fDiscount))
			fDiscount = 0;
		
		
		var fSubTotal = (iQuantity * fPrice);
		
		if (iQuantity > 0)
			fSubTotal -= fDiscount;


		$("#Cart #SubTotal" + i).html(fSubTotal.formatNumber( ));
		

		fTotal += fSubTotal;
	}

	
	$("#SubTotal").html(fTotal.formatNumber( ));	
	
	if ($("#txtTax").length == 1)
		$("#txtTax").val(fTax.toFixed(0));
	
	if ($("#txtDeliveryCharges").length == 1)
		$("#txtDeliveryCharges").val(fDeliveryCharges.toFixed(0));

	
	fTotal -= fAdjustableDiscount;
	fTotal -= fAdjustablePromotion;
	fTotal -= fAdjustableAmount;
	fTotal += fDeliveryCharges;
//	fTotal += fTax;


	$("#BalanceAmount").html(fTotal.formatNumber( ));
	
	
	if (fTotal >= 0)
	{
		$("#CreditNote").hide( ).val("");
		$("#BtnSave").find("span").text("Create Order");
	}
	
	else
	{
		$("#CreditNote").show( );
		$("#BtnSave").find("span").text("Update Order / Add Credit");
	}
}