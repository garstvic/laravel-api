<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    public function arrivalAriport() {
        return $this->belongsTo('App\Airport', 'arrival_airport_id');   
    }
    
    public function departureAirport() {
        return $this->belongsTo('App\Airport', 'departure_airport_id');
    }
    
    // ????
    public function passengers() {
        return $this->belongsToMany('App\Customer', 'flight_customer');
    }
}
