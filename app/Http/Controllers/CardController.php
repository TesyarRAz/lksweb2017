<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Board;
use App\Models\BoardList;
use Illuminate\Http\Request;

class CardController extends Controller
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
    public function store(Request $request, Board $board, BoardList $list)
    {
        $validator = \Validator::make($request->all(), [
            'task' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        $data = $validator->validated();

        $data['order'] = optional($list->cards()->orderByDesc('order')->first())->order + 1 ?? 1;

        $list->cards()->create($data);

        return response(['message' => 'create card success']);
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
    public function update(Request $request, Board $board, BoardList $list, Card $card)
    {
        $validator = \Validator::make($request->all(), [
            'task' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        $card->update($validator->validated());

        return response(['message' => 'update card success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $card->delete();

        return response(['message' => 'delete card success']);
    }

    public function up(Request $request, Board $board, BoardList $list, Card $card)
    {
        if ($prev_card = $list->cards()->orderBy('order')->firstWhere('order', '<', $card->order))
        {
            // Swap List
            $current_order = $card->order;

            $card->update(['order' => $prev_card->order]);
            $prev_card->update(['order' => $current_order]);
        }

        return response(['message' => 'move success']);
    }

    public function down(Request $request, Board $board, BoardList $list, Card $card)
    {
        if ($next_card = $list->cards()->orderBy('order')->firstWhere('order', '>', $card->order))
        {
            // Swap List
            $current_order = $card->order;
            
            $card->update(['order' => $next_card->order]);
            $next_card->update(['order' => $current_order]);
        }

        return response(['message' => 'move success']);
    }

    public function moveList(Request $request, Card $card, BoardList $list)
    {
        $card->update(['list_id' => $list->id, 'order' => optional($list->cards()->orderByDesc('order')->first())->order ?? 1]);

        return response(['message' => 'move success']);
    }
}
