<?php

namespace App\Http\Controllers;

use App\Models\ListItem;
use Illuminate\Http\Request;

use Auth;
class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $data = ListItem::with(['cards'])->where('user_id',Auth::id())->paginate(5);
            return response()->json($data,200);
        }
        return view('lists.index');
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
                  'title' => 'required|unique:App\Models\ListItem,title'
                  ]);
        
        if(Auth::User()->lists()->save(new ListItem(['title' => $request->title]))){
            return response()->json('Saved!',201);
        }else{
            return response()->json('Not Saved!',200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\Response
     */
    public function show(ListItem $listItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ListItem $listItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListItem $listItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListItem $listItem)
    {
        //
    }
}
