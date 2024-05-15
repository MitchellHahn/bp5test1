<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Node;
use App\Models\Relation;
use App\Models\Score;
use App\Models\SuccessNode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;



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



    //////////store chosen node in history
    public function set_node_history(Node $node) {


        // Search for the node in the Relation model
        $relation = Relation::where('node_yes', $node->id)
            ->orWhere('node_no', $node->id)
            ->first();

        $parentId = $relation->parent_node;


        History::create([
            'node' => $node->id,
            'name' => $node->name
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
            }

            // Check if current visited node is in the visited nodes list
            $isVisited = in_array($currentVisitedNode, $visitedNodeIds);
            // Store the visitation status of the node
            $results[$FourTimesNode] = $isVisited ? 'visited' : 'not visited';
        }

        // Extract the nodes that were visited
        $visitedNodeIds = [];
        foreach ($results as $nodeId => $status) {
            if ($status === 'visited') {
                $visitedNodeIds[] = $nodeId;
            }
        }

        // If visitedNodeIds is empty, return early
        if (empty($visitedNodeIds)) {
            return null; // or any other appropriate action
        }


      //create cash when SmartGuess is Executed
        Cache::increment('SmartGuess_Executed');

        Cache::put('SmartGuess_InputNode', $currentVisitedNode, 60);

//        $test = Cache::get('SmartGuess_InputNode');
//
//dd($test);

        // Continue with the rest of the function if visitedNodeIds is not empty
        $randomKey = array_rand($visitedNodeIds);
        $randomNodeId = $visitedNodeIds[$randomKey];

        $relation = Relation::where('node_yes', $randomNodeId)
            ->orWhere('node_no', $randomNodeId)
            ->first();

        if ($relation) {
            $parentId = $relation->parent_node;
            if ($parentId) {
                return $parentId;
            }
        }

        return null; // Return null if no parent ID found
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Node $node
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function yes(Node $node)
    {
        // get current node
        $currentNodeId = $node->id;

        // Capture click
        Cache::increment('click_count');

        // Retrieve click count
        $totalClicks = Cache::get('click_count', 0);

        $relation = Relation::where('parent_node', $currentNodeId)
            ->first();
        // get the node from kolom "node_no" in the same row as the $currentNodeId

        // fu
        if ($totalClicks == 2) {
//            $nodeIdToCheck = $currentNodeId;
            $smartGuessInputNodeId = $relation->node_yes;


            $smartGuessInputNode = Node::findOrFail($smartGuessInputNodeId);

            // Call SmartGuess method with the Node instance
            return $this->SmartGuess($smartGuessInputNode->id, $totalClicks);

        }

//        if (Cache::has('SmartGuess_Executed') && $node->question && Str::startsWith("answer", Str::lower($node->question))) {

        $answer = $node->answer;
        if (Cache::has('SmartGuess_Executed') && Node::where('answer', $answer)->exists()) {
            $parentNode = Node::where('answer', $answer)->first();

            if (Relation::where('parent_node', $node->id)->exists()) {
                $relation = Relation::where('parent_node', $node->id)->first();
                $nodeYes = $relation->node_yes;

                // Check if a SuccessNode already exists for the current nodeYes
                if (!SuccessNode::where('node', $nodeYes)->exists()) {
                    // Create the SuccessNode record
                    SuccessNode::create([
                        'node' => $nodeYes,
                    ]);

                    $latestSuccessNode = SuccessNode::latest()->first();
                    $latestSuccessNode->delete();

                    $this->set_node_history($nodeYes);

                    // Log information
                    Log::info('SuccessNode created for node with ID ' . $nodeYes);
                }
            } else {
                // Log that the conditions were not met
                Log::info('Conditions not met for creating SuccessNode for node with ID ' . $node->id);

                $this->set_node_history($node);

                return $this->Register_SmartGuess_SuccessNote($parentNode);
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



    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Node $node
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function no(Node $node)
    {


        // Capture each click
        Cache::increment('click_count');

        // Retrieve click count
        $totalClicks = Cache::get('click_count', 0);

        // get current node
        $currentNodeId = $node->id;


        $relation = Relation::where('parent_node', $currentNodeId)
            ->first();
        // get the node from kolom "node_no" in the same row as the $currentNodeId


//////////////////function execute SmartGues after the player has answered 2 questions//////////////////////
        if ($totalClicks == 2) {
            $smartGuessInputNodeId = $relation->node_no;

            $smartGuessInputNode = Node::findOrFail($smartGuessInputNodeId);
            return $this->SmartGuess($smartGuessInputNode->id, $totalClicks);
        }
        // dd($currentNodeId);

        $relation = null;
        /*
        $random_node_guess = $this->random_guess($node);

        if($random_node_guess) {
            $node = $random_node_guess;
        }
        */

        if (Cache::has('SmartGuess_Executed') && $node->question && Str::startsWith("answer", Str::lower($node->question))) {
//             Retrieve SmartGuess cache for input SmartGuess
            return $this->ResetTo_SmartGuessInputNode($node);

        }

        if($node->question && Str::startsWith("answer", Str::lower($node->question))) {
            $node->question = "Is this your character?";
        }
        if($node->relation !== null && $node->relation->id !== null) {
            $relation = $node->relation->no;
        }
        return view('game.no',compact('node', 'relation'));

    }



    public function SmartGuess($currentNodeId, $totalClicks) {
        Log::info("Entering SmartGuess with node ID: $currentNodeId and total clicks: $totalClicks");

        if ($totalClicks == 2) {
            $relation = Relation::where('parent_node', $currentNodeId)->first();
            $smartGuessPredictionNodeId = $this->handleLoopUpRequest($currentNodeId);

            Log::info("SmartGuessPredictionNodeId: $smartGuessPredictionNodeId");

            if ($smartGuessPredictionNodeId && $smartGuessPredictionNodeId != $currentNodeId) {
                return redirect()->route('SmartGuess', ['node' => $smartGuessPredictionNodeId, 'totalClicks' => $totalClicks]);
            } else {
                Log::warning("Redirect prevented to avoid a loop: SmartGuessPredictionNodeId ($smartGuessPredictionNodeId) is the same as currentNodeId ($currentNodeId)");
            }
        }


        // Example data to pass to the view, you may replace this as per your logic
        $node = Node::find($currentNodeId);

        // Pass the node and smartGuessPredictionNodeId to the view
        return view('SmartGuess', ['node' => $node, 'smartGuessPredictionNodeId' => $smartGuessPredictionNodeId]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Node $node
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ResetTo_SmartGuessInputNode(Node $node) {

        $SmartGuess_InputNode = Cache::get('SmartGuess_InputNode');
//dd($SmartGuess_InputNode);

        // Change SmartGuess cache status to failed
//        $value = Cache::get('SmartGuess_Executed');

        // Remove the 'SmartGuess_Executed' key
//        Cache::forget('SmartGuess_Executed');

        // Set the 'SmartGuess_Failed' key with the previous value
//        Cache::put('SmartGuess_Failed', $value);

        // Return the view with the node and SmartGuess_InputNode
        return view('SmartGuessFailed', ['node' => $node, 'SmartGuess_InputNode' => $SmartGuess_InputNode]);

    }


    public function Register_SmartGuess_SuccessNote(Node $node) {
        // Search for the node in the Relation model
        $relation = Relation::where('node_yes', $node->id)
            ->orWhere('node_no', $node->id)
            ->first();

        if ($relation) {
            $parentId = $relation->parent_node;

            // Create the SuccessNode record
            SuccessNode::create([
                'node' => $node->id,
            ]);

            return view('game.gameover');
        }



    function getCachedKeys()
    {
        $cachePath = storage_path('framework/cache/data');
        $files = File::allFiles($cachePath);
        $keys = [];

        foreach ($files as $file) {
            $fileName = $file->getFilename();
            // Process the filename to get the cache key
            $keys[] = $fileName;
        }

        return $keys;
    }}


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
