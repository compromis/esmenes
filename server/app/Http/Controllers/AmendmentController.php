<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAmendmentRequest;
use App\Models\Amendment;
use App\Models\Assembly;
use App\Models\Document;

class AmendmentController extends Controller
{
    public function new(Assembly $assembly, StoreAmendmentRequest $request)
    {
        $document = Document::where('assembly_id', $assembly->id)->where('ref', $request->input('document_ref'))->first();

        $amendment = new Amendment;
        $amendment->document_id = $document->id;
        $amendment->user_id = $request->user()->id;
        $amendment->article = $request->input('id');
        $amendment->type = 'modification';
        $amendment->original = $request->input('original');
        $amendment->amended = $request->input('text');
        $amendment->justification = $request->input('justification');
        $amendment->registered_by_assembly = $request->input('registered_by_assembly');
        $amendment->registered_by = ($amendment->registered_by_assembly) ? $request->user()->data('comarca') : $request->user()->fullName();
        $amendment->save();

        return $amendment;
    }
}
