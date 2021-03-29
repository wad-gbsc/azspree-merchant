<!DOCTYPE html>
<html>
    <head>
        <title>Statement of Account</title>
        
      <style type="text/css">
            body{
			font-family: calibri;
		}
		.qrcode{
			width: 100px;
			height: 100px;
		}
        .logodh{
			width: auto;
			height: 50px;
		}
		/* td.header{
			text-transform: uppercase;
			font-Total Kg: bold;
			font-size: 15pt;
		}*/
		.field{
            font-size: 16px; 
            text-transform: capitalize;
            text-align: justify;
            font-weight: bold;
		}
		 .top{
			border-top: 1px solid black;
            margin-top: 10%;
            margin-bottom: 10%;
		}
		table td.report_header{
			font-size: 26px;
		}
        .track{
            text-align: center;
        }
        </style>
    </head>
    <body>

        <table width="100%" >
        <tr class="border">
			<td colspan="4" class="header report_header" style="font-size: 24pt; font-family: Times new roman;">
                <img src="brands_try/azspreelogo.png" class="logodh">
            </td>
        </tr> 
        </table>
        <table width="100%" >
            <tr class="border">
                <td colspan="12" class="header report_header" style="font-size: 14pt; font-family: Times new roman;">
                    <center><b>Statement of Account</b></center>
                </td>
                <br><br>
            </tr>
            <tr class="border">
                <td class="border" colspan="10">
                    @foreach ($issuances as $issuance)
                        
                    
                        <span>Send To.</span>
                        <br>
                        <span class="field">Name: {{$issuance->seller_name}}</span>
                        <br>
                        <span>Account Name: {{$issuance->bank_account_name}}</span>
                        <br>
                        <span>Bank Account No.: {{$issuance->bank_account_no}}</span>
                        <br>
                        <span>Bank Name & Branch: {{$issuance->bank_name}}</span>
                        <br>
                        <span>&nbsp;</span>
                    @endforeach
                </td>
                <td  class="border" colspan="2" style="text-align:right;">
                    <span>Date Prepared :  {{date("Y-m-d")}}</span>
                    <br>
                    <span><?php date_default_timezone_set("Asia/Manila"); echo "Time Prepared :" . date("h:i:sa"); ?></span>
                    <br>
                    <span></span>
                    <br>
                    <span></span>
                    <br>
                    <span></span>
                    <br>
                    <span>&nbsp;</span>
                </td>
            </tr>
        </table>

        </div>
        
        <table width="100%" >
            <tr>
                <td colspan="6">
                    <span class="field">product name</span>
                    <br><br>
                </td>
                <td colspan="1">
                    <span class="field">Quantity</span>
                    <br><br>
                </td>
                <td colspan="2" style="text-align: right;">
                    <span class="field">unit cost</span>
                    <br><br>
                </td> 
                <td colspan="3" style="text-align: right;">
                    <span class="field">amount</span>
                    <br><br>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="top"></td>
            </tr>
            <tr>
                @foreach ($details as $detail)
                    <?php 
                    $transaction_fee = 0;
                    $transaction_fee += $detail->azspree; 
                    ?>
                @endforeach
                @foreach($invoices as $in)
                   <?php
                   $subtotal = 0;
                   $shipping = 0;
                   $amount = $in->qty * $in->unit_price;
                   $subtotal += $amount;
                   $shipping += $in->m_shipping_fee;
                   ?>
                <td colspan="6">
                    <span>{{$in->product_name}}</span>
                    <br><br><br><br>
                </td>
                <td colspan="1">
                    <span>{{$in->qty}}</span>
                    <br><br><br><br>
                </td>
                <td colspan="2" style="text-align: right;">
                    <span>{{number_format($in->unit_price,2)}}</span>
                    <br><br><br><br>
                </td>
                <td colspan="3" style="text-align: right;">
                    <span>{{number_format($amount,2)}}</span>
                    <br><br><br><br>
                </td>
                @endforeach
            </tr>
            <tr>
                <td colspan="12" class="top"></td>
            </tr>
            <tr>
                <td colspan="12">&nbsp;</td>
                
            </tr>
            <tr>
                <td colspan="12"><small>Note: Bank charges may apply upon transfer of your payout from our BDO account to your bank account.</small></td>
                <br><br>
            </tr>
            <tr>
                <td colspan="6">
                   &nbsp;
                </td>
                <td colspan="4">
                    <span>Sub-Total</span>
                    <br>
                    <span>Shipping Fee</span>
                    <br>
                    <span>Transaction Fee</span>
                    <br>
                    <span>Bank Fee</span>
                    <br>
                    <span>Packaging Fee</span>
                    <br>
                    <span><b>GRAND TOTAL</b></span>
                    <br><br><br><br>
                </td>
                
                <td colspan="2" style="text-align: right;">
                    <span>{{number_format($subtotal,2)}}</span>
                    <br>
                    <span>-{{number_format($shipping,2)}}</span>
                    <br>
                    <span>-{{number_format($transaction_fee, 2)}}</span>
                    <br>
                    <span>{{number_format($issuance->bank_fee, 2)}}</span>
                    <br>
                    <span>-{{number_format($in->packaging_fee, 2)}}</span>
                    <br>
                    <span><b>{{number_format($subtotal - ($shipping + $transaction_fee + $issuance->bank_fee + $in->packaging_fee) , 2)}}<b></span>
                    <br><br><br><br>
                </td>
            </tr>
            <tr>
                <td colspan="12"><br>&nbsp;<br><br>&nbsp;<br></td>
            </tr>
            <tr>
                <td colspan="12">
                    <center>
                        <span>For more information, please contact us at https://www.facebook.com/AZSpree</span>
                    </center>
                    <br><br>
                </td>
            </tr>
        </table>
        <br>

        <div style="text-align:center;">
            <small>***** NOTHING TO FOLLOW *****</small>
        </div>
         <br>
         <br>
         <br>

        <div style="text-align:right;">
            <span class="top"  >&emsp;&emsp;&emsp; Prepared by &emsp;&emsp;&emsp;</span>
        </div>
    </body>
</html>