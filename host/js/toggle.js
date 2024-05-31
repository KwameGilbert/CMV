function updateDatabase(checkbox) {
    const isChecked = checkbox.checked;
    const value = isChecked ? 1 : 0;

    fetch('update_toggle.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ show_results: value, event_id: eventId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Database updated successfully.');
        } else {
            console.error('Error updating database.');
        }
    })
    .catch(error => console.error('Error:', error));
}
