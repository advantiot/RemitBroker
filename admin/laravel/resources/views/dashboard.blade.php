@extends('layouts.navframe')
@section('page_heading','Dashboard')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Outbound</h3>
                </div>
        
                <div class="panel-body">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2" style="color:blue;">
                                    <i class="fa fa-arrow-left fa-3x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $outbound_total_req_new }}</div>
                                    <div>New Transactions</div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        -->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2" style="color:orange;">
                                    <i class="fa fa-edit fa-3x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $outbound_total_req_mod }}</div>
                                    <div>Modification Requests</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2" style="color:red;">
                                    <i class="fa fa-ban fa-3x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $outbound_total_req_can }}</div>
                                    <div>Cancellation Requests</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2" style="color:green;">
                                    <i class="fa fa-inr fa-3x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $outbound_total_fxrate }}</div>
                                    <div>FX Rates</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div><!-- /.panel body -->
                <a href="{{ url ('dashboard/outbound') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div><!-- /.panel -->
            </div><!-- /.col12 -->
        </div> <!-- /.row -->

        <div class="row">
            <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Inbound</h3>
                </div>
        
                <div class="panel-body">
                    <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2" style="color:blue;">
                                    <i class="fa fa-arrow-right fa-3x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $inbound_total_req_new }}</div>
                                    <div>New Transactions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2" style="color:orange;">
                                    <i class="fa fa-edit fa-3x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $inbound_total_req_mod }}</div>
                                    <div>Modification Requests</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2">
                                    <i class="fa fa-ban fa-3x" style="color:red;"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $inbound_total_req_can }}</div>
                                    <div>Cancellation Requests</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2" style="color:green;">
                                    <i class="fa fa-inr fa-3x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge">{{ $inbound_total_fxrate }}</div>
                                    <div>FX Rates</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div><!-- /.panel body -->
                <a href="{{ url ('dashboard/inbound') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div><!-- /.panel -->
            </div><!-- /.col12 -->
        </div> <!-- /.row -->

        <!-- Notification Panel column -->
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Partner Notifications</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-success">
                                <h5 class="list-group-item-heading">
                                <strong>Rupaisa Pvt. Ltd.</strong>
                                <span class="pull-right text-muted small"><em>4 minutes ago</em>
                                </span>
                                </h5>
                                <p class="list-group-item-text">
                                New Mobile Wallet payment services launched. Now directly credit your beneficiary's mobile wallet.
                                </p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-danger">
                                <h5 class="list-group-item-heading">
                                <strong>Zoomia Dinero SL</strong>
                                <span class="pull-right text-muted small"><em>4 minutes ago</em>
                                </span>
                                </h5>
                                <p class="list-group-item-text">
                                Payout services unavailable on Sunday, 05-Feb-2017 from 17:00 hours local time. 
                                </p>
                            </a>
                        </div>
                        <!-- /.list-group -->
                        <a href="#" class="btn btn-default btn-block">View All</a>
                    </div> <!-- /.panel-body -->
                </div>
            </div> <!-- /.col-lg-12 -->
            <!-- Column 2 -->
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">RemitBroker Notifications</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-warning">
                                <h5 class="list-group-item-heading">
                                <strong>Scheduled Maintenance Update</strong>
                                <span class="pull-right text-muted small"><em>4 minutes ago</em>
                                </span>
                                </h5>
                                <p class="list-group-item-text">
                                RemitBroker will be undergoing scheduled maintenance on 01-Feb-2017 from 12:00 (midnight) to 02-Feb-2017 (01:00 hours). No downtime is expected.
                                </p>
                            </a>
                        </div>
                        <!-- /.list-group -->
                        <a href="#" class="btn btn-default btn-block">View All</a>
                    </div> <!-- /.panel-body -->
                </div>
            </div> <!-- /.col-lg-12 -->
        </div> <!-- END Row -->
@stop
