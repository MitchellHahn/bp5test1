{{--@extends('layouts.app')--}}

{{--@extends('layouts.user.functietoevoegen.toeslag.layout')--}}


{{--@section('content')--}}

    <?php //pagina van ZZPer module waar hij/zij uren en toeslag kunnen invoeren en facturen aanmaken?>
    <?php // uren en toeslag toevoegen?>

    {{--    <style>--}}
    {{--        body {--}}
    {{--            height: 100%;--}}
    {{--            margin: 0;--}}
    {{--        }--}}
    {{--    </style>--}}

    {{--    <meta charset="utf-8" />--}}
    {{--    <style>--}}
    {{--        .overlay {--}}
    {{--            position: fixed;--}}
    {{--            width: 100%;--}}
    {{--            height: 100%;--}}
    {{--            left: 0;--}}
    {{--            top: 0;--}}
    {{--            background: rgba(51,51,51,0.7);--}}
    {{--            z-index: 10;--}}
    {{--        }--}}
    {{--    </style>--}}
    <section class="section">
        {{--    <div class="section" >--}}

        <div class="container-xl height100 containerSupportedContent">

            {{--    </div>--}}

            <div class="container-lg height70">
                <div class="row height100 justify-content-center">
                    <div class="col-md-12 height100 sectioninner" style="align-self:flex-end;">


                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        {{--    <div class="container-lg">--}}

                        <div class="row justify-content-center">
                            <div class="col-sm-5">
                                {{--                <div class="row justify-content-center" >--}}
                                {{--                    <div class="col-8 col-sm-5">--}}
                                {{--                        <strong>Datum:</strong>--}}
                                {{--                    </div>--}}
                                {{--                    <div class="col-8 col-sm-7" >--}}
                                {{--                        <label>--}}
                                {{--                            <input type="text" name="datum" value="{{  $toeslag->datum }}" class="form-control" placeholder="datum">--}}
                                {{--                        </label>--}}
                                {{--                    </div>--}}
                                {{--                </div>--}}
yes
                                <div class="row justify-content-center">
                                    <div class="col-8 col-sm-7">
                                        <span class="timer">5 seconds left.<br /><br /></span>
                                        <label>
{{--                                            <input type="number" name="node_yes" value="{{ $node->question }}" class="form-control" placeholder="node_yes">--}}
                                         {{ $relation->question }}
                                         {{ $relation->answer }}
                                        </label>
                                    </div>
                                </div>

{{--                                <div class="row justify-content-center">--}}
{{--                                    <div class="col-8 col-sm-5">--}}
{{--                                        <strong>Uurtarief:</strong>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-8 col-sm-7">--}}
{{--                                        <label>--}}
{{--                                            <input type="text" name="bedrag" value="{{ $toeslag->tarief->bedrag }}" class="form-control" placeholder="bedrag">--}}
{{--                                            <input type="text" name="bedrag" value="{{ $relation->node_yes->node }}" class="form-control" placeholder="bedrag">--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <input type="hidden" value="{{ $node->id }}" name="current_node" />

                                <a class="button" href="#" onclick="addPoints('{{ route('Yes',$relation->id) }}')">Yes</a>
                                <a class="button" href="#" onclick="addPoints('{{ route('No',$relation->id) }}')">No</a>
                                {{--            </div>--}}
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{--</div>--}}

        <script defer src="http://localhost/bp5test1/public/js/game.js"></script>
    </section>


{{--@endsection--}}


