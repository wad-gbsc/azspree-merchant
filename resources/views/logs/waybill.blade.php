<!DOCTYPE html>
<html>
    <head>
        <title>Waybill</title>
        
        <style type="text/css" scoped>
            body{
                margin: 0%;
			font-family: calibri;
		}
		.qrcode{
			width: 70px;
			height: 70px;
		}
        .logodh{
			width: auto;
			height: 50px;
		}
		/* td.header{
			text-transform: uppercase;
			font-Total Kg: bold;
			font-size: 15pt;
		}
		.line{
			width: 100px !important;
			border-bottom: 1px solid black;
			display: inline-block;
            float: left;
		}
		table{
			border-collapse: collapse;
			width: 100%;
		} */
		table td.report_header{
			font-size: 26px;
		}
		
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
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
                text-align: center;
                vertical-align: middle;
            }
        </style>
    </head>
    <body style="overflow-x:auto;">
        @foreach ($waybill as $item)
        @endforeach
        @foreach ($sumr as $merchant)
        @endforeach
        
        <table width="100%" >
		<tr>
		</tr>
		<tr>
             <td colspan="3">
                <center>
                 <img src="brands_try/azspreelogo.png" class="logodh">
                </center>
            </td>
            
			<td colspan="3" style="border-right: 0px;">
                <div><label>Tracking No.</label></div><br>
				<div style="font-size: 20px;"><label><center><b>{{$item->order_no}}</b></center></label></div>
            </td>
            <td colspan="6">
                        <center>
                        <div class="barcodecell">
                        <barcode code="{{"Order No. : "  .$item->order_no}}" size="2.3" type="QR" error="H" disableborder="1" class="barcode" />
                        </div>
                        </center>
                        {{-- {!!  QrCode::size(130)->generate("Order No. : "  .$item->order_no ."\nMerchant Name : "
                         .$item->seller_name ."\nContact No. : " .$item->contact ."\nAddress : " .$merchant->sumr_address ." " 
                         .$merchant->barangay .", " .$merchant->m_city); !!} --}}
			</td>
        </tr>
        <tr>
            <td colspan="12">
                <div style="font-size: 18px;"><label>ORDER NO:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$item->order_no}}</div>
                <br>
                <div style="font-size: 18px;"><label>DH LOCATION:</label>&nbsp;&nbsp;&nbsp;&nbsp; Distribution Hub 2</div>
                <br>
			</td>
        </tr>
        <tr>
            <td colspan="3"  style="font-size: 18px">
                <b>FROM</b>
            </td>
            <td colspan="9"  style="font-size: 18px">
               <div>Merchant's Name: {{$merchant->seller_name}}</div><br>
               <div>Contact No: {{$merchant->contact}}</div><br>
               <div>Address: {{$merchant->sumr_address ." " .$merchant->barangay ." " .$merchant->m_city .", " .$merchant->province }}</div> <br>
            </td>
        </tr>
        <tr>
            <td colspan="3"  style="font-size: 18px">
                <b>TO</b>
            </td>
            <td colspan="9"  style="font-size: 18px">
               <div>Buyer's Name: {{$item->fullname}}</div><br>
               <div>Contact No: {{$item->contact_no}}</div><br>
               <div>Address: {{$item->address ." " .$item->barangay ." " .$item->city .", " .$item->province}}</div><br>
            </td>
        </tr>
        <tr>
            <td colspan="3"  style="font-size: 18px">
                <div><br><br><center>No of Delivery Attemps</center><br></div>
                <div>
                    <tr>
                    <td colspan="1" >
                        <center>1</center>
                    </td>
                    <td colspan="1" >
                        <center>2</center>
                    </td>
                    <td colspan="1" >
                        <center>3</center>
                    </td>
                    </tr>
                </div>
            </td>
            <td colspan="9"  style="font-size: 18px">
                <div>Mode of Payment: {{$item->payment_method}}</div>
                <div>Total Qty: {{$item->total_qty}}</div>
                <div>Total Kg: {{$item->total_excess_kg}}</div>
                <div>Category:</div>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="font-size: 18px">
                <label><br><center>Thank you for shopping with AZSpree!<br>
                    Please don’t forget to click the “Order Received and rate this Product”
                    </center><br>&emsp;</label>
            </td>
            <td colspan="6">
                <center>
                    <div class="barcodecell">
                    <barcode
                        code="{{
                        "Merchant Name: ".$merchant->seller_name.'\n'
                        ."Contact No.: ".$merchant->contact .'\n'
                        ."Address: ".$merchant->barangay.", ".$merchant->m_city.'\n'
                        ."-".'\n'
                        ."Buyer Name: ".$item->fullname .'\n'
                        ."Contact No.: ".$item->contact_no .'\n'
                        ."Buyer Address: ".$item->address 
                        ." " 
                        .$item->barangay .", ".$item->city
                        }}" 
                        size="2.3" type="QR" error="L" disableborder="1" class="barcode"/>
                    </div>
                </center>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <br>
            </td>
            <td colspan="8">
              <br>
            </td>
        </tr>
        <tr>
            {{-- <td colspan="4"  style="font-size: 18px">
                <br><center>MERCHANT'S SIGNATURE</center><br>
            </td> --}}
            <td colspan="8"  style="font-size: 18px">
                <br><center>DH'S SIGNATURE</center><br>
            </td>
        </tr>
        </table>
           
    </body>
</html>
