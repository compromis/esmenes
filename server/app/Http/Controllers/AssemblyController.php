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
            $filters = json_decode($assembly->filter);
            $userdata = json_decode($user->data);
            $checks = true;

            foreach($filters as $filterName => $filterValue) {
                if ($userdata->$filterName != $filterValue) {
                    $checks = false;
                }
            }

            return $checks;
        })->all();

        return response()->json($userAssemblies);
    }
}
