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
        <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="name">
            Nombre de la prenda <span class="text-rose-500">*</span>
        </label>
        <input
            class="rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm shadow-sm transition focus:border-[#5aa9e6] focus:ring-1 focus:ring-[#5aa9e6] focus:outline-none font-medium"
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $garment?->name ?? '') }}"
            placeholder="Ej: Blusa de algodón"
            maxlength="120"
            required
        >
        <p class="text-[10px] text-stone-400 font-medium">Mínimo 3, máximo 120 caracteres.</p>
        @error('name')
            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ===== Descripción ===== --}}
    <div class="grid gap-2">
        <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="description">Descripción</label>
        <textarea
            class="min-h-[120px] rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm shadow-sm transition focus:border-[#5aa9e6] focus:ring-1 focus:ring-[#5aa9e6] focus:outline-none font-medium"
            id="description"
            name="description"
            placeholder="Describe la prenda, su estado, material..."
            maxlength="1000"
        >{{ old('description', $garment?->description ?? '') }}</textarea>
        <p class="text-[10px] text-stone-400 font-medium">Máximo 1000 caracteres.</p>
        @error('description')
            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ===== Precio + Status (condicional) ===== --}}
    <div class="grid gap-6 {{ $isEdit ? 'sm:grid-cols-2' : 'sm:grid-cols-1' }}">
        <div class="grid gap-2">
            <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="price">
                Precio (USD) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-stone-400">$</span>
                <input
                    class="w-full rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm pl-8 pr-4 py-2.5 text-sm shadow-sm transition focus:border-[#5aa9e6] focus:ring-1 focus:ring-[#5aa9e6] focus:outline-none font-medium"
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
            <p class="text-[10px] text-stone-400 font-medium">Valor positivo, máximo 2 decimales.</p>
            @error('price')
                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status — SOLO en edición --}}
        @if ($isEdit)
            <div class="grid gap-2">
                <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="status">
                    Estado de disponibilidad <span class="text-rose-500">*</span>
                </label>
                <select
                    class="rounded-full border border-stone-200 bg-white px-4 py-2.5 text-xs font-bold text-stone-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer"
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
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>

    {{-- ===== Categoría / Talla / Color ===== --}}
    <div class="grid gap-6 sm:grid-cols-3">

        {{-- Categoría --}}
        <div class="grid gap-2">
            <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="category">
                Categoría <span class="text-rose-500">*</span>
            </label>
            <select
                class="rounded-full border border-stone-200 bg-white px-4 py-2.5 text-xs font-bold text-stone-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer"
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
                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Talla --}}
        <div class="grid gap-2">
            <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="size">
                Talla <span class="text-rose-500">*</span>
            </label>
            <select
                class="rounded-full border border-stone-200 bg-white px-4 py-2.5 text-xs font-bold text-stone-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer"
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
                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Color --}}
        <div class="grid gap-2">
            <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="color">
                Color <span class="text-rose-500">*</span>
            </label>
            <select
                class="rounded-full border border-stone-200 bg-white px-4 py-2.5 text-xs font-bold text-stone-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer"
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
                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ===== Imagen ===== --}}
    <div class="grid gap-2">
        <label class="text-xs font-bold text-stone-700 uppercase tracking-wider" for="image">Fotografía</label>
        @if ($isEdit && $garment?->image_path)
            <div class="mb-2">
                <img
                    src="{{ asset('storage/' . $garment->image_path) }}"
                    alt="{{ $garment->name }}"
                    class="h-32 w-32 rounded-2xl border border-stone-200 object-cover shadow-md"
                >
                <p class="mt-1.5 text-[10px] text-stone-400 font-medium">Sube una nueva imagen para reemplazar la actual.</p>
            </div>
        @endif
        <input
            class="rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm shadow-sm file:mr-3 file:rounded-xl file:border-0 file:bg-[#5aa9e6]/10 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-[#2974a6] transition focus:border-[#5aa9e6] focus:outline-none"
            id="image"
            name="image"
            type="file"
            accept="image/jpeg,image/png,image/webp"
        >
        <p class="text-[10px] text-stone-400 font-medium">Formatos soportados: JPG, PNG o WebP. Tamaño máximo de archivo: 2 MB.</p>
        @error('image')
            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
