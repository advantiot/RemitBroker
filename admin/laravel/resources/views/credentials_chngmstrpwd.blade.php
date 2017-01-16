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
                <!-- Change Master Password panel -->
                <div id="pnl-chngmstrpwd" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Change Master Password:</h3>
                    </div>
            
                    <div class="panel-body">
                        <form role="form" method="POST" action="/credentials/confchngmstrpwd">
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label>New Master Password:</label>
                                <input id="txt-mstrpwd" name="master_password" type="password" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Confirm Master Password:</label>
                                <input id="txt-confmstrpwd" name="confirm_master_password" type="password" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <button id="btn-confchngmstrpwd" name="submit" value="confchngmstrpwd" type="submit" class="btn btn-success">Confirm Change</button>
                                <button id="btn-cnclchngmstrpwd" type="button" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                        {{-- Display errors generated by the validator --}}
                        @if (count($errors) > 0)
                        <!-- ./ Error div -->
                        <div id="div-errors" class="alert alert-danger">
                            <div class="list-group" style="margin-bottom:0px;">
                                @foreach ($errors->all() as $error)
                                <span class="list-group-item">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div> <!-- ./ Error div -->
                        @endif
                    </div>
                </div>
            </div> <!-- /.col-lg-6 -->
        </div> <!-- END Form Row -->
        
        @stop
