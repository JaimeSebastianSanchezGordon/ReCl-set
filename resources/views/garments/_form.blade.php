{{--
    Formulario reutilizable para crear/editar prendas.

    Variables esperadas:
    - $garment (nullable): instancia del modelo para edición
    - $isEdit (bool, optional): true si estamos en modo edición

    Nota: el campo 'status' SOLO se muestra en modo edición.
    En creación, el controlador fuerza status = 'available'.
--}}

@php
    $garment  = $garment ?? null;
    $isEdit   = $isEdit ?? ($garment !== null);

    $categories = \App\Models\Garment::CATEGORIES;
    $sizes      = \App\Models\Garment::SIZES;
    $colors     = \App\Models\Garment::COLORS;
    $statuses   = \App\Models\Garment::STATUSES;
@endphp

<div class="grid gap-6">

    {{-- ===== Nombre ===== --}}
    <div class="grid gap-2">
        <label class="text-sm font-medium text-stone-700" for="name">
            Nombre <span class="text-rose-500">*</span>
        </label>
        <input
            class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $garment?->name ?? '') }}"
            placeholder="Ej: Blusa de algodón"
            maxlength="120"
            required
        >
        <p class="text-xs text-stone-400">Mínimo 3, máximo 120 caracteres.</p>
        @error('name')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- ===== Descripción ===== --}}
    <div class="grid gap-2">
        <label class="text-sm font-medium text-stone-700" for="description">Descripción</label>
        <textarea
            class="min-h-[120px] rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
            id="description"
            name="description"
            placeholder="Describe la prenda, su estado, material..."
            maxlength="1000"
        >{{ old('description', $garment?->description ?? '') }}</textarea>
        <p class="text-xs text-stone-400">Máximo 1000 caracteres.</p>
        @error('description')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- ===== Precio + Status (condicional) ===== --}}
    <div class="grid gap-6 {{ $isEdit ? 'sm:grid-cols-2' : 'sm:grid-cols-1' }}">
        <div class="grid gap-2">
            <label class="text-sm font-medium text-stone-700" for="price">
                Precio (USD) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-stone-400">$</span>
                <input
                    class="w-full rounded-md border border-stone-300 bg-white pl-7 pr-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                    id="price"
                    name="price"
                    step="0.01"
                    min="0.01"
                    type="number"
                    value="{{ old('price', $garment?->price ?? '') }}"
                    placeholder="0.00"
                    required
                >
            </div>
            <p class="text-xs text-stone-400">Valor positivo, máximo 2 decimales.</p>
            @error('price')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status — SOLO en edición --}}
        @if ($isEdit)
            <div class="grid gap-2">
                <label class="text-sm font-medium text-stone-700" for="status">
                    Estado <span class="text-rose-500">*</span>
                </label>
                <select
                    class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                    id="status"
                    name="status"
                    required
                >
                    @foreach ($statuses as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(old('status', $garment?->status ?? 'available') === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>

    {{-- ===== Categoría / Talla / Color (selectores cerrados) ===== --}}
    <div class="grid gap-6 sm:grid-cols-3">

        {{-- Categoría --}}
        <div class="grid gap-2">
            <label class="text-sm font-medium text-stone-700" for="category">
                Categoría <span class="text-rose-500">*</span>
            </label>
            <select
                class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                id="category"
                name="category"
                required
            >
                <option value="">— Seleccionar —</option>
                @foreach ($categories as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(old('category', $garment?->category ?? '') === $value)
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Talla --}}
        <div class="grid gap-2">
            <label class="text-sm font-medium text-stone-700" for="size">
                Talla <span class="text-rose-500">*</span>
            </label>
            <select
                class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                id="size"
                name="size"
                required
            >
                <option value="">— Seleccionar —</option>
                @foreach ($sizes as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(old('size', $garment?->size ?? '') === $value)
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('size')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Color --}}
        <div class="grid gap-2">
            <label class="text-sm font-medium text-stone-700" for="color">
                Color <span class="text-rose-500">*</span>
            </label>
            <select
                class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                id="color"
                name="color"
                required
            >
                <option value="">— Seleccionar —</option>
                @foreach ($colors as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(old('color', $garment?->color ?? '') === $value)
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('color')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ===== Imagen ===== --}}
    <div class="grid gap-2">
        <label class="text-sm font-medium text-stone-700" for="image">Imagen</label>
        @if ($isEdit && $garment?->image_path)
            <div class="mb-2">
                <img
                    src="{{ asset('storage/' . $garment->image_path) }}"
                    alt="{{ $garment->name }}"
                    class="h-32 w-32 rounded-lg border border-stone-200 object-cover shadow-sm"
                >
                <p class="mt-1 text-xs text-stone-400">Sube una nueva imagen para reemplazar la actual.</p>
            </div>
        @endif
        <input
            class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-1 file:text-sm file:font-medium file:text-emerald-700 transition focus:border-emerald-500 focus:outline-none"
            id="image"
            name="image"
            type="file"
            accept="image/jpeg,image/png,image/webp"
        >
        <p class="text-xs text-stone-400">JPG, PNG o WebP. Máximo 2 MB.</p>
        @error('image')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
