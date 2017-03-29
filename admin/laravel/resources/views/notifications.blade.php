@extends('layouts.navframe')
@section('page_heading','Notifications')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">

        <div class="row"> <!-- START Form Row -->
            <div class="col-lg-12">
                <!-- Form -->
                <div id="pnl-entercredentials" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Enter Notification</h3>
                    </div>
            
                    <div class="panel-body">
  <form role="form" class="form-horizontal" method="POST" action="/postnotification">
    <!--    
    <div class="form-group">
      <label class="col-sm-1" for="inputEmail1">Email</label>
      <div class="col-sm-5"><input type="email" class="form-control" id="inputEmail1" placeholder="Email"></div>
    </div>
    <div class="form-group">
      <label class="col-sm-1" for="inputPassword1">Password</label>
      <div class="col-sm-5"><input type="password" class="form-control" id="inputPassword1" placeholder="Password"></div>
    </div>
    -->
    <div class="form-group">
      <div class="col-sm-3"><label>Severity:</label>
        <select class="form-control">
          <option>Information</option>
          <option>Warning</option>
          <option>Alert</option>
        </select>
    </div>

      <div class="col-sm-3"><label>Relevant Until:</label><input type="text" class="form-control" placeholder="yyyy-mm-dd"></div>
    </div>
    <div class="form-group">
      <label class="col-sm-12" for="TextArea">Textarea</label>
      <div class="col-sm-6"><textarea class="form-control" id="TextArea" rows="4"></textarea></div>
    </div>
    <!--
    <div class="form-group">
      <label class="col-sm-12">Phone number</label>
      <div class="col-sm-1"><input type="text" class="form-control" placeholder="000"><div class="help">area</div></div>
      <div class="col-sm-1"><input type="text" class="form-control" placeholder="000"><div class="help">local</div></div>
      <div class="col-sm-2"><input type="text" class="form-control" placeholder="1111"><div class="help">number</div></div>
      <div class="col-sm-2"><input type="text" class="form-control" placeholder="123"><div class="help">ext</div></div>
    </div>
    <div class="form-group">
      <label class="col-sm-1">Options</label>
      <div class="col-sm-2"><input type="text" class="form-control" placeholder="Option 1"></div>
      <div class="col-sm-3"><input type="text" class="form-control" placeholder="Option 2"></div>
    </div>
    -->
    <div class="form-group">
      <div class="col-sm-6">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
                    </div>
                </div> <!-- ./ Form Panel -->
            </div> <!-- /.col-lg-6 -->
        </div> <!-- END Form Row -->



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
                    </div> <!-- /.panel-body -->
                </div>
            </div> <!-- /.col-lg-12 -->
        </div> <!-- END Row -->
@stop
