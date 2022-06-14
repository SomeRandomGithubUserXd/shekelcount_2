<?php

namespace App\Http\Controllers\User\Entry;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Entry\DeleteManyEntriesRequest;
use App\Http\Requests\User\Entry\EntryRequest;
use App\Http\Requests\User\Entry\ImportRequest;
use App\Http\Requests\User\Entry\TransferEntriesRequest;
use App\Http\Requests\User\Entry\UpdateEntryRequest;
use App\Models\Category;
use App\Models\Entry;
use App\Models\Service\EntryImportBank;
use App\Rules\EntryBelongsToUserRule;
use App\Services\ImportEntries\AbstractEntryParser;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function index(Request $request)
    {
        $categories = auth()->user()
            ->categories()
            ->addSelect([
                'entries_sum' => Entry::query()
                    ->whereColumn('category_id', 'categories.id')
                    ->selectRaw('sum(sum / 100) as entries_sum')
            ])
            ->orderBy('entries_sum', 'DESC')
            ->paginate(5);
        return inertia('User/Entries/Index', [
            'categories' => $categories,
            'icons' => config('icons.v5'),
            'import' => AbstractEntryParser::getImportMeta()
        ]);
    }

    public function import(ImportRequest $request)
    {
        $parserModel = EntryImportBank::find($request->get('schema_id'));
        /** @var AbstractEntryParser $parser */
        $parser = new $parserModel->parser($request->file('file'), auth()->id(), $parserModel->id);
        $parser->save();
        return redirect()->route('entries.index');
    }

    public function store(EntryRequest $request)
    {
        Entry::create($request->validated());
        return redirect()->back();
    }

    public function update(UpdateEntryRequest $request)
    {
        $entry = Entry::find($request->get('entry_id'));
        $entry->update($request->validated());
        return redirect()->back();
    }

    public function delete(Entry $entry)
    {
        $rule = new EntryBelongsToUserRule(auth()->id());
        $passes = $rule->passes('entry_id', $entry->id);
        abort_unless($passes, 403);
        $entry->delete();
        return redirect()->back();
    }

    public function deleteMany(DeleteManyEntriesRequest $request)
    {
        foreach ($request->get('entry_ids') as $entryId) {
            Entry::find($entryId)->delete();
        }
        return redirect()->back();
    }

    public function transfer(TransferEntriesRequest $request)
    {
        foreach ($request->get('entry_ids') as $entryId) {
            Entry::find($entryId)
                ->update(['category_id' => $request->get('category_id')]);
        }
        return redirect()->back();
    }

    public function unmarkNew(Entry $entry)
    {
        $entry->update(['was_recently_imported' => 0]);
        return redirect()->back();
    }
}
