<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;

use App\Models\Card;
use App\Models\ListItem;
use Illuminate\Http\Request;

use DB;
use Auth;
class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $data = Card::where('list_id',request()->list_id)->paginate(5);
            return response()->json($data,200);
        }

        $data['list'] = ListItem::find(request()->list_id);
        return view('cards.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                  'title' => 'required|unique:App\Models\Card,title',
                  'description' => 'required',
                  'completed' => 'required'
                  ]);
        $card = new Card($request->all());

        if(Auth::User()->cards()->save($card)){
            $progress_data = DB::table('cards')->select(DB::raw("count(*) AS total , count(IF(completed = 1, 1, null)) AS total_completed"))->where('list_id',$request->list_id)->first();
            $progress = round($progress_data->total_completed / $progress_data->total);
            ListItem::where('id',$request->list_id)->update(['progress'=>$progress]);

            if($request->ajax()){
                return response()->json('Created!',201);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
                  'title' => 'required|unique:App\Models\Card,title,'.$card->id,
                  'description' => 'required',
                  'completed' => 'required'
                  ]);
        if($card->update($request->all())){
            $progress_data = DB::table('cards')->select(DB::raw("count(*) AS total , count(IF(completed = 1, 1, null)) AS total_completed"))->where('list_id',$request->list_id)->first();
            $progress = round(($progress_data->total_completed / $progress_data->total)*100);
            ListItem::where('id',$request->list_id)->update(['progress'=>$progress]);

            if($request->ajax()){
                return response()->json('Updated!',200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        if($card->delete()){
            if(request()->ajax()){
                return response()->json('Deleted!',200);
            }
        }
    }
}
