<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function getUrlAttribute() {
        //return route('question.show', $this->id);
        return '#';
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getAvatarAttribute()
    {
        $email = $this->email;
        $size = 32;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;

    }

    public function favourites()
    {
        return $this->belongsToMany(Question::class, 'favourites')->withTimestamps();
    }

    // relationship method
    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'voteable');
    }
    public function
    voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'voteable');
    }

    // our custom methods
    public function voteQuestion(Question $question, $vote)
    {
        $voteQuestions = $this->voteQuestions();
        $this->_vote($voteQuestions, $question, $vote);
    }
    public function voteAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->voteAnswers();
        $this->_vote($voteAnswers, $answer, $vote);

    }
    private function _vote($relationship, $model, $vote)
    {
        if ($relationship->where('voteable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }
        else {
            $relationship->attach($model, ['vote' =>$vote]);
        }
        $model->load('votes'); // refresh votes relationship
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');

        $model->votes_count = $upVotes + $downVotes;
        $model->save();
    }
}
