<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutators\MutatorRequest;
use App\Http\Resources\MutatorResource;
use App\Models\Mutator;
use App\Models\MutatorOption;

class MutatorController extends Controller
{
    public function index()
    {
        return inertia()->render('User/Mutators/Index', [
            'mutator_options' => MutatorOption::all(),
            'mutators' => MutatorResource::collection(auth()->user()
                ->mutators()
                ->with('mutatorOption')
                ->paginate(4))
        ]);
    }

    public function store(MutatorRequest $request)
    {
        $mutatorArray = $request->validated();
        $comparableArray = [$mutatorArray, MutatorRequest::getStaticFields()];
        $mutatorToCreate = array_intersect_key(...$comparableArray) + ['arguments' => array_diff_key(...$comparableArray)];
        auth()->user()->mutators()->create($mutatorToCreate);
        return redirect()->back();
    }

    public function destroy(Mutator $mutator)
    {
        abort_if(auth()->id() !== $mutator->user_id, 403);
        $mutator->delete();
        return redirect()->back();
    }
}
