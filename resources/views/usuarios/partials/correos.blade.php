<h5 class="fw-semibold mb-3">Correos electrónicos</h5>

<div id="correos-container">

    @foreach($correos as $c)
        <div class="row g-2 mb-2">
            <div class="col-md-6">
                <input class="form-control"
                       value="{{ $c->correo }}"
                       disabled>
            </div>

            <div class="col-md-4">
                <input class="form-control"
                       value="{{ $c->descripcion }}"
                       disabled>
            </div>

            <div class="col-md-2">
                <button type="button"
                        class="btn btn-outline-danger w-100"
                        data-delete-url="{{ route('usuarios.delete.correo', $c->id) }}"
                        data-delete-type="correo">
                    ✖
                </button>
            </div>
        </div>
    @endforeach

</div>

<button type="button"
        class="btn btn-outline-primary btn-sm mt-2"
        onclick="agregarCorreo()">
    Agregar correo
</button>

<!-- TEMPLATE NUEVO CORREO -->
<template id="correo-template">
    <div class="row g-2 mb-2">
        <div class="col-md-6">
            <input type="email"
                   name="nuevo_correo[]"
                   class="form-control"
                   placeholder="correo@dominio.com"
                   required>
        </div>

        <div class="col-md-4">
            <select name="correo_desc[]" class="form-select" required>
                <option value="SECUNDARIO" selected>Secundario</option>
                <option value="PRINCIPAL">Principal</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="button"
                    class="btn btn-outline-secondary w-100"
                    onclick="this.closest('.row').remove()">—</button>
        </div>
    </div>
</template>
