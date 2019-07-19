<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Book;
class BookPartee extends Model
{
  static $status = array(
    "VACIO",// indicando que ningún huésped ha cubierto el formulario de check-in online todavía; 
    "HUESPEDES", // indicando que al menos un huésped ha cubierto el enlace de check-in online;
    "FINALIZADO" // indicando que el parte de viajeros ha sido finalizado, es decir, se han creado los partes de viajeros y se ha realizado el envío al cuerpo policial correspondiente
  );
  
  
  public function book()
  {
          return $this->belongsTo(Book::class)->first();
  }
}
