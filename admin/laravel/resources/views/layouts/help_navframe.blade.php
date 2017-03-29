@extends('layouts.basepage')

@section('body')
 <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle pull-left" style="margin-left:10px;" data-toggle="collapse" data-target="#sidebar-content">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url ('dashboard') }}">RemitBroker | Help</a>
                <button type="button" class="btn btn-danger btn-sm navbar-btn" style="margin-right:10px;" onclick="window.location='{{ url("logout") }}'">Sign Out</button>
            <!--</div>-->
            <!-- Collect the nav links, forms, and other content for toggling -->
            <!--<div class="collapse navbar-collapse" id="navbar-collapse-1">-->
                <ul class="nav navbar-nav" style="margin-left:10px;">
                    <li><p class="navbar-text">Signed in as {{ Auth::user()->name }} / {{ $remitter->name }}</p></li>
                    <!--<li><a href="#">Signed in as {{ Auth::user()->name }} / {{ $remitter->name }}</a></li>-->
                  </ul>
            </div>
            <!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse" id="sidebar-content">
                    <ul class="nav" id="side-menu">
                        <li {{ (Request::is('*dashboard') ? 'class="active"' : '') }}>
                            <a href="{{ url ('dashboard') }}"><i class="fa fa-th fa-fw"></i> Dashboard</a>
                        </li>
                        <li {{ (Request::is('*help/overview') ? 'class="active"' : '') }}>
                            <a href="{{ url ('help/overview') }}"><i class="fa fa-th fa-minus"></i> Overview<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li {{ (Request::is('*panels') ? 'class="active"' : '') }}>
                                    <a href="{{ url ('panels') }}">Process Flows</a>
                                </li>
                                <li {{ (Request::is('*buttons') ? 'class="active"' : '') }}>
                                    <a href="{{ url ('buttons' ) }}">Glossary</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li {{ (Request::is('*help/start') ? 'class="active"' : '') }}>
                            <a href="{{ url ('help/start') }}"><i class="fa fa-th fa-fw"></i> Getting Started</a>
                        </li>
                        <li {{ (Request::is('*dashboard') ? 'class="active"' : '') }}>
                            <a href="{{ url ('dashboard') }}"><i class="fa fa-th fa-fw"></i> Test Environment</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
			 <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header" style="margin-top:10px;">@yield('page_heading')</h2>
                </div>
                <!-- /.col-lg-12 -->
           </div>
			<div class="row">  
				@yield('section')

            </div>
            <!-- /#page-wrapper -->
        </div>
    </div>
@stop

