<?php

namespace App\Services\v1;

use App\Flight;
use App\Airport;

class FlightService {
    protected $supported_includes = [
        'arrivalAirport' => 'arrival',
        'departureAirport' => 'departure'
    ];
    
    protected $clause_properties = [
        'status',
        'flight_number'
    ];
    
    public function getFlights($parameters) {
        if (empty($parameters)) {
            return $this->filterFlights(Flight::all());
        }
        
        $with_keys = $this->getWithKeys($parameters);
        $where_clause = $this->getWhereClause($parameters);
        
        $flights = Flight::with($with_keys)->where($where_clause)->get();

        // return dd(Flight::with($with_keys)->get());
        
        return $this->filterFlights($flights, $with_keys);
    }
    
    public function createFlight($req) {
        // return dd($req->input());
        
        $arrival_airport = $req->input('arrival.iata_code');
        $departure_airport = $req->input('departure.iata_code');
        
        $airports = Airport::whereIn('iata_code', [$arrival_airport, $departure_airport])->get();
        
        $codes = [];
        
        foreach ($airports as $airport) {
            $codes[$airport->iata_code] = $airport->id;
        }
        
        // return dd($codes[$arrival_airport]);
        
        $flight = new Flight();
        $flight->flight_number =$req->input('flight_number');
        $flight->status = $req->input('status');
        $flight->arrival_airport_id = $codes[$arrival_airport];
        $flight->arrival_date_time = $req->input('arrival.datetime');
        $flight->departure_airport_id = $codes[$departure_airport];
        $flight->departure_date_time = $req->input('departure.datetime');
        
        $flight->save();
        
        // return dd($flight);
        
        return $this->filterFlights([$flight]);
    }
    
    public function updateFlight($req, $flight_number) {
        $flight = Flight::where('flight_number', $flight_number)->firstOrFail();
        
        $arrival_airport = $req->input('arrival.iata_code');
        $departure_airport = $req->input('departure.iata_code');
        
        $airports = Airport::whereIn('iata_code', [$arrival_airport, $departure_airport])->get();
        $codes = [];
        
        foreach ($airports as $airport) {
            $codes[$airport->iata_code] = $airport->id;
        }
        
        $flight->flight_number = $req->input('flight_number');
        $flight->status = $req->input('status');
        $flight->arrival_airport_id = $codes[$arrival_airport];
        $flight->arrival_date_time = $req->input('arrival.datetime');
        $flight->departure_airport_id = $codes[$departure_airport];
        $flight->departure_date_time = $req->input('departure.datetime');
        
        $flight->save();
        
        return $this->filterFlights([$flight]);
    }
    
    public function deleteFlight($flight_number) {
        $flight = Flight::where('flight_number', $flight_number)->firstOrFail();
        
        $flight->delete();
    }

    protected function filterFlights($flights, $keys = []) {
        $data = [];
        
        // return dd($keys);
        
        foreach ($flights as $flight) {
            $entry = [
                'flight_number' => $flight->flight_number,
                'status' => $flight->status,
                'href' => route('api.v1.flights.show', ['id' => $flight->flight_number])
            ];
            
            if (in_array('arrivalAirport', $keys)) {
                $entry['arrival'] = [
                    'datetime'  => $flight->arrival_date_time,
                    'iata_code' => $flight->arrivalAirport->iata_code,
                    'city'      => $flight->arrivalAirport->city,
                    'state'     => $flight->arrivalAirport->state,
                ];
            }
            
            if (in_array('departureAirport', $keys)) {
                $entry['departure'] = [
                    'datetime'  => $flight->departure_date_time,
                    'iata_code' => $flight->departureAirport->iata_code,
                    'city'      => $flight->departureAirport->city,
                    'state'     => $flight->departureAirport->state,
                ];
            }
            
            $data[] = $entry;
        }
        
        return $data;
    }
    
    protected function getWithKeys($parameters) {
        
        $with_keys = [];
        
        if (isset($parameters['include'])) {
            $include_parms = explode(',', $parameters['include']);
            $includes = array_intersect($this->supported_includes, $include_parms);
            $with_keys = array_keys($includes);
        }
        
        return $with_keys;
    }
    
    protected function getWhereClause($parameters) {
        $clause = [];
        
        foreach ($this->clause_properties as $prop) {
            if (in_array($prop, array_keys($parameters))) {
                $clause[$prop] = $parameters[$prop];
            }
        }
        
        return $clause;
    }
}