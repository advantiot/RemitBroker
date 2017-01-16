@extends('layouts.navframe')
@section('page_heading','Credentials')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">
        <!-- /.row -->

        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info " role="alert">
                    <h3 style="margin-top:0px;"><i class="fa fa-info-circle"></i> Alert</h3> 
                    A new API Key and a new Master Password should be generated periodically to increase security. At any time if you suspect that your credentials have been compromised generate new credentials immediately. It is stating the obvious that you should not share your credentials with anyone other than authorized users. When using the API Key in an application store it as a hash not in clear text. 
                </div>
            </div>
        </div>

        <div class="row"> <!-- START Form Row -->
            <div class="col-lg-6">
                <!-- Form -->
                <div id="pnl-entercredentials" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Enter Credentials</h3>
                    </div>
            
                    <div class="panel-body">
                        <form role="form" method="POST" action="/validate">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Remitter Id:</label>
                                <input id="fld-remitterid" class="form-control" placeholder="" name="remitter_id" value="475246">
                            </div>

                            <div class="form-group">`
                                <label>Master Password:</label>
                                <input id="fld-mstrpwd" class="form-control" placeholder="" name="master_password" value="4tj9bO15Md" type="password">
                            </div>

                            <div class="form-group">
                                <label>Current API Key:</label>
                                <input id="fld-apikey" class="form-control" placeholder="" name="api_key" value="e06abdc9-767a-40a3-b6dd-5cb210816158">
                            </div>

                            <div class="form-group">
                                <button id="btn-newapikey" type="button" class="btn btn-primary">New API Key</button>
                                <button id="btn-chngmstrpwd" type="button" class="btn btn-primary">Change Master Password</button>
                            </div>
                        </form>
                        
                        <!-- ./ Error div -->
                        <div id="div-errors" class="alert alert-danger" style="display:none;">
                            <div class="list-group" style="margin-bottom:0px;">
                                @foreach ($errors->all() as $error)
                                <span class="list-group-item">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <!-- ./ Error div -->
                    </div>
                </div>
                <!-- ./ Form Panel -->

                <!-- Generate New API Key panel -->
                <div id="pnl-newapikey" class="panel panel-default" style="display:none">
                    <div class="panel-heading">
                        <h3 class="panel-title">New API Key:</h3>
                    </div>
            
                    <div class="panel-body">
                        <h3 id="lbl-newapikey" style="margin-top:0px;color:red;"></h3>

                        <div class="alert alert-danger " role="alert">
                            <h3 style="margin-top:0px;"><i class="fa fa-info-circle"></i> Alert</h3> 
                            <p id="lbl-newapikeymsg">
                            A new API Key has been generated for your account, however, it is not yet activated. Once you activate the new API key requests to the RemitBroker API with your old API Key will stop working immediately. Please ensure that all client applications, including batch jobs, are updated with the new API Key before activating it. If you are not ready at this time please click on the Cancel button and activate this API Key when you are ready.
                            </p>
                        </div>

                        <button id="btn-activatenewapikey" type="button" class="btn btn-success">Activate New API Key</button>
                        <button id="btn-cancelnewapikey" type="button" class="btn btn-danger">Cancel</button>
                    </div>
                </div>

                <!-- Change Master Password panel -->
                <div id="pnl-chngmstrpwd" class="panel panel-default" style="display:none">
                    <div class="panel-heading">
                        <h3 class="panel-title">Change Master Password:</h3>
                    </div>
            
                    <div class="panel-body">
                        <form role="form">
                            <div class="form-group">
                                <label>New Master Password:</label>
                                <input class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Confirm Master Password:</label>
                                <input type="password" class="form-control" placeholder="">
                            </div>

                        <button id="btn-confchngmstrpwd" type="button" class="btn btn-success">Confirm Change</button>
                        <button id="btn-cnclchngmstrpwd" type="button" class="btn btn-danger">Cancel</button>

                        </form>
                    </div>
                </div>
            </div> <!-- /.col-lg-6 -->
        </div> <!-- END Form Row -->
        
        @stop
