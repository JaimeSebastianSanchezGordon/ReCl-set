<?php

use App\Models\Garment;

/*
|--------------------------------------------------------------------------
| Garment Configuration
|--------------------------------------------------------------------------
|
| Las listas canónicas viven en el modelo Garment como constantes.
| Este archivo de configuración las re-exporta para conveniencia,
| permitiendo acceso vía config('garments.categories'), etc.
|
*/

return [
    'categories' => Garment::CATEGORIES,
    'sizes'      => Garment::SIZES,
    'colors'     => Garment::COLORS,
    'statuses'   => Garment::STATUSES,
];
