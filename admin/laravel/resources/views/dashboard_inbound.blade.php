@extends('layouts.navframe')
@section('page_heading','Inbound Transactions')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">

        <div class="row"> <!-- START Table Row -->
            <div class="col-lg-12">
                <!-- ./ TxnRequests Sent Counts -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Requests From and Responses To</h3>
                    </div>
            
                    <div class="panel-body visible-xs-block visible-sm-block">
                        <div class="alert alert-info" role="alert">Data table only visible on wider screen formats.</div>
                    </div>

                    <div class="panel-body visible-md-block visible-lg-block">
                        <table class="table table-bordered" style="text-align:right;width:100%;">
                            <thead>
                            <tr>
                                <th style="width:35%;"></th>
                                <th colspan="3" class="text-center">REQUESTS FROM</th>
                                <th colspan="4" class="text-center">RESPONSES TO</th>
                            </tr>
                            <tr>
                                <th class="text-right" style="width:35%;">Remitter</th>
                                <th class="text-right">REQ_NEW</th>
                                <th class="text-right">REQ_MOD</th>
                                <th class="text-right">REQ_CAN</th>
                                <th class="text-right">ACK_REQ</th>
                                <th class="text-right">REJ_REQ</th>
                                <th class="text-right">CNF_PD</th>
                                <th class="text-right">CNF_CAN</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($request_response_counts as $k => $v)
                            <tr>
                                <!--<td>{{ $k }}</td>--> 
                                @foreach ($v as $key => $value) 
                                    <!--<td>{{ $key }} - {{ $value }} - {{ $v['name'] }}</td>-->
                                @endforeach

                                <!-- Hard code rather than loop as above for better control on positioning -->
                                <!--Check if key exists since a key is inseerted only if there is a data value -->

                                <th class="text-right">@if (array_key_exists('name', $v))
                                        {{ $v['name'] }} 
                                    @else -
                                    @endif
                                </th>
                                <td>@if (array_key_exists('req_new', $v))
                                        {{ $v['req_new'] }} 
                                    @else 0
                                    @endif
                                </td>
                                <td>@if (array_key_exists('req_mod', $v))
                                        {{ $v['req_mod'] }} 
                                    @else 0
                                    @endif
                                </td>
                                <td>@if (array_key_exists('req_can', $v))
                                        {{ $v['req_can'] }} 
                                    @else 0
                                    @endif
                                </td>
                                <td>@if (array_key_exists('ack_req', $v))
                                        {{ $v['ack_req'] }} 
                                    @else 0
                                    @endif
                                </td>
                                <td>@if (array_key_exists('rej_req', $v))
                                        {{ $v['rej_req'] }} 
                                    @else 0
                                    @endif
                                </td>
                                <td>@if (array_key_exists('cnf_pd', $v))
                                        {{ $v['cnf_pd'] }} 
                                    @else 0
                                    @endif
                                </td>
                                <td>@if (array_key_exists('cnf_can', $v))
                                        {{ $v['cnf_can'] }} 
                                    @else 0
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <!-- Totals row -->
                            <tr style="font-weight:bold;">
                                <td>Totals:</td>
                                <td>{{ $total_req_new }}</td>
                                <td>{{ $total_req_mod }}</td>
                                <td>{{ $total_req_can }}</td>
                                <td>{{ $total_ack_req }}</td>
                                <td>{{ $total_rej_req }}</td>
                                <td>{{ $total_cnf_pd }}</td>
                                <td>{{ $total_cnf_can }}</td>
                            </tr>
                            </tbody>
                        </table>            
                    </div>
                </div>
                <!-- ./ TxnRequests Sent Counts -->
            </div> <!-- /.col-lg-12 -->
        </div> <!-- END Table Row -->
@stop
