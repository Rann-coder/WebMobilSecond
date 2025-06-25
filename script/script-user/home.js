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
                typesList.querySelector('[data-type="All"]').classList.remove('active');
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
                typesList.querySelector('[data-type="All"]').classList.add('active');
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

        const maxDisplayCars = 11;
        const carsToShow = cars.slice(0, maxDisplayCars);
        const hasMoreCars = cars.length >= maxDisplayCars;


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
        
       const seeMoreCard = hasMoreCars ? `
        <div class="vehicle-card see-more-card" onclick="showAllCars()">
            <div class="see-more-content">
                <div class="see-more-icon">
                    <div class="car-icons">
                        <svg class="car-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5H6.5C5.84 5 5.28 5.42 5.08 6.01L3 12V20C3 20.55 3.45 21 4 21H5C5.55 21 6 20.55 6 20V19H18V20C18 20.55 18.45 21 19 21H20C20.55 21 21 20.55 21 20V12L18.92 6.01ZM6.5 16C5.67 16 5 15.33 5 14.5S5.67 13 6.5 13 8 13.67 8 14.5 7.33 16 6.5 16ZM17.5 16C16.67 16 16 15.33 16 14.5S16.67 13 17.5 13 19 13.67 19 14.5 18.33 16 17.5 16ZM5 11L6.5 6.5H17.5L19 11H5Z"/>
                        </svg>
                        <svg class="car-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23 15.5C23 16.33 22.33 17 21.5 17C20.67 17 20 16.33 20 15.5S20.67 14 21.5 14C22.33 14 23 14.67 23 15.5ZM6.5 14C7.33 14 8 14.67 8 15.5S7.33 17 6.5 17 5 16.33 5 15.5 5.67 14 6.5 14ZM19 13H18.5L17.79 9.67C17.61 8.75 16.81 8.09 15.86 8.09H8.14C7.19 8.09 6.39 8.75 6.21 9.67L5.5 13H5C3.34 13 2 14.34 2 16V19C2 19.55 2.45 20 3 20H4C4.55 20 5 19.55 5 19V18H19V19C19 19.55 19.45 20 20 20H21C21.55 20 22 19.55 22 19V16C22 14.34 20.66 13 19 13Z"/>
                        </svg>
                        <svg class="car-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 11L6.5 6.5H17.5L19 11H5ZM17.5 16C16.67 16 16 15.33 16 14.5S16.67 13 17.5 13 19 13.67 19 14.5 18.33 16 17.5 16ZM6.5 16C5.67 16 5 15.33 5 14.5S5.67 13 6.5 13 8 13.67 8 14.5 7.33 16 6.5 16ZM18.92 6.01C18.72 5.42 18.16 5 17.5 5H6.5C5.84 5 5.28 5.42 5.08 6.01L3 12V20C3 20.55 3.45 21 4 21H5C5.55 21 6 20.55 6 20V19H18V20C18 20.55 18.45 21 19 21H20C20.55 21 21 20.55 21 20V12L18.92 6.01Z"/>
                        </svg>
                    </div>
                </div>
                <div class="see-more-text">
                    <h3>Klik untuk melihat selengkapnya</h3>
                    <p>+${cars.length - maxDisplayCars} mobil lainnya</p>
                </div>
            </div>
        </div>
    ` : '';

    vehicleGrid.innerHTML = carsHTML + seeMoreCard;
    }

    filterCars();
});