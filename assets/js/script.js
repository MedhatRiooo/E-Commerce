document.addEventListener('DOMContentLoaded', function () {
    // Your initialization code here, if needed

    // Example: Handle form submission using Fetch API
    const addProductForm = document.getElementById('addProductForm');
    const addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));

    addProductForm.addEventListener('submit', function (event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Fetch API to handle form submission
        fetch('manage_products.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // You can handle the response as needed

            // Optionally, you can reload the product table or perform other actions
            addProductModal.hide();
        })
        .catch(error => console.error('Error:', error));
    });
});


function updateOrderStatus(orderId) {
    // Get the modal element
    var modal = document.getElementById('updateOrderStatusModal');

    // Set the order ID in the modal form
    document.getElementById('orderIdInput').value = orderId;

    // Display the modal
    modal.style.display = 'block';
}

function closeUpdateOrderStatusModal() {
    // Close the modal when the cancel button is clicked
    document.getElementById('updateOrderStatusModal').style.display = 'none';
}
