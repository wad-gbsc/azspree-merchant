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
                    
                    <br>
                    <span>&nbsp;</span>
                    <br><br><br><br>
                </td>
                <td colspan="4">
                    <span class="field">Order details</span>
                    <br>
                    <span>Order No: {{$item->order_no}}</span>
                    <br>
                    <span>Invoice No:</span>
                    <br>
                    <span>Invoice Date:</span>
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
                <td colspan="1">
                    <span class="field">product code</span>
                    <br><br>
                </td>
                <td colspan="5" style="text-align: center;">
                    <span class="field">description</span>
                    <br><br>
                </td>
                <td colspan="2" style="text-align: right;">
                    <span class="field">unit price</span>
                    <br><br>
                </td> 
                <td colspan="1" style="text-align: right;">
                    <span class="field">discount</span>
                    <br><br>
                </td>
                <td colspan="3" style="text-align: right;">
                    <span class="field">total price</span>
                    <br><br>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="top"></td>
            </tr>
            @foreach($soln as $line)
            <tr>
                
                <td colspan="2" style="text-align: left;">
                  <span>{{$line->product_name}}</span> 
                  <br><br><br><br>
                </td>
                <td colspan="5" style="text-align: left;">
                    <span>{{$line->product_desc}}</span>
                    <br><br>
                </td>
                <td colspan="1" style="text-align: right;">
                    <span>{{$line->cost_amt}}</span>
                    <br><br><br><br>
                </td>
                <td colspan="1" style="text-align: right;">
                    <span>{{$item->disc_amt}}</span>
                    <br><br><br><br>
                </td>
                <td colspan="3" style="text-align: right;">
                    <span>{{$line->unit_price}}</span>
                    <br><br><br><br>
                </td>
               
            </tr>
             @endforeach
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
                    <span><b>GRAND TOTAL</b></span>
                    <br><br><br><br>
                </td>
                <td colspan="2" style="text-align: right;">
                    <span>{{$item->shipping_fee}}</span>
                    <br>
                    <span>0.00</span>
                    <br>
                    <span>0.00</span>
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
    </body>
</html>