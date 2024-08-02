var Delete = Delete || {};

(function($, module) {
    module.onShowModal = function () {
        $(function() {
            const $modal = $('#deleteModal');
            const $deleteForm = $('#deleteButton');
        
            $('.delete').on('click', function() {
                const { name, id, entity } = $(this).data();
        
                $('#deleteName').text(name);
                $('#deleteId').val(id);
        
                $deleteForm.attr('href', `/admin/delete/${id}`);
                $modal.modal('show');
            });
        });
    }
})(jQuery, Delete);