<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;

use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(auth()->user()->boards);
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
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        $data = $validator->validated();

        $data['user_id'] = auth()->id();

        auth()->user()->boards()->create($data);

        return response(['message' => 'create board success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        $board->load('members', 'lists', 'lists.cards');

        return response($board);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        $data = $validator->validated();

        $board->update($data);

        return response(['message' => 'update board success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        $board->delete();

        return response(['message' => 'delete board success']);
    }

    public function storeMember(Request $request, Board $board)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        if (!$user = User::firstWhere($validator->validated()))
        {
            return response(['message' => 'user did not exists'], 422);
        }

        $board->members()->attach($user->id);

        return response(['message' => 'add member success']);
    }

    public function destroyMember(Request $request, Board $board, $member)
    {
        if (!$user = User::firstWhere(['username' => $member]))
        {
            return response(['message' => 'user did not exists'], 422);
        }

        $board->members()->detach($user->id);

        return response(['message' => 'destroy member success']);
    }
}
