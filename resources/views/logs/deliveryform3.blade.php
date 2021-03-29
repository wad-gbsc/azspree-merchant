<!DOCTYPE html>
<html>
    <head>
        <title>Delivery Receipt</title>
        
        <style type="text/css" scoped>
            body{
			font-family: calibri;
		}
        .logodh{
			width: auto;
			height: auto;
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
        .barcode {
                padding: 1.5mm;
                margin: 0;
                vertical-align: top;
            }
            .barcodecell {
                text-align: right;
                vertical-align: middle;
            }
        </style>
    </head>
    <body style="overflow-x:auto;">
        @foreach ($sohr as $item)
        @endforeach
        @foreach ($sumr as $merchant)
        @endforeach
        @foreach ($users as $user)
        @endforeach
        <table width="100%" >
            <tr>
                <td colspan="12">
                    <center>
                        <span class="field">Delivery Receipt</span>
                    </center>
                    <br><br>
                </td>
            </tr>

            <tr>
                <td colspan="12" style="text-align: right;">
                        <i>For Buyer's Copy</i>
                </td>
            </tr>

		<tr >
			<td colspan="10">
                <br><br>
                <img src="brands_try/azspreelogo.png" class="logodh">
                {{-- <span text-transform: uppercase;>Pampanga, Philippines</span> --}}
            </td>
            <td colspan="2"  style="text-align:right;">
                <barcode code="{{$item->order_no}}" size="1.2" type="QR" error="H" disableborder="1" class="barcode" />
			</td>
        </tr>
        <tr>
			<td colspan="12" class="top"></td>
		</tr>
        </table>
        <table width="100%" >
            <tr>
                <td colspan="4">
                    <span class="field">Ordered by</span>
                    <br>
                    <span>Name: {{$user->fullname}}</span>
                    <br>
                    <span>Email: {{$user->email}}</span>
                    <br>
                    <span>Address: {{$user->address ." " .$user->barangay}} <br> {{$user->city .", " .$user->province}}</span>
                    <br><br><br><br>
                </td>
                <td colspan="4">
                    <span class="field">Order details</span>
                    <br>
                    <span>Order No: {{$item->order_no}}</span>
                    <br>
                    <span>Payment Type: {{$item->payment_method}}</span>
                    <br><br><br><br>
                </td>
                <td colspan="4">
                    <span class="field">delivered to</span>
                    <br>
                    <span>Name: {{$item->fullname}}</span>
                    <br>
                    <span>Email: {{$item->email}}</span>
                    <br>
                    <span>Address: {{$item->address ." " .$item->barangay}} <br> {{$item->city .", " .$item->province}}</span>
                    <br><br><br><br>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="top"></td>
            </tr>
        </table>
        <table width="100%" >
            <tr>
                <td colspan="5">
                    <span class="field">product name</span>
                    <br><br>
                </td>
                <td colspan="1" >
                    <span class="field">quantity</span>
                    <br><br>
                </td>
                <td colspan="2" style="text-align: right;">
                    <span class="field">unit cost</span>
                    <br><br>
                </td> 
                <td colspan="1" style="text-align: right;">
                    <span class="field">discount</span>
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
            <?php 
            $item_total = 0;
            $grand_total =0;
            $amount = 0;
            ?>
            <?php foreach($soln as $line):
            $item_total += $line->unit_price;
            $amount = $line->qty * $line->unit_price;

            ?>
            
            <tr>
                <td colspan="5" style="text-align: left;">
                  <span>{{$line->product_name}}</span> 
                  <br><br>
                </td>
                <td colspan="1">
                    <span>{{number_format($line->qty)}}</span>
                    <br><br>
                </td>
                <td colspan="2" style="text-align: right;">
                    <span>{{number_format($line->unit_price,2)}}</span>
                    <br><br>
                </td>
                <td colspan="1" style="text-align: right;">
                    <span>{{number_format($item->disc_amt,2)}}</span>
                    <br><br>
                </td>
                <td colspan="3" style="text-align: right;">
                    <span>{{number_format($amount,2)}}</span>
                    <br><br>
                </td>
               
            </tr>   
            <?php endforeach; ?> 
            <tr>
                <td colspan="12" class="top"></td>
            </tr>
            <tr>
                <td colspan="12">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="8">&nbsp;</td>
                <td colspan="2">
                    <span>Shipping Fee </span>
                    <br>
                    <span>Shipping Fee Disc.</span>
                    <br>
                    <span><b>TOTAL AMOUNT</b></span>
                    <br><br><br><br>
                </td>
                <td colspan="2" style="text-align: right;">
                    <span>{{number_format($item->shipping_fee,2)}}</span>
                    <br>
                    <span>0.00</span>
                    <br>
                    <span>{{number_format($item_total + $item->shipping_fee,2)}} </span>
                    <br><br><br><br>
                </td>
            </tr>
        </table>
        <table width="100%">
        <tr>
            <td colspan="3" style="font-size: 18px">
                <center>MERCHANT'S SIGNATURE</center>
            </td>
            <td colspan="9" style="font-size: 18px">
                <center>DH'S SIGNATURE</center>
            </td>
        </tr>
        <tr>
            <td colspan="12">
                <br>
                <center>
                    <span>For more information, please contact us at https://www.facebook.com/AZSpree</span>
                </center>
                <br><br>
            </td>
        </tr>
        </table>
    </body>
</html>