<?php

namespace App\Http\Controllers;

use App\Event;
use App\Room;
use App\Sold;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Receive a json containing event datails and create a new instance
     * of Sold.
     *
     * @param  Request $request
     * @return status
     */
    public function buy(Request $request){
    	$id = \Auth::user()->id;

    	$eventId = $request->input("event");
    	$chairs = $request->input("chairs");
    	$quantity = $request->input("quantity");

    	$event = Event::where('id', $eventId)->first();

    	if(!$event or $event->room == NULL){
			return response()->json([
	        	'status' => 'error',
		  		'message' => 'Inexistent event!'], 400);
		}


		$room = Room::where('id', $event->room)->first();

		if($room->type == 1){
			$count = Sold::where('event', $eventId)->count();

			if($count <= ($count + $quantity)){

				//payment routine here

				$count = 0;	
				while ($count < $quantity) {
					$sold = new Sold();
			        $sold->event = $eventId;
			        $sold->user = $id;
			        $sold->save();

			        $count++;
				}

				return response()->json([
	        		'status' => 'ok'], 200);
				
			}
			else{
				return response()->json([
		        	'status' => 'error',
			  		'message' => 'Quantity of tickets exceeding the avaliable!'], 400);
			}
		}
		else if($room->type == 2){

		}

		return $room->chairs;

    }
}
