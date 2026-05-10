<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGarmentRequest;
use App\Http\Requests\UpdateGarmentRequest;
use App\Models\Garment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class GarmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Identity helper — mock-ready
    |--------------------------------------------------------------------------
    | Devuelve el ID del usuario autenticado.
    | Cuando el sistema de auth esté listo, basta con eliminar el fallback.
    */
    private function currentUserId(Request $request): ?int
    {
        // TODO: reemplazar fallback cuando auth esté integrado
        return $request->user()?->id ?? 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Explorar — galería pública
    |--------------------------------------------------------------------------
    | Muestra TODAS las prendas con status "available".
    | No muestra botones de edición/eliminación.
    */
    public function index(Request $request)
    {
        $query = Garment::with('user')
            ->where('status', Garment::STATUS_AVAILABLE);

        // Excluir las prendas del usuario actual (sólo ver las de otros)
        $currentUserId = $this->currentUserId($request);
        if ($currentUserId) {
            $query->where('user_id', '!=', $currentUserId);
        }

        $query
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->filled('size'), fn ($q) => $q->where('size', $request->input('size')))
            ->when($request->filled('color'), fn ($q) => $q->where('color', $request->input('color')))
            ->orderByDesc('created_at');

        $garments = $query->paginate(12)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $garments->items(),
                'meta' => [
                    'current_page' => $garments->currentPage(),
                    'last_page'    => $garments->lastPage(),
                    'per_page'     => $garments->perPage(),
                    'total'        => $garments->total(),
                ],
            ]);
        }

        return view('garments.explore', [
            'garments' => $garments,
            'filters'  => $request->only(['category', 'size', 'color']),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Mis Prendas — panel privado
    |--------------------------------------------------------------------------
    | Muestra SOLO las prendas del usuario actual, con todos los estados.
    | Incluye botones de Editar, Eliminar y cambio de estado.
    */
    public function myGarments(Request $request)
    {
        $currentUserId = $this->currentUserId($request);

        $query = Garment::where('user_id', $currentUserId);

        $query
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->filled('size'), fn ($q) => $q->where('size', $request->input('size')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('color'), fn ($q) => $q->where('color', $request->input('color')))
            ->orderByDesc('created_at');

        $garments = $query->paginate(12)->withQueryString();

        $metrics = [
            'total'     => Garment::where('user_id', $currentUserId)->count(),
            'available' => Garment::where('user_id', $currentUserId)->where('status', 'available')->count(),
            'reserved'  => Garment::where('user_id', $currentUserId)->where('status', 'reserved')->count(),
            'sold'      => Garment::where('user_id', $currentUserId)->where('status', 'sold')->count(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'data'    => $garments->items(),
                'meta'    => [
                    'current_page' => $garments->currentPage(),
                    'last_page'    => $garments->lastPage(),
                    'per_page'     => $garments->perPage(),
                    'total'        => $garments->total(),
                ],
                'metrics' => $metrics,
            ]);
        }

        return view('garments.my-garments', [
            'garments' => $garments,
            'metrics'  => $metrics,
            'filters'  => $request->only(['category', 'size', 'status', 'color']),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Crear — formulario
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('garments.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar — registro de nueva prenda
    |--------------------------------------------------------------------------
    | El status se fuerza a "available". El usuario no controla este campo.
    */
    public function store(StoreGarmentRequest $request)
    {
        $validated = $request->validated();

        // Forzar estado inicial
        $validated['status'] = Garment::STATUS_AVAILABLE;

        // Asignar propietario
        $validated['user_id'] = $this->currentUserId($request);

        // Manejo de imagen
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('garments', 'public');
        }

        $garment = Garment::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['data' => $garment], Response::HTTP_CREATED);
        }

        return redirect()
            ->route('garments.show', $garment)
            ->with('status', 'Prenda publicada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Detalle — vista pública
    |--------------------------------------------------------------------------
    */
    public function show(Request $request, Garment $garment)
    {
        $currentUserId = $this->currentUserId($request);
        $isOwner = $garment->isOwnedBy($currentUserId);

        if ($request->expectsJson()) {
            return response()->json([
                'data'     => $garment,
                'is_owner' => $isOwner,
            ]);
        }

        return view('garments.show', [
            'garment' => $garment,
            'isOwner' => $isOwner,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Editar — solo el propietario
    |--------------------------------------------------------------------------
    */
    public function edit(Request $request, Garment $garment)
    {
        $currentUserId = $this->currentUserId($request);

        if (! $garment->isOwnedBy($currentUserId)) {
            abort(403, 'No tienes permiso para editar esta prenda.');
        }

        return view('garments.edit', ['garment' => $garment]);
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar — solo el propietario
    |--------------------------------------------------------------------------
    */
    public function update(UpdateGarmentRequest $request, Garment $garment)
    {
        $currentUserId = $this->currentUserId($request);

        if (! $garment->isOwnedBy($currentUserId)) {
            abort(403, 'No tienes permiso para actualizar esta prenda.');
        }

        $validated = $request->validated();

        // Manejo de imagen (reemplaza si se sube una nueva)
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($garment->image_path && Storage::disk('public')->exists($garment->image_path)) {
                Storage::disk('public')->delete($garment->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('garments', 'public');
        }

        $garment->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['data' => $garment]);
        }

        return redirect()
            ->route('garments.show', $garment)
            ->with('status', 'Prenda actualizada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar — solo el propietario
    |--------------------------------------------------------------------------
    | Validación de propiedad: si el user_id de la prenda no coincide con el
    | usuario actual, se retorna un error 403 (No autorizado).
    | Limpieza de archivos: si la prenda tiene imagen asociada, se elimina
    | del disco antes de borrar el registro.
    */
    public function destroy(Request $request, Garment $garment)
    {
        $currentUserId = $this->currentUserId($request);

        // — Seguridad de propiedad —
        if (! $garment->isOwnedBy($currentUserId)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tienes permiso para realizar esta acción.',
                ], Response::HTTP_FORBIDDEN);
            }

            abort(403, 'No tienes permiso para eliminar esta prenda.');
        }

        // — Limpieza de archivos —
        if ($garment->image_path && Storage::disk('public')->exists($garment->image_path)) {
            Storage::disk('public')->delete($garment->image_path);
        }

        $garment->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Prenda eliminada correctamente.']);
        }

        return redirect()
            ->route('garments.my')
            ->with('status', 'Prenda eliminada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Cambio de estado — solo el propietario (AJAX-friendly)
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, Garment $garment)
    {
        $currentUserId = $this->currentUserId($request);

        if (! $garment->isOwnedBy($currentUserId)) {
            abort(403, 'No tienes permiso para cambiar el estado de esta prenda.');
        }

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Garment::STATUSES))],
        ], [
            'status.required' => 'Debes seleccionar un estado.',
            'status.in'       => 'El estado seleccionado no es válido.',
        ]);

        $garment->update(['status' => $request->input('status')]);

        if ($request->expectsJson()) {
            return response()->json(['data' => $garment]);
        }

        return redirect()
            ->route('garments.my')
            ->with('status', 'Estado actualizado a "' . $garment->statusLabel() . '" correctamente.');
    }
}
