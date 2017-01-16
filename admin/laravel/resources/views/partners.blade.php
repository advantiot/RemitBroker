@extends('layouts.navframe')
@section('page_heading','Partners')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">
        <!-- /.row -->

        <div class="row"> <!-- START Table Row -->
            <div class="col-lg-12">
                <!-- Add a Partner -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Add A Partner</h3>
                    </div>
            
                    <div class="panel-body">
                        <form role="form" method="POST" action="/login">
                        {{ csrf_field() }}
                        <fieldset>
                            <!-- Hide remitter id and master password, not used for validation currently -->
                            <!--
                            <div class="form-group">
                                    <input class="form-control" placeholder="Remitter Id" name="remitter_id" value="" type="" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Master Password" name="master_password" type="password" value="">
                            </div>
                            <hr />
                            -->
                            <div class="form-group">
                                <input class="form-control" placeholder="User Email" name="email" value="" type="email">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="User Password" name="user_password" type="password" value="secret">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                            </div>
                        </fieldset>
                        </form>
                    /div>
                </div> <!-- Panel -->
            </div> <!-- /.col-lg-12 -->
        </div> <!-- END Table Row -->
        
        <div class="row"> <!-- START Table Row -->
            <div class="col-lg-12">
                <!-- Partner Details -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Partners</h3>
                    </div>
            
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width:10%">Remitter ID</th>
                                <th style="width:25%">Remitter Name</th>
                                <th style="width:20%">First Activated On</th>
                                <th style="width:35%">Status</th>
                                <th style="width:10%">Public Key</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($partners as $partner)
                            <tr>
                                <td>{{ $partner->partner_id }} </td>
                                <td>{{ $partner->name }} </td>
                                <td>{{ date('d-M-Y,H:i:s', strtotime($partner->created_at)) }} </td>
                                <td>
                                    @if ($partner->status == 0)
                                    <span style="color:red;font-weight:bold;">Inactive <i>(since {{ $partner->updated_at }})</i></span>          
                                    <button type="button" class="btn btn-success btn-sm pull-right">Activate</button></td>
                                    @elseif ($partner->status == 1)
                                    <span style="color:green;font-weight:bold;">Active <i>(since {{ $partner->updated_at }})</i></span>          
                                    <button type="button" class="btn btn-danger btn-sm pull-right">Deactivate</button></td>
                                    @endif
                                <td><button type="button" class="btn btn-primary btn-sm">Download</button></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>            
                    </div>
                </div>
                <!-- ./ Transaction Send Counts -->
            </div> <!-- /.col-lg-12 -->
        </div> <!-- END Table Row -->
        
        @stop
