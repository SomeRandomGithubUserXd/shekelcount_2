<?php

namespace App\Http\Controllers\User\Entry;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Entry\DeleteManyEntriesRequest;
use App\Http\Requests\User\Entry\EntryRequest;
use App\Http\Requests\User\Entry\ImportRequest;
use App\Http\Requests\User\Entry\TransferEntriesRequest;
use App\Models\Entry;
use App\Models\Service\EntryImportBank;
use App\Services\ImportEntries\AbstractEntryParser;
use App\Traits\CRUDsEntries;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    use CRUDsEntries;

    public function index()
    {
        $user = auth()->user();
        $categories = $user
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
            'icons' => config('icons.data'),
            'import' => AbstractEntryParser::getImportMeta(),
            'mutators' => $user->mutators
        ]);
    }

    public function import(ImportRequest $request)
    {
        $parserModel = EntryImportBank::find($request->get('schema_id'));
        /** @var AbstractEntryParser $parser */
        $parser = new $parserModel->parser(
            $request->file('file'),
            auth()->id(),
            $parserModel->id,
            $request->mutator_ids
        );
        $parser->save();
        return redirect()->route('entries.index');
    }

    public function store(EntryRequest $request)
    {
        Entry::create($request->validated());
        return redirect()->back();
    }

    public function update(EntryRequest $request, Entry $entry)
    {
        abort_unless($this->entryBelongsToUser(auth()->id(), $entry->id), 403);
        $entry->update($request->validated());
        return redirect()->back();
    }

    public function destroy(Entry $entry)
    {
        abort_unless($this->entryBelongsToUser(auth()->id(), $entry->id), 403);
        $entry->delete();
        return redirect()->back();
    }

    public function deleteMany(DeleteManyEntriesRequest $request)
    {
        Entry::query()
            ->whereIn('id', $request->get('entry_ids'))
            ->delete();
        return redirect()->back();
    }

    public function transfer(TransferEntriesRequest $request)
    {
        Entry::query()
            ->whereIn('id', $request->get('entry_ids'))
            ->update(['category_id' => $request->get('category_id')]);
        return redirect()->back();
    }

    public function unmarkNew(Entry $entry)
    {
        $entry->update(['was_recently_imported' => 0]);
        return redirect()->back();
    }
}
