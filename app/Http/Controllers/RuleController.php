<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RuleController extends Controller
{

  public function __construct()
  {
    $this->middleware("auth")->except('terms_of_service');
  }

  public function privacy()
  {
    return view('app.privacy');
  }

  public function terms()
  {
    return view('app.terms');
  }

  public function terms_of_service()
  {
    return view('app.terms_of_service');
  }

  public function cookies()
  {
    return view('app.cookies');
  }

  public function about()
  {
    return view('app.about');
  }

  public function help()
  {
    return view('app.help');
  }

  public function addQuestion(Request $request)
  {
    $data=$request->validate([
      'question'=>'required'
    ]);
    $question=new \App\Question;
    $question->question=$data['question'];
    $question->save();

    $request->session()->flash('status', 'Question is saved');

    return back();
  }
}
