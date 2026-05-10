<?php

namespace App\Http\Requests;

use App\Models\Garment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGarmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para creación de prendas.
     *
     * Nota: el campo 'status' NO se acepta en creación.
     * El controlador fuerza status = 'available' automáticamente.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price'       => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:999999.99'],
            'category'    => ['required', Rule::in(array_keys(Garment::CATEGORIES))],
            'size'        => ['required', Rule::in(array_keys(Garment::SIZES))],
            'color'       => ['required', Rule::in(array_keys(Garment::COLORS))],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'    => 'Por favor ingresa un nombre para la prenda.',
            'name.min'         => 'El nombre debe tener al menos 3 caracteres.',
            'name.max'         => 'El nombre no puede superar los 120 caracteres.',
            'description.max'  => 'La descripción no puede superar los 1000 caracteres.',
            'price.required'   => 'Ingresa un precio para la prenda.',
            'price.numeric'    => 'El precio debe ser un número válido.',
            'price.decimal'    => 'El precio puede tener máximo 2 decimales.',
            'price.gt'         => 'El precio debe ser mayor a cero.',
            'price.max'        => 'El precio no puede superar $999,999.99.',
            'category.required'=> 'Selecciona una categoría.',
            'category.in'      => 'Selecciona una categoría válida.',
            'size.required'    => 'Selecciona una talla.',
            'size.in'          => 'Selecciona una talla válida.',
            'color.required'   => 'Selecciona un color.',
            'color.in'         => 'Selecciona un color válido.',
            'image.image'      => 'El archivo debe ser una imagen válida.',
            'image.mimes'      => 'La imagen debe ser JPG, PNG o WebP.',
            'image.max'        => 'La imagen no puede superar los 2 MB.',
        ];
    }
}
