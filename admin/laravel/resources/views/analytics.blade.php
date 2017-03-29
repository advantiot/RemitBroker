@extends('layouts.navframe')
@section('page_heading','Analytics')
@section('section')

        <!-- /.row -->
        <!--<div class="col-sm-12">-->
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
@stop
