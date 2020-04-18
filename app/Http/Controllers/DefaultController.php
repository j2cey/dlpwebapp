<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function defaultrequest($reqtype, $phonenum) {
      //dd($reqtype, $phonenum);

      $request_parsed = -1;
      $request_msg = "";

      if ($reqtype == '1') {
        $request_parsed = 1;
        $request_msg = "Deplacement alimentaire";
      } elseif($reqtype == '2') {
        $request_parsed = 2;
        $request_msg = "Deplacement de sante";
      } elseif($reqtype == '3') {
        $request_parsed = 3;
        $request_msg = "Deplacement d urence";
      } elseif($reqtype == '4') {
        $request_parsed = 4;
        $request_msg = "Consultation";
      } else {
        $request_msg = "Bad Request!";
      }

      return response()->json([
            'message' => $request_msg
        ]);
    }
}
