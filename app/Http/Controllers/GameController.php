<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Node;
use App\Models\Relation;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Node $node
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Node $node)
    {


        // $tijd->toeslag_id = Toeslag::where('user_id', auth()->user()->id)->latest('created_at')->first()->id;

//         $nodes = node::where('id','1')->first()->id;

        if(!$node->id) {
            return redirect("/start/1");
        } else {
            return view('game.start', compact('node'));
        }

//        $NodeID = request()->page ?? 1; // First node
//        $questionNodes = node::where('id', $NodeID)->get();
//
//        return view('game.start', compact('questionNodes'))
//            ->with('nodes', $questionNodes)
//            ->with("test", "TEST");
            if ($node->relation === null) {



//                $newnode = new node();
//                $newrelation = new relation();
//                $newrelation->rels

            }
        }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Node $node
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function yes(Node $node)
    {

//        $relation = node::where('relation');
//        dump($node->id);

//        dd($relation->question);

//        $node = $relation->node_yes->get();
//        dump($relation);
//        $node = $relation->node_yes;

        if($node->relation) {
            $random_guess = $this->node_guess();

            if($random_guess || ($node->question && Str::startsWith("answer", Str::lower($node->question)))) {
                $node->question = "Is this your character?";
            }
            if($random_guess) {
                $relation = $random_guess->relation->yes;
            } else {
                $relation = $node->relation->yes;
            }
            return view('game.yes', compact('node', 'relation'));
        } else {
            $this->node_guess_store($node);
            return view('game.gameover');
        }
    }

    function node_guess_store(Node $node) {
        History::insert([
            "datum" => date('Y-m-d H:i'),
            "node" => $node->id
        ]);
    }

    function node_guess() {
        $node_history = History::orderBy('datum', 'desc')->toArray();
        $random = array_rand($node_history, 1);
        $node = Relation::where('node_yes', $random[0]->node);

        if($node) {
            return $node;
        } else {
            return null;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Node $node
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function no(Node $node)
    {
        $relation = null;
        $random_node_guess = $this->random_guess($node);

        if($random_node_guess) {
            $node = $random_node_guess;
        }
        if($node->question && Str::startsWith("answer", Str::lower($node->question))) {
            $node->question = "Is this your character?";
        }
        if($node->relation !== null && $node->relation->id !== null) {
            $relation = $node->relation->no;
        }
        return view('game.no',compact('node', 'relation'));

    }



    ///voor het toevoegen van een de juiste character en een vraags als de gebruiker heeft gewonnen.
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @param  \App\Models\Node $node
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function store(Node $node, Relation $relation, request $request)
    {

        //////node aanmaken en opslaan
        $request->validate([
            //table tijd
            'question' => 'required',
            'answer' => 'required',

        ]);

        $data = $request->except(['_token', 'current_node']);

        // dd($node);

        // dd(Node::latest()->first());

        foreach($data as $key => $value) {
            $newNode = Node::create([
                $key => $value
            ]);

            $newNode->timestamps = false;
            $newNode->save();

            if($key === "answer") {
                $newrelation = new Relation();
                $newrelation->timestamps = false;
                // $newrelation->node_yes = Node::orderBy("id")->first()->id;
                $newrelation->node_yes = $newNode->id;

                $newrelation->node_no = $request->input("current_node");
                $newrelation->parent_node = Node::orderBy('id', 'desc')->skip(1)->take(1)->get()->first()->id;
                $newrelation->save();

                $oldRelation = Relation::where('node_no', $request->current_node)->orWhere('node_yes', $request->current_node)->first();
                if($oldRelation->node_no === $request->current_node) {
                    $oldRelation->node_yes = $newrelation->parent_node;
                } else {
                    $oldRelation->node_no = $newrelation->parent_node;
                }
                $oldRelation->save();
            }
        }




        /*
        foreach($data as $key => $value) {
            $newNode = Node::create([
                "question" => $value,
                "answer" => $value
            ]);
            $newNode->save();

            $newrelation = new Relation();
            $newrelation->node_yes = $newNode->id;
            $newrelation->parent_node = Relation::where()->latest('created_at')->first()->id;


            //foute node/character wordt altijd naar node_no in de nieuwe relatie verplaats
            $newrelation->node_no = $node->id;

            $newrelation->save();

            dd($newrelation);

            //om de voorgesteld note te ontvangen
            $oldRelation = Relation::where('node_yes', $request->current_node)->first();
        }
        */



        //////////////////////////////scorebouard//////////////////////////////////////////

        ///////////////////////////////////////////

        return redirect("/start");
        // return view('game.start', compact('node', 'relation'));



    }

    ////////////////scoreboard///////////
    /**
     * deze functie haalt alle gebruikers en administrators op van tabel "users"
     * toont via de "gebruikers" view (blade) alle gebruikers en administrators op van tabel "users"
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function leaderboard()
    {

        // toont via de "User" model, alle gebruikers en administrators van tabel users
        // sorteert alle gebruikers en administrators op alphabetische volgorde
        $scores = Score::orderBy('score', 'DESC')->paginate(1000);

        return view('game.leaderboard',compact('scores'))
            ->with('i', (request()->input('page', 1) - 1) * 4);
    }

    /////////////////score doorgegven////////////////
    /**
     * deze functie toont de registratie forumulier (pagina 'registratie') waarin accounts kunnn worden aangemaakt
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function score_invoer()
    {
        // toont de view (blade bestand) "registratie"
        return view('game.gameover');
    }

    /////////////////score opslaan//////////////////////
    /**
     * deze functie registreert een toeslag aan een zpper.
     * Maakt een nieuwe rij aan in tabel "toeslagen".
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Score $score
     * @return \Illuminate\Http\RedirectResponse
     */
    public function score_opslaan(Request $request, Score $score)
    {

        // haalt de ingevulde data op van alle invoer vakken van de "toeslag aanmaken" venster
        // controleert als de "required" vakken ingevuld zijn
        $request->validate([
            //tabel toeslagen
            'naam' => '',
            'score' => '',

        ]);

        // maakt een nieuwe toeslag aan van de doorgeven gegevens
        $score = Score::create($request->all());

        // slaat de nieuwe toeslag op met de doorgegeven gegevens op in table "toeslagen"
        // maakt gebruik van de model "Toeslag"
        $score->save();

        $scores = Score::orderBy('score', 'DESC')->get();

        // als de toeslag is geregistreerd, redirect het systeem weer naar de "toeslag" pagina van de medewerkers module (Toeslagen.blade.php)
        return view('game.leaderboard', compact('scores'))
            ->with('success', 'score is opgeslagen');
    }

}
