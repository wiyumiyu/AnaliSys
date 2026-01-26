<!-- Modal Confirmar Eliminación -->
<div class="modal fade"
     id="confirmDeleteModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger" id="confirmDeleteTitle">
                    Confirmar eliminación
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p id="confirmDeleteMessage" class="mb-0">
                    ¿Está seguro que desea eliminar este elemento?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-danger"
                        id="confirmDeleteBtn">
                    Eliminar
                </button>
            </div>

        </div>
    </div>
</div>
