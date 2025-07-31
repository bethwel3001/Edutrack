document.addEventListener('DOMContentLoaded', () => {
    // Toggle ID field label based on user type
    const userTypeSelect = document.querySelector('select[name="user_type"]');
    const idFieldLabel = document.querySelector('#id-field label');

    if (userTypeSelect && idFieldLabel) {
        userTypeSelect.addEventListener('change', (e) => {
            idFieldLabel.textContent = e.target.value === 'student' 
                ? 'Student ID' 
                : 'Staff ID';
        });
    }

    // Display error messages (if any)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        alert(urlParams.get('error'));
    }
});