<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;

class VoteQuestionController extends Controller
{
    public function __construct()
    {
        // user must be signed in
        $this->middleware('auth');


    }
    public function index(Question $question)
    {
        $vote = (int) request()->vote;
        auth()->user()->voteQuestion($question, $vote);

        return back();
    }
}
