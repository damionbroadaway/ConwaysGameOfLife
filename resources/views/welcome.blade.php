<!DOCTYPE html>
<html>
    <head>
        <title>I am a generious God.</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ URL::asset('/assets/css/life.css') }}">
    </head>
    <body>
        <div class="container" style="width:100%;">
            <div class="row-fluid">
                <div class="col-lg-2">
                    <h4>Do Stuff</h4>
                    <a class="btn btn-success btn-block golGenesis" href="#" role="button"><span class="glyphicon glyphicon-play" aria-hidden="true"></span></a>
                    <a class="btn btn-danger btn-block disabled golApocalypse" href="#" role="button"><span class="glyphicon glyphicon-stop" aria-hidden="true"></span></a>
                    <a class="btn btn-info btn-block golReload" href="" role="button"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
                    <h4>Show Stuff</h4>
                    <ul class="list-group">
                        <li class="list-group-item">
                            Generation:
                            <span class="label label-success golGenerationDisplay pull-right"></span>
                        </li>
                        <li class="list-group-item">
                            Living Cells:
                            <span class="label label-success golLivingCellsDisplay pull-right"></span>
                        </li>
                        <li class="list-group-item">
                            Width:
                            <span class="label label-success golWidthDislplay pull-right"></span>
                        </li>
                        <li class="list-group-item">
                            Height:
                            <span class="label label-success golHeightDisplay pull-right"></span>
                        </li>
                    </ul>
                    <h4>Show Other Stuff</h4>
                    <div class="golAlertWrapper">
                        <div class="alert golAlert" role="alert" style="display:none;"></div>
                    </div>
                    <h4>Show Dev Stuff</h4>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Submitted by</h3>
                        </div>
                        <div class="panel-body">
                            Damion M Broadaway
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Repo</h3>
                        </div>
                        <div class="panel-body">
                            <a href="https://github.com/damionbroadaway/ConwaysGameOfLife" target="_blank">GitHub</a>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Demo</h3>
                        </div>
                        <div class="panel-body">
                            Add <code>10.22.1.128   your.mom</code> to your computer's host file.
                        </div>
                    </div>

                </div>
                <div class="col-lg-10">
                    {{--<div class="cell-row">--}}
                    {{--<div class="cell"></div>--}}
                    {{--<div class="cell"></div>--}}
                    {{--<div class="cell"></div>--}}
                    {{--<div class="cell"></div>--}}
                    {{--<div class="cell"></div>--}}
                    {{--<div class="cell"></div>--}}
                    {{--<div class="cell"></div>--}}
                    {{--</div>--}}
                    <div class="gol" data-generation="0" data-width="100" data-height="100" data-active="1">
                    </div>
                </div>
            </div>
        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="{{ URL::asset('/assets/js/life.js') }}"></script>
    </body>
</html>
