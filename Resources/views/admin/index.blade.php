@extends('cockpit::layouts.admin')


@section('page-content')
<div class="page-content">
    <h1 class="page-title"> Blank Page Layout
        <small>blank page layout</small>
    </h1>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{ $count['users'] }}">{{ $count['users'] }}</span>
                        </h3>
                        <small>USERS</small>
                    </div>
                    <div class="icon">
                        <i class="icon-user"></i>
                    </div>
                </div>
                <div class="progress-info">
                    <div class="progress">
                        <span style="width: 76%;" class="progress-bar progress-bar-success green-sharp">
                            <span class="sr-only">76% progress</span>
                        </span>
                    </div>
                    <div class="status">
                        <div class="status-title"> progress </div>
                        <div class="status-number"> 76% </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-red-haze">
                            <span data-counter="counterup" data-value="{{ $count['teams'] }}">{{ $count['teams'] }}</span>
                        </h3>
                        <small>TEAMS</small>
                    </div>
                    <div class="icon">
                        <i class="icon-users"></i>
                    </div>
                </div>
                <div class="progress-info">
                    <div class="progress">
                        <span style="width: 85%;" class="progress-bar progress-bar-success red-haze">
                            <span class="sr-only">85% change</span>
                        </span>
                    </div>
                    <div class="status">
                        <div class="status-title"> change </div>
                        <div class="status-number"> 85% </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="note note-info">
        <p> A black page template with a minimal dependency assets to use as a base for any custom page you create </p>
    </div>
</div>
<!-- END CONTENT BODY -->
@endsection