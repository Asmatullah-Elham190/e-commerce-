<?php

namespace App\Http\Controllers;

use App\Enums\NoteType as EnumsNoteType;
use App\Models\Note;
use App\Models\NoteTranslation;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class NoteController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:view_notes'])->only('index');
        $this->middleware(['permission:add_note'])->only('create');
        $this->middleware(['permission:edit_note'])->only('edit');
        $this->middleware(['permission:delete_note'])->only('destroy');

        $this->note_rules = [
            'description' => ['required','max:900'],
        ];

        $this->note_messages = [
            'description.required' => translate('Note description is required'),
            'description.max'  => translate('Max 900 character'),
        ];
    }

    public function index(Request $request)
    {
        $sort_search =null;
        $note_tabs = ['All Notes', 'Admin Notes', 'Seller Notes'];
        $notes  = Note::whereHas('user')->orderBy('created_at', 'desc');

        if ($request->has('search')){
            $sort_search = $request->search;
            $notes = $notes->where('description', 'like', '%'.$sort_search.'%');
        }

        $notes = $notes->paginate(15);
        return view('backend.note.index', compact('notes', 'sort_search', 'note_tabs'));
    }

    public function filter(Request $request)
    {
        $notes  = Note::whereHas('user')->orderBy('created_at', 'desc');
        $sort_search = null;

        
        $adminId = get_admin()->id;

        if ($request->note_status == "admin_notes") {
            $notes = $notes->where('user_id', $adminId);
        } else if ($request->note_status == 'seller_notes') {
            $notes = $notes->where('user_id', '!=', $adminId);
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $notes = $notes->where(function ($query) use ($sort_search) {
                $query->where('description', 'like', '%' . $sort_search . '%');
            });
        }

        $notes = $notes->paginate(15);
        $view = view(
            'backend.note.table',
            compact('notes', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function create(Request $request)
    {
        $types = EnumsNoteType::cases();
        return view('backend.note.create', compact('types'));
    }

    public function store(Request $request)
    {
        $rules      = $this->note_rules;
        $messages   = $this->note_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }
        
        $note = new Note();
        $note->user_id = get_admin()->id;
        $note->note_type = $request->note_type;
        $note->description = $request->description;
        $note->save();

        $note_translation = NoteTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'note_id' => $note->id]);
        $note_translation->description = $request->description;
        $note_translation->save();

        return 1;
    }

    public function edit(Request $request)
    {
        $lang   = $request->lang ?? env("DEFAULT_LANGUAGE");
        $types = EnumsNoteType::cases();
        $note  = Note::findOrFail($request->id);
        return view('backend.note.edit', compact('note', 'types', 'lang'));
    }

    public function update(Request $request)
    {
        $rules      = $this->note_rules;
        $messages   = $this->note_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $note = Note::findOrFail($request->id);
        $note->note_type = $request->note_type;
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $note->description = $request->description;
        }
        $note->save();

        $note_translation = NoteTranslation::firstOrNew(['lang' => $request->lang, 'note_id' => $note->id]);
        $note_translation->description = $request->description;
        $note_translation->save();

        return 1;
    }

    public function destroy($id)
    {   
        $note = Note::findOrFail($id);
        $note->note_translations()->delete();
        $note->delete();
        return 1;
    }

    public function bulk_note_delete(Request $request)
    {
        if ($request->id) {

            foreach ($request->id as $note_id) {

                $note = Note::find($note_id);

                if (!$note) {
                    continue;
                }

                foreach ($note->note_translations as $note_translation) {
                    $note_translation->delete();
                }

                $note->delete();
            }

            return 1;
        }

        return 0;
    }

    public function getNotes(Request $request)
    {
        $user = auth()->user();
        $noteType = $request->note_type;
        
        $notes = Note::where('note_type', $noteType);
        if($user->user_type == 'seller'){
            $notes->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhere(function($query) {
                        $query->where('user_id', get_admin()->id)
                                ->where('seller_access', 1);
                    });
            });
        }
        else{
            $notes->where('user_id', get_admin()->id);
        }

        $notes = $notes->get();
        return view('backend.note.get_notes', compact('notes', 'noteType'));
    }

    public function getSingleNote($id){
        $note = Note::findOrFail($id);
        $note = $note != null ? $note->getTranslation('description') : translate('Note not found');
        return $note;
    }


    public function updateSellerAccess(Request $request) {
        $note = Note::findOrFail($request->id);
        $note->seller_access = $request->status;
        $note->save();
        return 1;
    }

    public function admin_ajax_add_note_modal(Request $request)
    {
        $note_type=$request->type;
        $types = EnumsNoteType::cases();
        return view('backend.note.ajax_add_note_modal',
        [
            'note_type' => $note_type,
            'types' => $types
        ]);
    }

    public function admin_ajax_add_note_store(Request $request)
    {
        $rules      = $this->note_rules;
        $messages   = $this->note_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
        
        $note = new Note();
        $note->user_id = get_admin()->id;
        $note->note_type = $request->note_type;
        $note->description = $request->description;
        $note->save();

        $note_translation = NoteTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'note_id' => $note->id]);
        $note_translation->description = $request->description;
        $note_translation->save();

        return response()->json([
            'success' => true,
            'message' => translate('Note has been inserted successfully'),
            'note_id' => $note->id,
            'html' => view('backend.note.partials.carousel', [
                'notes' => Note::where('note_type', $request->note_type)->orderBy('created_at', 'desc')->get(),
                'class' => $this->getClass($request->note_type),
                'hiddenInput' => $this->getInputId($request->note_type),
            ])->render()
        ]);
    }

    private function getClass(string $noteType): string
    {
        return match($noteType) {
            'refund'   => 'refund-notes',
            'warranty' => 'single-warranty-notes',
            'shipping' => 'shp-notes',
            'delivery' => 'delivery-notes',
            default    => 'refund-notes',
        };
    }

    private function getInputId(string $noteType): string
    {
        return match($noteType) {
            'refund'   => 'refund_note_id',
            'warranty' => 'warranty_note_id',
            'shipping' => 'shipping_note_id',
            'delivery' => 'delivery_note_id',
            default    => 'refund_note_id',
        };
    }
}
