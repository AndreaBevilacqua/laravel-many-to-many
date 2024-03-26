const deleteForms = document.querySelectorAll('.delete-form');
deleteForms.forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();

        const hasConfirmed = confirm('Sicuro di voler eliminare questo progetto?');
        if (hasConfirmed) form.submit();
    })
})