<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Node;
use App\Models\Relation;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use function Ramsey\Uuid\setNodeProvider;

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

        // Reset cache
        $this->reset_teller_cache();

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
        $currentNodeId = $node->id;

        // Capture click
        Cache::increment('click_count');

        // Retrieve click count
        $totalClicks = Cache::get('click_count', 0);

        if ($totalClicks == 2) {
            $nodeIdToCheck = $currentNodeId;
            $parentId = $this->handleLoopUpRequest($nodeIdToCheck);

            if ($parentId) {
                return redirect()->route('Yes', ['node' => $parentId]);
            }
        }

        if ($node->relation) {
            if ($node->question && Str::startsWith("answer", Str::lower($node->question))) {
                $node->question = "Is this your character?";
            }
            $relation = $node->relation->yes;
            return view('game.yes', compact('node', 'relation'));
        } else {
            $this->set_node_history($node);
            return view('game.gameover');
        }
    }


    //////////store chosen node in history
   public function set_node_history(Node $node) {


            // Search for the node in the Relation model
            $relation = Relation::where('node_yes', $node->id)
                ->orWhere('node_no', $node->id)
                ->first();

            $parentId = $relation->parent_node;


        History::create([
            'node' => $node->id,
            'parent_node' => $parentId
        ]);


    }

    public function handleLoopUpRequest($currentVisitedNode) {
        $histories = History::select('node')
            ->groupBy('node')
            ->havingRaw('COUNT(*) > 3')
            ->get();

        $FourTimesNodes = $histories->pluck('node')->toArray();
        $results = [];

        foreach ($FourTimesNodes as $FourTimesNode) {
            // Initialize an array to store visited node IDs
            $visitedNodeIds = [];

            // Find the node based on the ID
            $node = Node::find($FourTimesNode);

            // Check if the node exists
            if ($node) {
                // Loop until there is no parent node found
                while ($node !== null) {
                    // Search for the node in the Relation model
                    $relation = Relation::where('node_yes', $node->id)
                        ->orWhere('node_no', $node->id)
                        ->first();

                    if ($relation) {
                        // Get the parent node
                        $parentId = $relation->parent_node;

                        // Check if $parentId is an integer (node ID)
                        if (is_int($parentId)) {
                            // Store the visited node ID
                            $visitedNodeIds[] = $node->id;

                            // Update $node for the next iteration
                            $node = Node::find($parentId);
                        } else {
                            // If $parentId is not an integer, exit the loop
                            $node = null;
                        }
                    } else {
                        // If no parent node is found, exit the loop
                        $node = null;
                    }
                }
            } else {
                // Handle the case where the node with the given ID doesn't exist
            }



            // Check if node is in loop nodes list
//            $nodeIdToCheck = 4; // Node ID to check
            $isVisited = in_array($currentVisitedNode, $visitedNodeIds);

            // Store the visitation status of the node
            $results[$FourTimesNode] = $isVisited ? 'visited' : 'not visited';
        }

        $visitedNodeIds = [];

        foreach ($results as $nodeId => $status) {
            if ($status === 'visited') {
                $visitedNodeIds[] = $nodeId;
            }
        }

        foreach ($visitedNodeIds as $nodeId) {
            echo "Node $nodeId is visited.\n";
        }

//        dd($isVisited);

        $randomKey = array_rand($visitedNodeIds);
        $randomNodeId = $visitedNodeIds[$randomKey];

//        $randomNodeParentId = $this->getParentNodeId($randomNodeId);

        $relation = Relation::where('node_yes', $randomNodeId)
            ->orWhere('node_no', $randomNodeId)
            ->first();

        $parentId = $relation->parent_node;





        if($parentId) {
            return $parentId;
        }


        return null; // Return null if no parent ID found
    }

//        echo "Random NodeID: {$randomNodeId}";


//        return back()->with('visitedNodes', $visitedNodeIds);
//    }

    function set_parent_node_history($nodeId = null) {
        if ($nodeId === null) {
            // If no node ID is provided, return without performing any action
            return;
        }

        while ($nodeId !== null) {
            // Search for the node in the Relation model
            $relation = Relation::where('node_yes', $nodeId)
                ->orWhere('node_no', $nodeId)
                ->first();

            if ($relation) {
                // Get the id of the row
                $parentId = $relation->parent_node;
                // Store the parent node id in the History model or perform any other action
                // Example: History::create(['parent_node_id' => $parentId]);
                // dd($parentId);
                // Update $nodeId for the next iteration
                $nodeId = $parentId;

            } else {
                // If no parent node is found, exit the loop
                break;
            }
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


        $currentNodeId = $node->id;

        // Capture click
        Cache::increment('click_count');

        // Retrieve click count
        $totalClicks = Cache::get('click_count', 0);
//        dd($totalClicks);

        if($totalClicks == 2) {

            $nodeIdToCheck = $currentNodeId;
            $this->handleLoopUpRequest($nodeIdToCheck);
        //

        }
        // dd($currentNodeId);







        $relation = null;
        /*
        $random_node_guess = $this->random_guess($node);

        if($random_node_guess) {
            $node = $random_node_guess;
        }
        */
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
        $this->reset_teller_cache();
        return view('game.gameover');
    }

    /**
     * Leeg teller cache
     */
    public function reset_teller_cache() {
        Cache::clear();
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
