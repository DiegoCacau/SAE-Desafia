<?php

//TODO
//validate the json data from chairs

namespace App\Http\Controllers;

use App\Room;
use Validator;

use Illuminate\Http\Request;

class RoomController extends Controller
{

	/**
     * Receive a json containing the room's chairs and create a new instance
     * of Room.
     *
     * @param  Request $request
     * @return status
     */
    public function store(Request $request){

    	$request->user()->authorizeRoles(['admin', 'superadmin']);


    	if ($request->input("chairs") and $request->input("type")){
		    $type = $request->input("type");
		    $chair = $request->input("chairs");

		    if(($type == 1) and (gettype($chair) == "integer")){

		        $room = new Room();
		        $room->chairs = $chair;
		        $room->type = $type;
		        $room->save();

		        return response()->json([
		        	'status' => 'ok'], 200);

		    }
		    else if(($type == 2) and (gettype($chair) == "array")){
		    	
		    	$room = new Room();
		        $room->chairs = json_encode($chair);
		        $room->type = $type;
		        $room->save();

		        return response()->json([
		        	'status' => 'ok'], 200);
		    }

		    
		}

    	return response()->json([
	        	'status' => 'error',
		  		'message' => 'Verify the data and try again'], 400);
    }
}
