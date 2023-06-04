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

<script>
    function addExtraPoints() {
        let points = localStorage.getItem("points") || 0;
        points = Number(points)+22;

        localStorage.setItem("points", `${points}`);
    }
</script>
<section class="section">
    {{--    <div class="section" >--}}

    <div class="container-xl height100 containerSupportedContent">

        {{--    </div>--}}

        <div class="container-lg height70">
            <div class="row height100 justify-content-center">
                <div class="col-md-12 height100 sectioninner" style="align-self:flex-end;">


{{--                    @if ($message = Session::get('success'))--}}
                        <div class="alert alert-success">
{{--                            <p>{{ $message }}</p>--}}
                        </div>
{{--                    @endif--}}

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

{{--                            <a class="button" href="{{ route('next',$node->id) }}">Yes</a>--}}
{{--                            <a class="button" href="{{ route('next',$node->id) }}">No</a>--}}


                            @if ($relation === null)
{{--                                <form action="/add" method="post">--}}
                                    <form action="{{ route('Add') }}" method="POST">


                                    @csrf
                                    <h3>add character</h3>
                                    <input type="hidden" value="{{ $node->id }}" name="current_node" />

                                    <div class="form-group">
{{--                                        <label for="q">Question</label>--}}
{{--                                        <input type="text" name="question" id="q" autofocus />--}}
                                        <label>Question to distinguish character</label>

                                        <input type="text" name="question" class="form-control collumntextSupportedContent" placeholder="question">

                                    </div>

                                    <div class="form-group">
{{--                                        <label for="a">Answer</label>--}}
{{--                                        <input type="text" name="answer" id="a" />--}}
                                        <label>Final answer / Character</label>

                                        <input type="text" name="answer" class="form-control collumntextSupportedContent" placeholder="answer">

                                    </div>

                                        <button type="submit" onclick="addExtraPoints();">Submit</button>
                                    </form>
                            @else
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
                                <a class="button" href="#" onclick="addPoints('{{ route('Yes',$relation->id) }}')">Yes</a>
                                <a class="button" href="#" onclick="addPoints('{{ route('No',$relation->id) }}')">No</a>
                            @endif
{{----}}
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


