<?php //@extends('layouts.app')?>
{{--@extends('layouts.user.functietoevoegen.layout')--}}
<h4>Game</h4>
<br>
start
{{--@foreach($nodes as $node)--}}
    <div>{{ $node->question }}</div>
    <div>{{ $node->asnwer }}</div>

    <a class="button" href="{{ route('Yes',$node->id) }}">Yes</a>
    <a class="button" href="{{ route('No',$node->id) }}">No</a>

{{--@endforeach--}}


{{--@extends('game.leaderboard')--}}
