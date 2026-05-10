<?php

namespace App\Models;

use Database\Factories\GarmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'name',
    'description',
    'image_path',
    'price',
    'category',
    'size',
    'status',
    'color',
])]
class Garment extends Model
{
    /** @use HasFactory<GarmentFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Constantes de dominio — fuente única de verdad
    |--------------------------------------------------------------------------
    | Las claves se mantienen en inglés (para la BD y código interno).
    | Los valores (labels) están en español (para la interfaz de usuario).
    */

    public const CATEGORIES = [
        'tops'        => 'Partes superiores',
        'bottoms'     => 'Partes inferiores',
        'dresses'     => 'Vestidos',
        'outerwear'   => 'Abrigos',
        'shoes'       => 'Calzado',
        'accessories' => 'Accesorios',
    ];

    public const SIZES = [
        'xs' => 'XS',
        's'  => 'S',
        'm'  => 'M',
        'l'  => 'L',
        'xl' => 'XL',
    ];

    public const COLORS = [
        'black' => 'Negro',
        'white' => 'Blanco',
        'gray'  => 'Gris',
        'blue'  => 'Azul',
        'green' => 'Verde',
        'red'   => 'Rojo',
        'brown' => 'Marrón',
        'beige' => 'Beige',
    ];

    public const STATUSES = [
        'available' => 'Disponible',
        'reserved'  => 'Reservada',
        'sold'      => 'Vendida',
    ];

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED  = 'reserved';
    public const STATUS_SOLD      = 'sold';

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de presentación
    |--------------------------------------------------------------------------
    */

    /** Etiqueta legible de la categoría */
    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category ?? 'Sin categoría');
    }

    /** Etiqueta legible del tamaño */
    public function sizeLabel(): string
    {
        return self::SIZES[$this->size] ?? strtoupper($this->size ?? 'N/D');
    }

    /** Etiqueta legible del color */
    public function colorLabel(): string
    {
        return self::COLORS[$this->color] ?? ucfirst($this->color ?? 'N/D');
    }

    /** Etiqueta legible del estado */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status ?? 'Desconocido');
    }

    /** URL pública de la imagen (o vacío si no tiene) */
    public function imageUrl(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return '';
    }

    /** ¿Pertenece al usuario indicado? */
    public function isOwnedBy(?int $userId): bool
    {
        if ($userId === null || $this->user_id === null) {
            return false;
        }

        return (int) $this->user_id === $userId;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }
}
