const formMain = document.getElementById('formMain');

formMain.addEventListener('submit', async function(e) {
    e.preventDefault();

    const fd = new FormData(this);
    const response = await fetch(this.action, {
        method: 'post',
        body: fd
    });
    const data = await response.json();

    if (!data.ok) {
        notify(data.message, 'danger');
        return;
    }

    document.location.href = '/settings';
});