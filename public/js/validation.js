var Main = Main || {};

(function($, module){

    // Function to remove 'is-invalid' class on input
    function removeInvalidClass(selector) {
        $(document).on("keyup click", selector, function () {
            $(this).removeClass("is-invalid");
        });
    }

    // Determine if we are on the create page
    const isCreatePage = window.location.pathname.includes('/admin/create');

    // Generalized save event handler
    function saveHandler(saveBtn, nameSelector, emailSelector, firstPsw, secondPsw, depmSelector) {
        $(document).on("click", saveBtn, function (e) {
            e.preventDefault();

            const $name = nameSelector ? $(nameSelector) : null;
            const $emailSelector = emailSelector ? $(emailSelector) : null;
            const $firstPsw = firstPsw ? $(firstPsw) : null;
            const $secondPsw = secondPsw ? $(secondPsw) : null;
            const $depmSelector = depmSelector ? $(depmSelector) : null;

            const isValid = module.validate($name, $emailSelector, $firstPsw, $secondPsw, $depmSelector, isCreatePage);

            if (isValid) {
                $(this).closest('form').submit();
            }
        });
    }

    // Register remove 'is-invalid' events
    removeInvalidClass("#employee_name, #employee_email, #employee_password_first, #employee_password_second, #employee_department, #feedback_description");

    saveHandler("#employee_save", "#employee_name", "#employee_email", "#employee_password_first", "#employee_password_second", "#employee_department");
    saveHandler("#feedback_save", "#feedback_description", null, null, null, null);

    module.validate = function ($name, $emailSelector, $firstPsw, $secondPsw, $depmSelector, isCreatePage) {
        let isValid = true;

        if ($name && !$name.val()) {
            $name.addClass("is-invalid");
            isValid = false;
        } else {
            $name && $name.removeClass("is-invalid");
        }

        if ($emailSelector && !$emailSelector.val()) {
            $emailSelector.addClass("is-invalid");
            isValid = false;
        } else {
            $emailSelector && $emailSelector.removeClass("is-invalid");
        }

        if (isCreatePage) {
            if ($firstPsw && !$firstPsw.val()) {
                $firstPsw.addClass("is-invalid");
                isValid = false;
            } else {
                $firstPsw && $firstPsw.removeClass("is-invalid");
            }

            if ($secondPsw && !$secondPsw.val()) {
                $secondPsw.addClass("is-invalid");
                isValid = false;
            } else {
                $secondPsw && $secondPsw.removeClass("is-invalid");
            }
        }

        if ($depmSelector && !$depmSelector.val()) {
            $depmSelector.addClass("is-invalid");
            isValid = false;
        } else {
            $depmSelector && $depmSelector.removeClass("is-invalid");
        }

        return isValid;
    };

})(jQuery, Main);
