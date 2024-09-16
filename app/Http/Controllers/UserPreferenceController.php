<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{

    public function index()
    {
        $preferences = UserPreference::where('user_id', Auth::id())->get();
        return response()->json($preferences);
    }


    public function store(Request $request, $id)
    {

        $preference = UserPreference::create([
            'user_id' => Auth::id(),
            'cat_id' => $id,
        ]);

        return response()->json([
            'message' => 'Preference saved successfully!',
            'preference' => $preference,
        ], 201);
    }




    public function destroy($id)
    {
        $preference = UserPreference::where('user_id', Auth::id())->findOrFail($id);
        $preference->delete();

        return response()->json([
            'message' => 'Preference deleted successfully!',
        ]);
    }
}
