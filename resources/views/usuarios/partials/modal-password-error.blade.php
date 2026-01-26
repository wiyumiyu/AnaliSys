<div class="modal fade"
     id="passwordErrorModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    Error al cambiar la contraseña
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>{{ $passwordError }}</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary"
                        data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
