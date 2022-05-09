<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    private $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function all()
    {
        return response()->json($this->note->allList());
    }

    public function get($id)
    {
        return response()->json($this->note->findOrFail($id));
    }

    public function createOrUpdate($id = 0, Request $req)
    {
        $req->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $user = Auth::user();

        if (!isset($req->user_id) || $user->id != $req->user_id) {
            return response()->json(false);
        }

        $id = isset($req->id) ? $req->id : 0;

        if ($id == 0) {
            $note = $this->note->create($req->all());
        }
        else {
            $note = $this->note->findOrFail($id);
            $note->title = $req->title;
            $note->content = $req->content;
            $note->status = $req->status;
            $note->deleted = $req->deleted;
            $note->save();
        }

        return response()->json($note);
    }

    public function delete($id)
    {
        $note = $this->note->findOrFail($id);
        $note->deleted = true;
        return response()->json($note->save());
    }
}
