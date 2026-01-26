<h5 class="fw-semibold mb-3">Teléfonos</h5>

<div id="telefonos-container">

@foreach($telefonos as $t)
    <div class="row g-2 mb-2 telefono-row">

        <div class="col-md-5">
            <input name="telefono_existente[{{ $t->id }}]"
                   class="form-control telefono-input"
                   value="{{ $t->telefono }}"
                   readonly>
        </div>

        <div class="col-md-5">
            <select name="telefono_tipo_existente[{{ $t->id }}]"
                    class="form-select telefono-select d-none">
                @foreach($tiposTelefono as $tt)
                    <option value="{{ $tt->id }}"
                        @selected($tt->id == $t->id_telefono_tipo)>
                        {{ $tt->nombre }}
                    </option>
                @endforeach
            </select>

            <input class="form-control telefono-tipo-text"
                   value="{{ $t->tipo }}"
                   readonly>
        </div>

        <div class="col-md-2">
            <button type="button"
                    class="btn btn-outline-danger w-100"
                    data-delete-url="{{ route('usuarios.delete.telefono', $t->id) }}"
                    data-delete-type="telefono">
                ✖
            </button>
        </div>
    </div>
@endforeach

</div>

<button type="button"
        class="btn btn-outline-primary btn-sm mt-2"
        onclick="agregarTelefono()">
    Agregar teléfono
</button>

<!-- TEMPLATE NUEVO TELÉFONO -->
<template id="telefono-template">
    <div class="row g-2 mb-2">
        <div class="col-md-5">
            <input name="nuevo_telefono[]" class="form-control" required>
        </div>

        <div class="col-md-5">
            <select name="telefono_tipo[]" class="form-select" required>
                @foreach($tiposTelefono as $tt)
                    <option value="{{ $tt->id }}">{{ $tt->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button type="button"
                    class="btn btn-outline-secondary w-100"
                    onclick="this.closest('.row').remove()">—</button>
        </div>
    </div>
</template>
