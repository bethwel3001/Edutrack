document.addEventListener('DOMContentLoaded', () => {
    // Auto-set today's date if not selected
    const dateInput = document.querySelector('input[type="date"]');
    if (dateInput && !dateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }

    // Prevent future dates
    dateInput?.addEventListener('change', (e) => {
        const selectedDate = new Date(e.target.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate > today) {
            alert("Cannot mark attendance for future dates!");
            e.target.value = today.toISOString().split('T')[0];
        }
    });

    // Confirmation on submit
    const form = document.querySelector('.attendance-form form');
    form?.addEventListener('submit', (e) => {
        if (!confirm("Submit attendance for today?")) {
            e.preventDefault();
        }
    });
});