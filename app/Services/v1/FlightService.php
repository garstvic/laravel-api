<?php

namespace App\Services\v1;

use App\Flight;

class FlightService {
    public function getFlights() {
        return $this->filterFlights(Flight::all());
    }
    
    public function getFlight($flight_number) {
        return $this->filterFlights(Flight::where('flight_number', $flight_number)->get());   
    }
    
    protected function filterFlights($flights) {
        $data = [];
        
        foreach ($flights as $flight) {
            $entry = [
                'flight_number' => $flight->flight_number,
                'status' => $flight->status,
                'href' => route('api.v1.flights.show', ['id' => $flight->flight_number])
            ];
            
            $data[] = $entry;
        }
        
        return $data;
    }
}