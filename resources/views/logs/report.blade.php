<!DOCTYPE html>
<html>
    <head>
        <title>Reports</title>
        
        <style type="text/css" scope> 
            body{
                
                
            }
            table {
                font-size: 10pt!important;
                border-collapse: collapse;
                
            }
            tr:last-child td{
                border-bottom: 1px solid gray;
                border: 0px;    
                
            }
            td {
                border-right: 1px solid gray;
                border-left: 1px solid gray;
                padding-left: 5px;
                padding-right: 5px;
               
            }
            th {
                border: 1px solid gray;
                padding-left: 5px;
                padding-right: 5px;
               
            }
            @page {
                margin: 0.4in;
                border: 0px;
            }
            .logo{
			width: 130px;
			height: 130px;
			float: right;
			text-align: right;
            border: 0px;
            margin-top: -9%;
            /* margin-right: -7%;
            
            margin-left: 0%; */
		}
        table td.report_header{
            border: 0px;
			font-size: 11pt;
            
		}
        .top{
			border-top: 1px solid black;
            border-left: 0px !important;
            border-right:0px;
            margin-left: 5px;
          
		}
        .border{
            border: 0px;
        }
        .bottom {
            border-bottom: 1px solid black;
        }
       body{
			font-family: calibri;
		}
        .logodh{
			width: auto;
			height: 50px;
		}
        .table1, .th1, .td1 {
            border: 1px solid black;
            border-collapse: collapse;
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
                <td colspan="4" class="header report_header" style="font-size: 14pt; font-family: Times new roman;">
                    <center><b>AZSpree Report</b></center>
                </td>
            </tr>
            
        </table>
    
        <div style="text-align:right;">
        <small> Date Prepared :  {{date("Y-m-d")}}
        <br>
        <?php
        date_default_timezone_set("Asia/Manila");
        echo "Time Prepared :" . date("h:i:sa");
        ?></small>
        </div>
        <div style="text-align:center;">
            <?php if ($date_from == 0 || $date_to == 0) {?>
                <h6>All</h6>
             <?php } else { ?>
                <small> {{date($date_from)}} - {{date($date_to)}}</small>
             <?php } ?>
            </div>
        <table class="table1" style="font-size: 14pt; width: 100%;">
            <thead>
                <tr>
                    <th colspan="8" class="th1" style="text-align:left;"></th>
                    <th colspan="6" class="th1" style="text-align:center; background-color: #5bc0de">Payout</th>
                </tr>
                <tr>
                    <th class="th1" style="text-align:left;">Order No.</th>
                    <th class="th1" style="text-align:left;">Merchant Name</th>
                    <th class="th1" style="text-align:left;">Order Date</th>
                    <th class="th1" style="text-align:left;">MOP</th>
                    <th class="th1" style="text-align:left;">Qty</th>
                    <th class="th1" style="text-align:left;">Order Subtotal</th>
                    <th class="th1" style="text-align:left;">Buyer's ShippingFee</th>
                    <th class="th1" style="text-align:left;">Merchants's ShippingFee</th>
                    <th class="th1" style="text-align:left;">Merchant</th>
                    <th class="th1" style="text-align:left;">DashSpree Express</th>
                    <th class="th1" style="text-align:left;">Azspree</th>
                    <th class="th1" style="text-align:left;">Total</th>
                </tr>
            </thead>
                <tbody>
                    
                    @foreach($invoices as $invoice)
                    <?php 
                    $grand_total = 0;
                    $grand_total += $invoice->order_total
                    ?>
                    <tr>
                        <td class="td1" style="text-align:left; width: 45px;">{{$invoice->order_no}}</td>
                        <td class="td1" style="text-align:left; ">{{$invoice->seller_name}}</td>
                        <td class="td1" style="text-align:left; width: 45px;">{{$invoice->order_date}}</td>
                        <td class="td1" style="text-align:left; ">{{$invoice->payment_method}}</td>
                        <td class="td1" style="text-align:left; ">{{$invoice->total_qty}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->order_subtotal,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->shipping_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->m_shipping_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->order_subtotal - ($invoice->m_shipping_fee + $invoice->transaction_fee + 3 + $invoice->azspree) , 2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->shipping_fee + $invoice->m_shipping_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->azspree,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->order_total,2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
        <br>
        <div style="text-align:right; font-size: 10pt; ">
            <span>
                <b>Grand Total : {{number_format($grand_total,2)}}<b> 
            </span>
        </div>
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