document.addEventListener("DOMContentLoaded", function () {
    // Get modal and its elements
    const modal = document.getElementById("imageModal");
    const modalImage = document.getElementById("modalImage");
    const closeModal = document.querySelector(".close");

    // Show modal function
    function showModal(imageElement, imageUrl) {
        // Open the modal
        modal.style.display = "flex";

        // Set the image source in the modal
        modalImage.src = imageUrl;
    }

    // Close modal functionality
    closeModal.addEventListener("click", () => {
        modal.style.display = "none"; // Hide the modal
        // Set the image source in the modal
        modalImage.src = '';
    });

    // Close modal when clicking outside the dialog/image (optional)
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none"; // Hide the modal
            // Set the image source in the modal
            modalImage.src = '';
        }
    });

    // Attach the `showModal()` function to the global window object
    window.showModal = showModal;
});
