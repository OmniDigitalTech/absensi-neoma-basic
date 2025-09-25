<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Anda akan logout dari aplikasi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Tidak',
            backdrop: true, // Ensures full-screen modal behavior
            allowOutsideClick: false // Prevents dismissal by clicking outside
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform logout using an AJAX request
                axios.post("{{ url('/logout') }}", {
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    })
                    .then(function(response) {
                        // Redirect to the login page or another location after logout
                        window.location.href = "{{ url('/login') }}";
                    })
                    .catch(function(error) {
                        // Handle error (e.g., display an error message)
                        console.error("Logout failed:", error);
                        Swal.fire('Error', 'Logout failed. Please try again.', 'error');
                    });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.body.addEventListener('click', (event) => {
            const deleteButton = event.target.closest('.delete-btn'); // Find closest button with the class

            if (deleteButton) {
                event.preventDefault();

                // Get dynamic information from data attributes
                const itemName = deleteButton.dataset.itemName || 'item'; // Default 'item' if not provided

                Swal.fire({
                    title: 'Anda yakin?',
                    text: `Aksi ini akan menghapus ${itemName} yang dipilih!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak',
                    backdrop: true, // Ensures full-screen modal behavior
                    allowOutsideClick: false // Prevents dismissal by clicking outside
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteButton.closest('form').submit();
                    }
                });
            }
        });

        document.body.addEventListener('click', (event) => {
            const lemburButton = event.target.closest('.lembur-aprvl-btn');
            const oncallButton = event.target.closest('.oncall-aprvl-btn');

            if (lemburButton || oncallButton) {
                event.preventDefault();
                let action = '';
                let itemName = '';
                let status = '';
                // Get dynamic information from data attributes
                if (lemburButton) {
                    action = 'Lembur';
                    itemName = lemburButton.dataset.itemName || 'item'; // Default 'item' if not provided
                    status = lemburButton.dataset.status; // Get status from data attribute
                } else {
                    action = 'Oncall';
                    itemName = oncallButton.dataset.itemName || 'item'; // Default 'item' if not provided
                    status = oncallButton.dataset.status; // Get status from data attribute
                }

                const confirmText = status === 'Approved' ? 'Terima' : 'Tolak';
                Swal.fire({
                    title: 'Anda yakin?',
                    text: `Aksi ini akan ${itemName} ${action} yang dipilih!`,
                    icon: 'warning',
                    input: "textarea",
                    inputLabel: "Catatan",
                    inputPlaceholder: `Masukkan Catatan ${action} Karyawan...`,
                    inputAttributes: {
                        "aria-label": `Masukkan Catatan ${action} Karyawan`
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, ' + confirmText + '!',
                    cancelButtonText: 'Kembali',
                    backdrop: true, // Ensures full-screen modal behavior
                    allowOutsideClick: false // Prevents dismissal by clicking outside
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set the value of the hidden input fields

                        // Submit the form
                        if (lemburButton) {
                            $('.status_lembur').val(status);
                            $('.notes_lembur').val(result.value);

                            lemburButton.closest('form').submit();
                        } else {
                            $('.status_oncall').val(status);
                            $('.notes_oncall').val(result.value);

                            oncallButton.closest('form').submit();
                        }
                    }
                });
            }
        });
    });
</script>
