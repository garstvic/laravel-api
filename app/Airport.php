<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    public function arrivalFlights() {
        return $this->hasMany('App\Flight', 'arrival_airport_id');
    }
    
    public function departingFlights() {
        return $this->hasMany('App\Flight', 'departure_airport_id');
    }
}
