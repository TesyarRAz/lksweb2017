<?php

namespace App\Http\Controllers;

use App\Models\BoardList;
use App\Models\Board;

use Illuminate\Http\Request;

class BoardListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, Board $board)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        $data = $validator->validated();

        $data['order'] = optional($board->lists()->orderByDesc('order')->first())->order + 1 ?? 1;

        $board->lists()->create($data);

        return response(['message' => 'create list success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BoardList  $list
     * @return \Illuminate\Http\Response
     */
    public function show(BoardList $list)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BoardList  $list
     * @return \Illuminate\Http\Response
     */
    public function edit(BoardList $list)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BoardList  $list
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board, BoardList $list)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        $list->update($validator->validated());

        return response(['message' => 'update list success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BoardList  $list
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board, BoardList $list)
    {
        $list->delete();

        return response(['message' => 'delete list success']);
    }

    public function left(Request $request, Board $board, BoardList $list)
    {
        if ($prev_list = $board->lists()->orderBy('order')->firstWhere('order', '<', $list->order))
        {
            // Swap List
            $current_order = $list->order;

            $list->update(['order' => $prev_list->order]);
            $prev_list->update(['order' => $current_order]);
        }

        return response(['message' => 'move success']);
    }

    public function right(Request $request, Board $board, BoardList $list)
    {
        if ($next_list = $board->lists()->orderBy('order')->firstWhere('order', '>', $list->order))
        {
            // Swap List
            $current_order = $list->order;
            
            $list->update(['order' => $next_list->order]);
            $next_list->update(['order' => $current_order]);
        }

        return response(['message' => 'move success']);
    }
}
