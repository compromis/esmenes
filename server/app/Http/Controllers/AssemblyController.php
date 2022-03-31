<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assembly;

class AssemblyController extends Controller
{
    public function assemblies(Request $request)
    {
        $assemblies = Assembly::orderBy('id', 'desc')->get();
        $user = $request->user();

        // Remove assemblies that the user does not have access to
        $userAssemblies = $assemblies->filter(function ($assembly) use ($user) {
            return $user->canAttend($assembly);
        })->all();

        return $userAssemblies;
    }

    public function assembly(Assembly $assembly, Request $request)
    {
        // Establish if user can register amendments as regional spokesperson for this assembly
        $assembly->is_spokesperson = $request->user()->isSpokesperson($assembly);
        $assembly->amendments_open = time() < strtotime($assembly->amendment_deadline);
        $assembly->supports_open = time() < strtotime($assembly->support_deadline);

        return $assembly;
    }
}
