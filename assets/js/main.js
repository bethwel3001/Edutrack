document.addEventListener('DOMContentLoaded', () => {
    // Delete user confirmation
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm("Permanently delete this user?")) {
                e.preventDefault();
            }
        });
    });

    // Toggle dark mode
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        });

        // Load saved preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    }
});