document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('.content-table tbody');

    if(tableBody){
        tableBody.addEventListener('click', function(event) {
            const button = event.target;

            const action = button.dataset.action;
            if(action === 'approve' || action === 'reject'){
                if(button.dissabled){
                    console.log("Aksi dibatalkan dikarenakan mobil belum di-review");
                    return;
                }

                const carId = button.dataset.id;
                const coonfirmation = confirm(`Anda yakin ingin melakukan "${action} pada mobil dengan ID ${carId}?"`);
                if(coonfirmation){
                    updateCarStatus(carId, action, button);
                }
            }
        });
    }
});