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

    /**
     * Receive a id and return the respective Sale, if exists.
     *
     * @param  $id
     * @return Event
     */
    public function showSelected($id)
    {
    	$request->user()->authorizeRoles(['admin', 'superadmin']);

        return Sold::find($id);
    }

    /**
     * Receive a json containing event datails and the id of an existent sale.
     * Update the selected sale.
     *
     * @param  Request $request
     * @return status
     */
    public function update(Request $request){

    	$request->user()->authorizeRoles(['admin', 'superadmin']);

    	$id = $request->input("id");
    	$chair = $request->input("chair");
    	$user = $request->input("user");
    	$eventId = $request->input("event");


    	if($id){
    		$sale = Sold::where('id', $id)->first();

    		$event = Event::where('id', $sale->event)->first();

    		$room = Room::where('id', $event->room)->first();

    		if($room->type == 1){
    			if($user){
    				$sold = Sold::where('id', $id)->update([
						'user' => $user
					]);
    			}
    			else{
    				return response()->json([
			        	'status' => 'error',
				  		'message' => 'Verify the data and try again'], 400);
    			}

    			return response()->json([
	        		'status' => 'ok'], 200);
    		}
    		else if($room->type == 2){

    		}

    		

    	}
    	

    	return response()->json([
	        	'status' => 'error',
		  		'message' => 'Verify the data and try again'], 400);

    }

    /**
     * Receive a id and delete the respective Sold, if exists.
     *
     * @param  $id
     * @return status
     */
    public function delete($id)
    {
    	$request->user()->authorizeRoles(['admin', 'superadmin']);

    	$sold = Sold::find($id);

    	if(!$sold){
			return response()->json([
	        	'status' => 'error',
		  		'message' => 'Inexistent sold!'], 400);
		}

		$sold->delete();

        return response()->json([
		        	'status' => 'ok'], 204);


    }
}
