<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assembly;

class AssemblyController extends Controller
{
    public function assemblies(Request $request)
    {
        $assemblies = Assembly::all();
        $user = $request->user();

        // Remove assemblies that the user does not have access to
        $userAssemblies = $assemblies->filter(function ($assembly) use ($user) {
            return $user->canAttend($assembly);
        })->all();

        return response()->json($userAssemblies);
    }

    public function assembly(Assembly $assembly, Request $request)
    {
        $request->user()->checkAttendance($assembly);
        return response()->json($assembly);
    }
}
