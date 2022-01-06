<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Member;
use App\Models\Message;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $v = array('mun','mci','che');
        $vs = array(['top_club_england'=>$v]);

        $Com = Message::where('id',4)->with(['from:id,username', 'to:id,username'])->get();

        return response()->json($vs);

    }
}
