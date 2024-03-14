<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index() {
        $authors = Author::all();

        return $this->validResponse($authors);
    }

    public function read($id) {
        $author = Author::findOrFail($id);

        return $this->validResponse($author);
    }

    public function create(Request $request) {
        $rules = [
            'name' => 'required|max:60|unique:authors',
            'nationality' => 'max:30',
            'created_by' => 'required'
        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $author = Author::create($data);

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'required|max:60|unique:authors,name,' . $id,
            'nationality' => 'max:30',
            'updated_by' => 'required'
        ];
        $this->validate($request, $rules);

        $data = $request->all();

        $author = Author::find($id);

        $author->fill($data);
        $author->save();
        return $this->successResponse($author, Response::HTTP_OK);
    }

    public function patch($id, Request $request) {
        $rules = [
            'name' => 'max:60|unique:authors,name,' . $id,
            'nationality' => 'max:30'
        ];
        $this->validate($request, $rules);

        $author = Author::findOrFail($id);

        $data = $request->all();
        $author->fill($data);
        $author->save();
        return $this->successResponse($author, Response::HTTP_OK);
    }

    public function delete($id) {
        $author = Author::findOrFail($id);
        $author->delete();
        return $this->successResponse($author, Response::HTTP_OK);
    }

}
