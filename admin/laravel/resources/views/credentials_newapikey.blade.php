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
                <!-- Generate New API Key panel -->
                <div id="pnl-newapikey" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">New API Key:</h3>
                    </div>
            
                    <div class="panel-body">
                        <!-- Form -->
                        <!-- Activation needs no usr inputs so this action is handled via jquery -->
                        <form role="form" method="POST" action="">
                            {{ csrf_field() }}
                            <input id="txt-newapikey" name="txt-newapikey" value={{ $new_api_key }} style="width:100%;font-size:20px;padding:5px;margin-bottom:10px;color:red;background:white;border:0px" disabled></input>

                            <div class="alert alert-danger " role="alert">
                                <h3 style="margin-top:0px;"><i class="fa fa-info-circle"></i> Alert</h3> 
                                <p id="lbl-newapikeymsg">
                                A new API Key has been generated for your account, however, it is not yet activated. Once you activate the new API key requests to the RemitBroker API with your old API Key will stop working immediately. Please ensure that all client applications, including batch jobs, are updated with the new API Key before activating it. If you are not ready at this time please click on the Cancel button and activate this API Key when you are ready.
                                </p>
                            </div>

                            <button id="btn-activatenewapikey" type="button" class="btn btn-success">Activate New API Key</button>
                            <button id="btn-cancelnewapikey" type="button" class="btn btn-danger">Cancel</button>
                        </form>
                    </div>
                </div>
            </div> <!-- /.col-lg-6 -->
        </div> <!-- END Form Row -->
        
        @stop
