<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class QuizController extends Controller
{
  public function index()
  {
    if (Gate::denies('test-permissions')) {
      dd('não autorizado');
    }
    dd('autorizado');
  }
}
