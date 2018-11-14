<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Database\EloquentModelNotFoundException;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\v1\FlightService;

class FlightController extends Controller
{
    protected $flights;
    
    protected $rules = [
        'flight_number'       => 'required',
        'status'              => 'required|flightstatus',
        'arrival.datetime'    => 'required|date',
        'arrival.iata_code'   => 'required',
        'departure.datetime'  => 'required|date',
        'departure.iata_code' => 'required'        
    ];
    
    public function __construct(FlightService $service) {
        $this->flights = $service;
        
        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = request()->input();
        
        $data = $this->flights->getFlights($parameters);    
        
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        
        try {
            $flight = $this->flights->createFlight($request);
            return response()->json($flight, 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parameters = request()->input();
        $parameters['flight_number'] = $id;
        
        $data = $this->flights->getFlights($parameters);
        
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $flight = $this->flights->updateFlight($request, $id);
            return response()->json($flight, 200);
        } 
        catch (ModelNotFoundException $exception) {
            throw $exception;
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $flight = $this->flights->deleteFlight($id);
            return response()->make('', 204);
        } 
        catch (ModelNotFoundException $exception) {
            throw $expection;
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
