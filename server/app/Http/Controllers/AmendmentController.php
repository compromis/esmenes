<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAmendmentRequest;
use App\Http\Requests\SupportAmendmentRequest;
use App\Models\Amendment;
use App\Models\Assembly;
use App\Models\Document;
use App\Models\Support;

class AmendmentController extends Controller
{
    /**
     * List all amendments in a document
     */
    public function amendments(Assembly $assembly, Document $document) {
        $amendments = $document->amendments;
        $amendments->load(['supports' => function ($query) {
            $query->where('user_id', auth()->user()->id);
        }]);

        // Group by article
        $articles = [];
        foreach ($amendments as $amendment) {
            $articles[$amendment->article][] = $amendment;
        }

        return $articles;
    }

    /**
     * Show amendment info with supports
     */
    public function amendment(Assembly $assembly, Amendment $amendment) {
        $amendment = $amendment->load([
            'supports' => function ($query) {
                $query->with(['user' => function ($userQuery) {
                    $userQuery->select('id', 'name', 'last_name');
                }]);
            },
            'document',
            'document.assembly'
        ]);

        return $amendment;
    }

    /**
     * Submit an amendment
     */
    public function new(Assembly $assembly, Document $document, StoreAmendmentRequest $request)
    {
        $lastAmendment = Amendment::where('document_id', $document->id)->orderBy('num', 'desc')->first();
        $nextNum = ($lastAmendment) ? $lastAmendment->num + 1 : 1;

        $amendment = new Amendment;
        $amendment->document_id = $document->id;
        $amendment->user_id = $request->user()->id;
        $amendment->num = $nextNum;
        $amendment->article = $request->input('article');
        $amendment->title = $request->input('title');
        $amendment->type = $request->input('type');
        $amendment->original = $request->input('original');
        $amendment->amended = $request->input('text');
        $amendment->justification = $request->input('justification');
        $amendment->registered_by_assembly = ($request->input('registered_by_assembly')) ? $request->user()->data('comarca') : null;
        $amendment->save();

        return $amendment;
    }

    /**
     * Support an amendment
     */
    public function support(Assembly $assembly, Amendment $amendment, SupportAmendmentRequest $request)
    {
        $support = new Support;
        $support->amendment_id = $amendment->id;
        $support->user_id = $request->user()->id;
        $support->save();

        return $support;
    }
}
