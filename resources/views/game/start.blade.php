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


{{--------------------------------test---------------------------------}}
{{--<form method="POST" action="{{ route('loop_up') }}">--}}
{{--    @csrf--}}
{{--    <input type="hidden" name="node_id" value="{{ $node->id }}">--}}
{{--    <button type="submit">Trigger loop_up function</button>--}}
{{--</form>--}}



<form method="POST" action="{{ route('handle_loop_up') }}">
    @csrf
    <input type="hidden" name="node_id" value="11">
    <button type="submit">Execute handleLoopUpRequest for node ID 11</button>
</form>

{{--@if ($lastNodeId !== null)--}}
{{--    <p>Last Node ID from the loop: {{ $lastNodeId }}</p>--}}
{{--@else--}}
{{--    <p>No node ID found</p>--}}
{{--@endif--}}

{{--@extends('game.leaderboard')--}}
