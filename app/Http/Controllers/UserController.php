<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUser $request)
    {
        $user = new User;

        $user->name = $request->name;
        $user->first_name = $request->first_name;
        $user->email = $request->email;

        $user->save();

        return response()->json(['message' => "L'utilisateur a été créé."]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'first_name' => $request->first_name,
            'email' => $request->email,
        ]);

        return response()->json(['message' => "Les informations de l'utilisateur ont été mises à jour."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => "L'utilisateur a été supprimé."]);
    }
}
