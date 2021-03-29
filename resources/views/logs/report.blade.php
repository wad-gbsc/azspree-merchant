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
			height: auto;
		}
        .table1, .th1, .td1 {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 12px;
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
                <td colspan="4" class="header report_header" style="font-size: 24pt; font-family: Times new roman;">
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
                <h4>All</h4>
             <?php } else { ?>
                <h4> {{date($date_from)}} - {{date($date_to)}}</h4>
             <?php } ?>
            </div>
        <table class="table1" style="font-size: 14pt; width: 100%;">
            <thead>
                <tr>
                    <th colspan="11" class="th1" style="text-align:left;"></th>
                    <th colspan="4" class="th1" style="text-align:center; background-color: #5bc0de">Payout</th>
                </tr>
                <tr>
                    <th class="th1" style="text-align:left;">Order No.</th>
                    <th class="th1" style="text-align:left;">Merchant Name</th>
                    <th class="th1" style="text-align:left;">Order Date</th>
                    <th class="th1" style="text-align:left;">MOP</th>
                    <th class="th1" style="text-align:left;">Qty</th>
                    <th class="th1" style="text-align:left;">Packaging Fee</th>
                    <th class="th1" style="text-align:left;">Adjustment</th>
                    <th class="th1" style="text-align:left;">Bank Fee</th>
                    <th class="th1" style="text-align:left;">Buyer's SF</th>
                    <th class="th1" style="text-align:left;">Merchants's SF</th>
                    <th class="th1" style="text-align:left;">Merchant</th>
                    <th class="th1" style="text-align:left;">DashSpree Express</th>
                    <th class="th1" style="text-align:left;">AZSpree</th>
                    <th class="th1" style="text-align:left;">Order Total</th>
                    {{-- <th class="th1" style="text-align:left;">Total</th> --}}
                </tr>
            </thead>
                <tbody>
                    
                    
                    <?php 
                    $grand_total = 0;
                    $order_total_total = 0;
                    $packaging_fee_total = 0;
                    $add_charges_total = 0;
                    $bank_fee_total = 0;
                    $shipping_fee_total = 0;
                    $m_shipping_fee_total = 0;
                    $dashspree_express_total = 0;
                    $azspree_total = 0;
                    $merchant_total = 0;
                    foreach($invoices as $invoice):
                    $grand_total += $invoice->order_total;
                    $order_total_total += $invoice->order_subtotal;
                    $packaging_fee_total += $invoice->packaging_fee;
                    $add_charges_total += $invoice->add_charges;
                    $bank_fee_total += $invoice->bank_fee;
                    $shipping_fee_total += $invoice->shipping_fee;
                    $m_shipping_fee_total += $invoice->m_shipping_fee;
                    $dashspree_express_total += $invoice->shipping_fee;
                    $azspree_total += $invoice->azspree;
                    $merchant_total += $invoice->order_subtotal - ($invoice->m_shipping_fee + $invoice->bank_fee + $invoice->packaging_fee + $invoice->add_charges + $invoice->azspree);

                    ?>
                    <tr>
                        <td class="td1" style="text-align:left; width: 45px;">{{$invoice->order_no}}</td>
                        <td class="td1" style="text-align:left; ">{{$invoice->seller_name}}</td>
                        <td class="td1" style="text-align:left; width: 45px;">{{$invoice->order_date}}</td>
                        <td class="td1" style="text-align:left; ">{{$invoice->payment_method}}</td>
                        <td class="td1" style="text-align:left; ">{{$invoice->total_qty}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->packaging_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->add_charges,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->bank_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->shipping_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->m_shipping_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->order_subtotal - ($invoice->m_shipping_fee + $invoice->bank_fee + $invoice->packaging_fee + $invoice->add_charges + $invoice->azspree) , 2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->shipping_fee + $invoice->m_shipping_fee,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->azspree,2)}}</td>
                        <td class="td1" style="text-align:right; ">{{number_format($invoice->order_subtotal,2)}}</td>
                        {{-- <td class="td1" style="text-align:right; ">{{number_format($invoice->order_total,2)}}</td> --}}
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <tr>
                    <th colspan="5" class="th1" style="text-align:right;"><b>Grand Total</b></th>
                    <td class="td1" style="text-align:right;"><b>{{number_format($packaging_fee_total,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($add_charges_total,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($bank_fee_total,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($shipping_fee_total,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($m_shipping_fee_total,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($merchant_total,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($invoice->shipping_fee + $invoice->m_shipping_fee,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($azspree_total,2)}}</b></td>
                    <td class="td1" style="text-align:right;"><b>{{number_format($order_total_total,2)}}</b></td>
                    {{-- <td class="td1" style="text-align:right;"><b>{{number_format($invoice->order_total,2)}}</b></td> --}}
                </tr>
                
        </table>
        <br>
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