<?php

namespace App\Http\Controllers;

use App\Event;
use App\Room;
use DateTime;

use Illuminate\Http\Request;

class EventController extends Controller
{

	/**
     * Receive a date string and validate it.
     *
     * @param  $date
     * @return boolean
     */
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

    /**
     * Receive a json containing event datails and create a new instance
     * of Event.
     *
     * @param  Request $request
     * @return status
     */
    public function store(Request $request){

    	$request->user()->authorizeRoles(['admin', 'superadmin']);
    	
    	$room = $request->input("room");
    	$day = $request->input("day");
    	$hour = $request->input("hour");
    	$price = $request->input("price");
    	$duration = $request->input("duration");
    	$name = $request->input("name");
    	$description = $request->input("description");

    	if($room and $day and $hour and $price and $duration
    		and $name and $description){
    		
    		

    		if(!Room::where('id', $room)->first()){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Inexistent room!'], 400);
    		}
    		
    		if (! $this->validateDate($day, 'd/m/Y')){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid date!'], 400);
    		}

    		if (! $this->validateDate($hour, 'H:i')){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid hour!'], 400);
    		}

    		if (! $this->validateDate($duration, 'H:i')){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid duration!'], 400);
    		}


    		if(!(gettype($price) == "integer" or gettype($price) == "double")){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid price!'], 400);
    		}

    		$initialTimestamp = strtotime($day.' '.$hour);
    		$durationEploded = explode(":", $duration);
    		$finalTimestamp = $initialTimestamp + (intval($durationEploded[0])*60*60) + (intval($durationEploded[1])*60);

    		
    		$event = new Event();
	        $event->name = $name;
	        $event->description = $description;
	        $event->price = $price;
	        $event->time = date("d-m-Y H:i:s",$initialTimestamp);
	        $event->finalTime = date("d-m-Y H:i:s",$finalTimestamp);
	        $event->room = $room;
	        $event->save();

	        return response()->json([
	        	'status' => 'ok'], 200);

    		
    	}

    	

    	return response()->json([
	        	'status' => 'error',
		  		'message' => 'Verify the data and try again'], 400);
    
    }



    /**
     * Receive a json containing event datails and the id of an existent event.
     * Update the selected event.
     *
     * @param  Request $request
     * @return status
     */
    public function update(Request $request){

    	$request->user()->authorizeRoles(['admin', 'superadmin']);

    	$id = $request->input("id");
    	$room = $request->input("room");
    	$day = $request->input("day");
    	$hour = $request->input("hour");
    	$price = $request->input("price");
    	$duration = $request->input("duration");
    	$name = $request->input("name");
    	$description = $request->input("description");

    	if($room and $day and $hour and $price and $duration
    		and $name and $description and $id){
    		
    		

    		if(!Room::where('id', $room)->first()){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Inexistent room!'], 400);
    		}

    		if(!Event::where('id', $id)->first()){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Inexistent event!'], 400);
    		}
    		
    		if (! $this->validateDate($day, 'd/m/Y')){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid date!'], 400);
    		}

    		if (! $this->validateDate($hour, 'H:i')){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid hour!'], 400);
    		}

    		if (! $this->validateDate($duration, 'H:i')){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid duration!'], 400);
    		}


    		if(!(gettype($price) == "integer" or gettype($price) == "double")){
    			return response()->json([
		        	'status' => 'error',
			  		'message' => 'Invalid price!'], 400);
    		}

    		$initialTimestamp = strtotime($day.' '.$hour);
    		$durationEploded = explode(":", $duration);
    		$finalTimestamp = $initialTimestamp + (intval($durationEploded[0])*60*60) + (intval($durationEploded[1])*60);


	        $event = Event::where('id', $id)->update([
					'name' => $name,
					'description' => $description,
					'price' => $price,
					'time' => date("d-m-Y H:i:s",$initialTimestamp),
					'finalTime' => date("d-m-Y H:i:s",$finalTimestamp),
					'room' => $room

				]);

	        return response()->json([
	        	'status' => 'ok'], 200);

    		
    	}

    	return response()->json([
	        	'status' => 'error',
		  		'message' => 'Verify the data and try again'], 400);

    }


    /**
     * Return all events in the database.
     *
     * @return Event
     */
    public function show(){
    	return Event::all();
    }


    /**
     * Receive a id and return the respective Event, if exists.
     *
     * @param  $id
     * @return Event
     */
    public function showSelected($id)
    {
        return Event::find($id);
    }


    /**
     * Receive a id and delete the respective Event, if exists.
     *
     * @param  $id
     * @return status
     */
    public function delete($id)
    {
    	$request->user()->authorizeRoles(['admin', 'superadmin']);
    	
    	$event = Event::find($id);

    	if(!$event){
			return response()->json([
	        	'status' => 'error',
		  		'message' => 'Inexistent event!'], 400);
		}

		$event->delete();

        return response()->json([
		        	'status' => 'ok'], 204);


    }
}
