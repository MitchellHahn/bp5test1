<style>
    ul {
        list-style: none;
        padding: 0;
    }
    ul > li {
        padding: 10px;
    }
</style>

<h3>Leaderboard</h3>
<ul id="leaderboard"></ul>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="container-xl containerSupportedContent" >
    <table class="table table-bordered">
        <tr class="tablehead tableheadSupportedContent">
            {{--      toon Klantnaam ,Debnummer, Straat, Huisnummer, Postcode, Stad en Land in het hoofd van de tabel      --}}

            <th class="tableheadfont tableheadfontSupportedContent">Naam</th>

            <th class="tableheadfont tableheadfontSupportedContent">Score</th>
            <th class="tableheadfont tableheadfontSupportedContent"></th>

        </tr>
        @foreach ($scores as $score)

            <tr class="tablerow">
                {{-- toont de gegeven en tabel op deze manier op mobiel aparaten--}}
                <td class="tablerowcell tablerowcellSupportedContent">
                    {{ $score->naam	 }}
                    <div class=" tablerowcellSupportedContent">
                        {{ $score->score }}<br />

                    </div>

                    {{-- toont de Klantnaam ,Debnummer, Straat, Huisnummer, Postcode, Stad en Land van elke elke klant van de ingelogde gebruiker in de tabel --}}
                </td>
                <td class="tablerowcell tablerowcellSupportedContent">{{ $score->score }}</td>

                <td class="tablerowcell">
                    {{-- functie dat de klant van de ingelogde gebruiker verwijdert --}}

                </td>
            </tr>
        @endforeach

        <script src="{{ asset('js/game.js') }}?v={{ time() }}"></script>
<script>
    // getLeaderboard();
</script>
