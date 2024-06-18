const formMain = document.getElementById('formMain');
const btnSubmit = document.getElementById('btnSubmit');
const toHide = document.getElementById('toHide');

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

    btnSubmit.setAttribute('disabled', 'disabled');
    document.body.classList.add('starFlight');

    setTimeout(function() {
        notify('3...', 'success');
    }, 100);
    setTimeout(function() {
        notify('2..', 'success');
    }, 1100);
    setTimeout(function() {
        notify('1!', 'success');
    }, 2100);
    setTimeout(function() {
        window.location.reload(true); 
    }, 2600);
});