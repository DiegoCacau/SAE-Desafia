<?php

namespace App\Http\Controllers;

use App\Room;

use Illuminate\Http\Request;

class RoomController extends Controller
{

	
	/**
     * Receive a json containing the room's chairs verify if contains only valid
     * elements
     *
     * @param  json $chairs
     * @return boolean
     */
	private function validateChairs($chairs){
    	
    	$range = range(0, 2);

    	foreach ($chairs as $array) {
    		
	    	$isInRange  = (min($array)>=min($range) and max($array)<=max($range)) ? 1 : 0;

	    	if($isInRange == 0) return 0;

	    }	

	    return 1;

    }



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
		    $status = 0;

		    if($request->input("status")){
		    	$status = $request->input("status");
		    }

		    if(($type == 1) and (gettype($chair) == "integer")){

		        $room = new Room();
		        $room->chairs = $chair;
		        $room->type = $type;
		        $room->status = $status;
		        $room->save();

		        return response()->json([
		        	'status' => 'ok'], 200);

		    }
		    else if(($type == 2) and (gettype($chair) == "array")){

		    	if($this->validateChairs($chair) == 0){
		    		return response()->json([
		        	'status' => 'error',
			  		'message' => 'Chairs are not in a valid format.'], 400);
		    	}
		    	
		    	$room = new Room();
		        $room->chairs = json_encode($chair);
		        $room->type = $type;
		        $room->status = $status;
		        $room->save();

		        return response()->json([
		        	'status' => 'ok'], 200);
		    }

		    
		}

    	return response()->json([
	        	'status' => 'error',
		  		'message' => 'Verify the data and try again'], 400);
    
    }


    /**
     * Receive a json containing the room's chairs and the id of an existent room.
     * Update the selected room.
     *
     * @param  Request $request
     * @return status
     */
    public function update(Request $request){

    	$request->user()->authorizeRoles(['admin', 'superadmin']);

    	if ($request->input("chairs") and $request->input("type")){
		    $type = $request->input("type");
		    $chair = $request->input("chairs");
		    $id = $request->input("id");
		    $status = 0;

		    $room = Room::where('id', $id)->first();
		    if(!$room){
	        	return response()->json([
		        	'status' => 'error',
			  		'message' => 'Inexistent room!'], 400);
	        }



		    if($request->input("status")){
		    	$status = $request->input("status");
		    }

		    if(($type == 1) and (gettype($chair) == "integer")){

		        $room = Room::find($id)->update([
					'chairs' => $chair,
					'type' => $type,
					'status' => $status
				]);

		        return response()->json([
		        	'status' => 'ok'], 200);

		    }
		    else if(($type == 2) and (gettype($chair) == "array")){

		    	if($this->validateChairs($chair) == 0){
		    		return response()->json([
		        	'status' => 'error',
			  		'message' => 'Chairs are not in a valid format.'], 400);
		    	}
		    	
		    	$room = Room::find($id)->update([
					'chairs' => json_encode($chair),
					'type' => $type,
					'status' => $status
				]);

		        return response()->json([
		        	'status' => 'ok'], 200);
		    }

		    
		}

    	return response()->json([
	        	'status' => 'error',
		  		'message' => 'Verify the data and try again'], 400);

    }


    public function show(Request $request){
    	return Room::all();
    }

    public function showSelected($id)
    {
        return Room::find($id);
    }

    
}
