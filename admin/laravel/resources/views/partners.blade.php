@extends('layouts.navframe')
@section('page_heading','Partners')
@section('section')

        <!-- /.row -->
        <div class="col-sm-12">
        <!-- /.row -->

        <div class="row"> <!-- START Table Row -->
            <div class="col-lg-2">
                <!-- Add a Partner -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Add A Partner</h3>
                    </div>
            
                    <div class="panel-body">
                        <form role="form" method="POST" action="/partners/add">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <label>Remitter Id</label>
                                <input class="form-control" placeholder="" name="remitter_id" value="" type="text">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Add</button>
                            </div>
                        </fieldset>
                        </form>
                    </div>
                </div> <!-- Panel -->
            </div> <!-- /.col-lg-4 -->

            <div class="col-lg-10">
                <!-- Find a Partner -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Find A Partner</h3>
                    </div>
            
                    <div class="panel-body">
                        <!-- Since find is reloading the main page with parameters there is a get and post route for /partners -->
                        <!-- Since two form actions exist on the same page there is a routing issue from one nested route to another (add after find) -->
                        <form role="form" method="POST" action="/partners">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="row">
                                <div class="form-group col-lg-2">
                                    <label>Remitter Id:</label>
                                    <input class="form-control" placeholder="" name="remitter_id" value="{{ old('remitter_id') }}" type="text">
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Remitter Name:</label>
                                    <input class="form-control" placeholder="" name="remitter_name" value="{{ old('remitter_name') }}" type="text">
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Remitter Country:</label>
                                    <select class="form-control" name="remitter_country">
                                        <option value="-1" @if (old('remitter_country') == '-1') selected="selected" @endif>Any</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->country_code }}" @if (old('remitter_country') == $country->country_code) selected="selected" @endif>{{ $country->country_code }}</option>
                                        @endforeach
                                        <!--
                                        <option value="USA" @if (old('remitter_country') == 'USA') selected="selected" @endif>USA</option>
                                        <option value="GBR" @if (old('remitter_country') == 'GBR') selected="selected" @endif>UK</option>
                                        <option value="FRA" @if (old('remitter_country') == 'FRA') selected="selected" @endif>France</option>
                                        <option value="IND" @if (old('remitter_country') == 'IND') selected="selected" @endif>India</option>
                                        <option value="CHN" @if (old('remitter_country') == 'CHN') selected="selected" @endif>China</option>
                                        -->
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Self Status:</label>
                                    <select class="form-control" name="self_status">
                                        <option value="-1" @if (old('self_status') == '-1') selected="selected" @endif>Any</option>
                                        <option value="1" @if (old('self_status') == '1') selected="selected" @endif>Active</option>
                                        <option value="0" @if (old('self_status') == '0') selected="selected" @endif>Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Partner Status:</label>
                                    <select class="form-control" name="partner_status">
                                        <option value="-1" @if (old('partner_status') == '-1' || old('partner_status') == '') selected="selected" @endif>Any</option>
                                        <option value="1" @if (old('partner_status') == '1') selected="selected" @endif>Active</option>
                                        <option value="0" @if (old('partner_status') == '0') selected="selected" @endif>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label>Service Type:</label>
                                    <select class="form-control" name="service_type">
                                        <option value="-1" @if (old('service_type') == '-1') selected="selected" @endif>Any</option>
                                        <option value="1" @if (old('service_type') == '1') selected="selected" @endif>Send</option>
                                        <option value="2" @if (old('service_type') == '2') selected="selected" @endif>Payout</option>
                                        <option value="3" @if (old('service_type') == '3') selected="selected" @endif>All</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-9">
                                    <label></label><br/>
                                    <button type="submit" class="btn btn-success pull-right">Find</button>
                                </div>
                            </div>
                        </fieldset>
                        </form>
                    </div>
                </div> <!-- Panel -->
            </div> <!-- /.col-lg-4 -->
        </div> <!-- END Table Row -->
        
        <div class="row"> <!-- START Error/Success Messages Row -->
            <div class="col-lg-12">
                    {{-- Display errors generated by the validator --}}
                    @if (count($errors) > 0)
                        <div id="div-errors" class="alert alert-danger">
                            <div class="list-group" style="margin-bottom:0px;">
                                @foreach ($errors->all() as $error)
                                <span class="list-group-item">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
            </div>
        </div>

        <div class="row"> <!-- START Table Row -->
            <div class="col-lg-12">
                <!-- Partner Details -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Partners</h3>
                    </div>
            
                    <div class="panel-body">
                        @if (!empty($partners))
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width:10%">Remitter ID</th>
                                <th style="width:25%">Remitter Name</th>
                                <th style="width:20%">First Activated On</th>
                                <th style="width:15%">Self Status</th>
                                <th style="width:15%">Partner Status</th>
                                <th style="width:10%">Public Key</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($partners as $partner)
                            <tr>
                                <td>{{ $partner->partner_id }} </td>
                                <td>{{ $partner->name }}<br/>({{ $partner->country_code }} / {{ $partner->currency_code }})</td>
                                <td>{{ date('d-M-Y,H:i:s', strtotime($partner->created_at)) }} </td>
                                <td>
                                    @if ($partner->status == 0)
                                    <span style="color:red;font-weight:bold;"><i class="fa fa-circle fa-2x"></i>
                                    <button id="{{ $partner->partner_id }}" type="button" class="btn btn-success btn-sm pull-right changepartnerstatus">Activate</button>
                                    @elseif ($partner->status == 1)
                                    <span style="color:green;font-weight:bold;"><i class="fa fa-circle fa-2x"></i>
                                    <button id="{{ $partner->partner_id }}" type="button" class="btn btn-danger btn-sm pull-right changepartnerstatus">Deactivate</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($partner->partner_status == 0)
                                    <span style="color:red;font-weight:bold;"><i class="fa fa-circle fa-2x"></i>
                                    @elseif ($partner->partner_status == 1)
                                    <span style="color:green;font-weight:bold;"><i class="fa fa-circle fa-2x"></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ asset('pubkeys/' . $partner->partner_id . '_pubkey.pem')  }}" target="_blank">Download</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>            
                        @else
                            <div class="alert alert-warning">No partners found. Please try other search parameters.</div>
                        @endif
                    </div> <!-- END panel body -->
                </div>
                <!-- ./ Transaction Send Counts -->
            </div> <!-- /.col-lg-12 -->
        </div> <!-- END Table Row -->
        
        @stop
