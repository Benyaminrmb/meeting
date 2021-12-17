<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    //

    public function index(Request $request)
    {
        $meeting=Meeting::all();
        return $this->successResponse(MeetingResource::collection($meeting));
    }


    public function store(Request $request)
    {
        $user=Auth::user();

        $meeting=$user->meetings()->create([
            'name' => $request->get('name'),
            'body' => $request->get('data'),
            'shamsi_created_at' => $request->get('date'),
        ]);



        return $this->successResponse(MeetingResource::make($meeting));
    }
}
