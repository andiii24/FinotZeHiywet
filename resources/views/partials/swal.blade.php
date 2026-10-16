{{-- Shared SweetAlert2 flash notifications + confirmation helpers --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    const toastDefaults = {
        confirmButtonColor: '#032b44',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    };

    window.AppSwal = {
        success(message, title) {
            return Swal.fire({
                ...toastDefaults,
                icon: 'success',
                title: title || 'Success',
                text: message,
                timer: 3500,
                timerProgressBar: true,
            });
        },

        error(message, title) {
            return Swal.fire({
                ...toastDefaults,
                icon: 'error',
                title: title || 'Error',
                html: typeof message === 'string' ? message : String(message),
            });
        },

        warning(message, title) {
            return Swal.fire({
                ...toastDefaults,
                icon: 'warning',
                title: title || 'Warning',
                text: message,
            });
        },

        info(message, title) {
            return Swal.fire({
                ...toastDefaults,
                icon: 'info',
                title: title || 'Notice',
                text: message,
            });
        },

        /**
         * Confirm before submitting the closest form (or a given form element).
         * Use on type="button" or with onclick that prevents default submit.
         */
        confirmSubmit(triggerOrForm, options) {
            options = options || {};
            const form = triggerOrForm && triggerOrForm.tagName === 'FORM'
                ? triggerOrForm
                : (triggerOrForm && triggerOrForm.closest ? triggerOrForm.closest('form') : null);

            if (!form) {
                console.error('AppSwal.confirmSubmit: form not found');
                return;
            }

            return Swal.fire({
                ...toastDefaults,
                title: options.title || 'Are you sure?',
                html: options.html || null,
                text: options.html ? undefined : (options.text || ''),
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmButtonColor || '#ef4444',
                confirmButtonText: options.confirmButtonText || 'Yes, continue',
                cancelButtonText: options.cancelButtonText || 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        },

        confirm(options) {
            options = options || {};
            return Swal.fire({
                ...toastDefaults,
                title: options.title || 'Are you sure?',
                html: options.html || null,
                text: options.html ? undefined : (options.text || ''),
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonColor: options.confirmButtonColor || '#032b44',
                confirmButtonText: options.confirmButtonText || 'Yes',
                cancelButtonText: options.cancelButtonText || 'Cancel',
                input: options.input || undefined,
                inputLabel: options.inputLabel || undefined,
                inputPlaceholder: options.inputPlaceholder || undefined,
                inputAttributes: options.inputAttributes || undefined,
                inputValidator: options.inputValidator || undefined,
            });
        },

        /**
         * Build and submit a POST form (supports Laravel _method spoofing).
         */
        post(action, fields) {
            fields = fields || {};
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrf);

            Object.keys(fields).forEach(function (name) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = fields[name];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        },
    };

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            AppSwal.success(@json(session('success')));
        @endif

        @if(session('error'))
            AppSwal.error(@json(session('error')));
        @endif

        @if(session('warning'))
            AppSwal.warning(@json(session('warning')));
        @endif

        @if(session('info') || session('status'))
            AppSwal.info(@json(session('info') ?? session('status')));
        @endif

        @if($errors->any())
            AppSwal.error(
                `{!! implode('<br>', $errors->all()) !!}`,
                'Validation Error'
            );
        @endif
    });
})();
</script>
