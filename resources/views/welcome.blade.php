<!DOCTYPE html>
<html>
    <head>
        <title>I am a generious God.</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ URL::asset('/assets/css/life.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    {{--<br />--}}
                    {{--<ul class="list-group">--}}
                        {{--<li class="list-group-item">--}}
                            {{--<span class="badge golGenerationDisplay">0</span>--}}
                            {{--Generation--}}
                        {{--</li>--}}
                        {{--<li class="list-group-item">--}}
                            {{--<span class="badge golWidthDislplay">0</span>--}}
                            {{--Width--}}
                        {{--</li>--}}
                        {{--<li class="list-group-item">--}}
                            {{--<span class="badge golHeightDisplay">0</span>--}}
                            {{--Height--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </div>
                <div class="col-lg-4">
                    {{--<br />--}}
                    {{--<a class="btn btn-success golGenesis" href="#" role="button">Genesis</a>--}}
                    {{--<a class="btn btn-danger disabled golApocalypse" href="#" role="button">Apocalypse</a>--}}
                    {{--<a class="btn btn-info disabled golReload" href="" role="button">Reload</a>--}}
                </div>
                <div class="col-lg-4">
                    Empty1
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1">
                    <div class="btn-group-vertical" role="group" aria-label="...">
                        <a class="btn btn-success golGenesis" href="#" role="button">Genesis</a>
                        <a class="btn btn-danger disabled golApocalypse" href="#" role="button">Apocalypse</a>
                        <a class="btn btn-info disabled golReload" href="" role="button">Reload</a>
                    </div>
                </div>
                <div class="col-lg-11">
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
