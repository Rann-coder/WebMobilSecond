document.addEventListener('DOMContentLoaded', function() {
    let selectedBrands = ['All'];
    let selectedTypes = ['All'];

    const brandsList = document.getElementById('brands-list');
    const typesList = document.getElementById('types-list');
    const vehicleGrid = document.querySelector('.vehicle-grid'); //utk tampilkan hasil mobil
    
    const scrollLeftBrands = document.getElementById('scroll-left-btn-brands');
    const scrollRightBrands = document.getElementById('scroll-right-btn-brands');
    
    const scrollLeftTypes = document.getElementById('scroll-left-btn-types');
    const scrollRightTypes = document.getElementById('scroll-right-btn-types');

    brandsList.addEventListener('click', function(e) {
        if (e.target.classList.contains('filter-btn')) {
            handleBrandSelection(e.target);
        }
    });

    typesList.addEventListener('click', function(e) {
        if (e.target.classList.contains('filter-btn')) {
            handleTypeSelection(e.target);
        }
    });

    scrollLeftBrands.addEventListener('click', () => {
        brandsList.scrollBy({ left: -200, behavior: 'smooth' });
    });

    scrollRightBrands.addEventListener('click', () => {
        brandsList.scrollBy({ left: 200, behavior: 'smooth' });
    });

    scrollLeftTypes.addEventListener('click', () => {
        typesList.scrollBy({ left: -200, behavior: 'smooth' });
    });

    scrollRightTypes.addEventListener('click', () => {
        typesList.scrollBy({ left: 200, behavior: 'smooth' });
    });

    function handleBrandSelection(button) {
        const brandName = button.textContent.trim();
        
        if (brandName === 'All') {
            // Reset semua pilihan brand lain
            selectedBrands = ['All'];
            brandsList.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        } else {
            // Remove 'All' 
            if (selectedBrands.includes('All')) {
                selectedBrands = [];
                brandsList.querySelector('[data-brand="All"]').classList.remove('active');
            }

            if (selectedBrands.includes(brandName)) {
                selectedBrands = selectedBrands.filter(brand => brand !== brandName);
                button.classList.remove('active');
            } else {
                selectedBrands.push(brandName);
                button.classList.add('active');
            }

            if (selectedBrands.length === 0) {
                selectedBrands = ['All'];
                brandsList.querySelector('[data-brand="All"]').classList.add('active');
            }
        }

        filterCars();
    }

    function handleTypeSelection(button) {
        const typeName = button.textContent.trim();
        
        if (typeName === 'All') {
            selectedTypes = ['All'];
            typesList.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        } else {
            if (selectedTypes.includes('All')) {
                selectedTypes = [];
                typesList.querySelector('[data-brand="All"]').classList.remove('active');
            }

            if (selectedTypes.includes(typeName)) {
                selectedTypes = selectedTypes.filter(type => type !== typeName);
                button.classList.remove('active');
            } else {
                selectedTypes.push(typeName);
                button.classList.add('active');
            }

            if (selectedTypes.length === 0) {
                selectedTypes = ['All'];
                typesList.querySelector('[data-brand="All"]').classList.add('active');
            }
        }

        filterCars();
    }

    function filterCars() {
        vehicleGrid.innerHTML = '<div class="loading">Loading cars...</div>';

        const formData = new FormData();
        formData.append('brands', JSON.stringify(selectedBrands));
        formData.append('types', JSON.stringify(selectedTypes));

        // Send AJAX request (kirim formdata ke filter_cars.php dgn metode POST)
        fetch('../api/filter_cars.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) //bisa langsung ubah ke objek/array js
        .then(data => {
            if (data.success) {
                displayCars(data.data);
            } else {
                vehicleGrid.innerHTML = `<div class="error">Error: ${data.error}</div>`;
                console.error('Filter error:', data);
            }
        })
        .catch(error => {
            console.error('Network error:', error);
            vehicleGrid.innerHTML = '<div class="error">Network error. Please try again.</div>';
        });
    }

    // Display filtered cars
    function displayCars(cars) {
        if (cars.length === 0) {
            vehicleGrid.innerHTML = '<div class="no-results">No cars found matching your criteria.</div>';
            return;
        }

        const carsHTML = cars.map(car => `
            <div class="vehicle-card">
                <a href="../pages/detail.php?slug=${car.slug}" class="card-link-wrapper">
                    <div class="vehicle-image-container">
                        <img src="../${car.image_url}" alt="${car.name}" onerror="this.src='../images/no-image.png'">
                    </div>
                    <div class="vehicle-info">
                        <h3 class="vehicle-title">${car.name}</h3>
                        <p class="vehicle-brand">${car.brand_name}</p>
                        <p class="vehicle-year">${car.year || 'N/A'}</p>
                        <p class="vehicle-price">${car.formatted_price}</p>
                    </div>
                </a>
            </div>
        `).join('');

        vehicleGrid.innerHTML = carsHTML;
    }

    filterCars();
});