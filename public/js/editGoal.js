document.addEventListener('DOMContentLoaded', function () {
    // Event listener for showing the modal
    $('#goalsCategoryModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var url = button.data('url'); // Extract URL from data-url attribute
        console.log(url);
        var userId = button.data('user-id'); // Extract user ID from data-user-id attribute
    
        var modal = $(this);
        modal.find('#modal-body-content').html(''); // Clear previous content
    
        $.ajax({
            url: url,
            method: 'GET',
            success: function (data) {
                // Load form into modal body
                modal.find('#modal-body-content').html(data.content);
    
                // Now that the form is loaded, set the hidden field value
                modal.find('#goals_user').val(userId);
    
                // Optional: Focus on the first input field
                setTimeout(function () {
                    modal.find("input[type='text']").first().focus();
                }, 300);
            },
            error: function (xhr, status, error) {
                console.error('Error loading modal content:', error);
                modal.find('#modal-body-content').html('<p>An error occurred while loading the form.</p>');
            }
        });
    });
    

    // Optionally handle form submission via AJAX
    $(document).on('submit', '#goals-form', function (e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this);
        var url = form.attr('action'); 

        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(), // Serialize form data
            success: function (response) {
                if (response.status === 'success') {
                    $('#goalsCategoryModal').modal('hide'); // Hide modal on success
                    location.reload(); // Reload the page or update the goals list
                } else {
                    $('#goalsCategoryModal').find('#modal-body-content').html(response.content); // Handle validation errors
                }
            },
            error: function (xhr, status, error) {
                console.error('Error submitting form:', error);
                $('#goalsCategoryModal').find('#modal-body-content').html('<p>An error occurred while submitting the form.</p>');
            }
        });
    });

    $(document).on('click', '#mark-completed-btn', function () {
        var button = $(this);
        var completedDate = button.data('completed-date');
        var goalId = button.data('goal-id');

        $.ajax({
            url: '/update-date/' + goalId,
            type: 'POST',
            data: {
                completedDate: completedDate
            },
            success: function(response) {
                location.reload(); // Reload the page or update the page content
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });
});
