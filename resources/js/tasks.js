// Ajax for task status toggle
document.addEventListener('DOMContentLoaded', function() {
    const toggleForms = document.querySelectorAll('[id^="toggle-form-"]');
    
    toggleForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status element without page reload
                    const taskRow = form.closest('tr');
                    const statusCell = taskRow.querySelector('td:nth-child(4)');
                    
                    if (data.status === 'Pending') {
                        statusCell.innerHTML = '<span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs">Pending</span>';
                    } else {
                        statusCell.innerHTML = '<span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs">Completed</span>';
                    }
                    
                    // Update the toggle button's color
                    const toggleButton = form.querySelector('button');
                    if (data.status === 'Pending') {
                        toggleButton.className = 'text-green-500 hover:text-green-700';
                    } else {
                        toggleButton.className = 'text-yellow-500 hover:text-yellow-700';
                    }
                    
                    // Show a temporary success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4';
                    alertDiv.innerHTML = `<p>${data.message}</p>`;
                    
                    const mainContent = document.querySelector('main');
                    mainContent.insertBefore(alertDiv, mainContent.firstChild);
                    
                    // Remove the alert after 3 seconds
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 3000);
                } else if (data.error) {
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4';
                    alertDiv.innerHTML = `<p>${data.error}</p>`;
                    
                    const mainContent = document.querySelector('main');
                    mainContent.insertBefore(alertDiv, mainContent.firstChild);
                    
                    // Remove the alert after 3 seconds
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});