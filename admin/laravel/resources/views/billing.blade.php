@extends('layouts.navframe')
@section('page_heading','Billing and Payments')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">
        <!-- /.row -->

        <div class="row"> <!-- START Panel Row 1-->
            <div class="col-lg-6">
                <div id="pnl-totaloutstanding" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Total Outstanding</h3>
                    </div>
            
                    <div class="panel-body">
                        <p style="font-style:italic;">Last calculated: 30-Jan-2017, 00:01 UTC</p>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Description</th>    
                                    <th class="text-right">Amount (USD)</th>
                                    <th class="text-right">Amount (INR)*</th>    
                                    <th class="text-right">On</th>    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Amount Due:</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">07-Feb-2017</td>    
                                </tr>
                                <tr>
                                    <td>Last Payment:</td>
                                    <td class="text-right">1000.00</td>    
                                    <td class="text-right">68,000.00</td>    
                                    <td class="text-right">07-Jan-2017</td>    
                                </tr>
                            </tbody>
                        </table>
                        <p>*at today's exchange rate of 1 USD = 68.00 INR</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="pnl-mtdoutstanding" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">January 2017 MTD Total</h3>
                    </div>
            
                    <div class="panel-body">
                        <p style="font-style:italic;">Last calculated: 30-Jan-2017, 00:01 UTC</p>
                        <p style="font-size:28px;color:#444;">USD 1308.75</p>
                        <p style="font-size:38px;color:#444;">INR 88,993.98*</p>
                        <p>*at today's exchange rate of 1 USD = 68.00 INR</p>
                    </div>
                </div>
            </div> <!-- /.col-lg-6 -->
        </div> <!-- END Panel Row 1-->

        <div class="row"> <!-- START Panel Row 2-->
            <div class="col-lg-6">
                <div id="pnl-billinghistory" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Billing History(last 12 months)</h3>
                    </div>
            
                    <div class="panel-body">
                        <p style="font-style:italic;">Last calculated: 30-Jan-2017, 00:01 UTC</p>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>    
                                    <th>Month</th>    
                                    <th class="text-right">Amount (USD)</th>
                                    <th class="text-right">Amount (INR)*</th>    
                                    <th class="text-right">Status</th>    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                                <tr>
                                    <td>11111111</td>
                                    <td>December 2016</td>
                                    <td class="text-right">1308.75</td>    
                                    <td class="text-right">88,993.98</td>    
                                    <td class="text-right">Paid</td>    
                                </tr>
                            </tbody>
                        </table>
                        <p>*at prevaling exchange rates at the time of invoicing</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="pnl-mtdbreakup" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">January 2017 MTD Breakup</h3>
                    </div>
            
                    <div class="panel-body">
                        <p style="font-style:italic;">
                        Last calculated: 30-Jan-2017, 00:01 UTC, All amounts in USD
                        </p>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Item</th>    
                                    <th class="text-right">Units</th>    
                                    <th class="text-right">Rate</th>    
                                    <th class="text-right">Total</th>    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Subscription (monthly)</td>    
                                    <td class="text-right">1</td>    
                                    <td class="text-right">100.00</td>    
                                    <td class="text-right">100.00</td>    
                                </tr>
                                <tr>
                                    <td>Transactions Posted</td>    
                                    <td class="text-right">10,000</td>    
                                    <td class="text-right">0.05</td>    
                                    <td class="text-right">500.00</td>    
                                </tr>
                                <tr>
                                    <td>Transactions Received</td>    
                                    <td class="text-right">10,000</td>    
                                    <td class="text-right">0.05</td>    
                                    <td class="text-right">500.00</td>    
                                </tr>
                                <tr>
                                    <td>FX Rates Posted</td>    
                                    <td class="text-right">900</td>    
                                    <td class="text-right">0.05</td>    
                                    <td class="text-right">45.00</td>    
                                </tr>
                                <tr>
                                    <td>New Partners Activated</td>    
                                    <td class="text-right">0</td>    
                                    <td class="text-right">0.05</td>    
                                    <td class="text-right">0.00</td>    
                                </tr>
                                <tr>
                                    <td>Sub-Total</td>    
                                    <td class="text-right">-</td>    
                                    <td class="text-right">-</td>    
                                    <td class="text-right">1,145.00</td>    
                                </tr>
                                <tr>
                                    <td>Tax</td>    
                                    <td class="text-right">-</td>    
                                    <td class="text-right">14.30%</td>    
                                    <td class="text-right">163.75</td>    
                                </tr>
                                <tr>
                                    <td>Total</td>    
                                    <td class="text-right">-</td>    
                                    <td class="text-right">-</td>    
                                    <td class="text-right">1,308.75</td>    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- /.col-lg-6 -->
        </div> <!-- END Panel Row 2-->
        
        @stop
