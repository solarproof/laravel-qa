<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
class FavouritesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function store(Question $question)
    {
        $question->favourites()->attach(auth()->id());
        return back()->with('success', "Question favourited");
    }
    public function destroy(Question $question)
    {
        $question->favourites()->detach(auth()->id());
        return back()->with('success', "Question unfavourited");
    }
}
