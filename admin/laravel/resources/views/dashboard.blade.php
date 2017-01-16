@extends('layouts.navframe')
@section('page_heading','Dashboard')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-exchange fa-3x"></i>
                            </div>
                            <div class="col-xs-10 text-right">
                                <div class="huge">{{ $total_req_new }}</div>
                                <div>New Transactions</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-edit fa-3x"></i>
                            </div>
                            <div class="col-xs-10 text-right">
                                <div class="huge">{{ $total_req_mod }}</div>
                                <div>Modification Requests</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-ban fa-3x"></i>
                            </div>
                            <div class="col-xs-10 text-right">
                                <div class="huge">{{ $total_req_can }}</div>
                                <div>Cancellation Requests</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-inr fa-3x"></i>
                            </div>
                            <div class="col-xs-10 text-right">
                                <div class="huge">{{ $total_fxrate }}</div>
                                <div>FX Rates</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                @section ('cchart11_panel_title','Today\'s Transactions')
                @section ('cchart11_panel_body')
                @include('widgets.charts.clinechart')
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'cchart11'))
                <!-- /.panel -->
            </div>        
        </div> <!-- END Chart Row -->

        <div class="row"> <!-- START Table Row -->
            <div class="col-lg-12">
                <!-- ./ TxnRequests Sent Counts -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">TxnRequests Pending Processing</h3>
                    </div>
            
                    <div class="panel-body">
                        <table class="table table-bordered" style="text-align:right;">
                            <thead>
                            <tr>
                                <th class="text-right">To Remitter</th>
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
        
        <!-- Notification Panel column -->
        <div class="row">
            <div class="col-lg-4">
                @section ('pane1_panel_title', 'Notifications Panel')
                @section ('pane1_panel_body')
                    
                    <div class="list-group">
                        <a href="#" class="list-group-item">
                            <i class="fa fa-comment fa-fw"></i> New Comment
                            <span class="pull-right text-muted small"><em>4 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small"><em>12 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-envelope fa-fw"></i> Message Sent
                            <span class="pull-right text-muted small"><em>27 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-tasks fa-fw"></i> New Task
                            <span class="pull-right text-muted small"><em>43 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                            <span class="pull-right text-muted small"><em>11:32 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-bolt fa-fw"></i> Server Crashed!
                            <span class="pull-right text-muted small"><em>11:13 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-warning fa-fw"></i> Server Not Responding
                            <span class="pull-right text-muted small"><em>10:57 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-shopping-cart fa-fw"></i> New Order Placed
                            <span class="pull-right text-muted small"><em>9:49 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-money fa-fw"></i> Payment Received
                            <span class="pull-right text-muted small"><em>Yesterday</em>
                            </span>
                        </a>
                    </div>
                    <!-- /.list-group -->
                    <a href="#" class="btn btn-default btn-block">View All Notifications</a>
                
                <!-- /.panel-body -->
              
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'pane1'))
            </div>
            <!-- /.col-lg-4 -->
        </div> <!-- END Row -->
@stop
