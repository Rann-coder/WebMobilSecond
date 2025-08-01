document.addEventListener('DOMContentLoaded', function() {
    let selectedBrands = ['All'];
    let selectedTypes = ['All'];
    let searchTerm = '';
    let allCars = [];
    let isShowingAll = false;
    const brandsList = document.getElementById('brands-list');
    const typesList = document.getElementById('types-list');
    const vehicleGrid = document.querySelector('.vehicle-grid');
    const searchInput = document.getElementById('car-search-input');
const searchButton = document.getElementById('search-button');
    
    const scrollLeftBrands = document.getElementById('scroll-left-btn-brands');
    const scrollRightBrands = document.getElementById('scroll-right-btn-brands');
    const scrollLeftTypes = document.getElementById('scroll-left-btn-types');
    const scrollRightTypes = document.getElementById('scroll-right-btn-types');

    function initializeEventListeners() {
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

        searchButton.addEventListener('click', () => {
        searchTerm = searchInput.value;
        filterCars();
    });

    searchInput.addEventListener('keyup', (event) => {
        if (event.key === 'Enter') {
            searchTerm = searchInput.value;
            filterCars();
        }
    });
    
    }

    function handleBrandSelection(button) {
        const brandName = button.textContent.trim();
        
        if (brandName === 'All') {
            selectedBrands = ['All'];
            brandsList.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        } else {
            if (selectedBrands.includes('All')) {
                selectedBrands = [];
                const allButton = brandsList.querySelector('[data-brand="All"]');
                if (allButton) allButton.classList.remove('active');
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
                const allButton = brandsList.querySelector('[data-brand="All"]');
                if (allButton) allButton.classList.add('active');
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
                const allButton = typesList.querySelector('[data-type="All"]');
                if (allButton) allButton.classList.remove('active');
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
                const allButton = typesList.querySelector('[data-type="All"]');
                if (allButton) allButton.classList.add('active');
            }
        }

        filterCars();
    }

    function filterCars() {
        isShowingAll = false;
        vehicleGrid.innerHTML = '<div class="loading">Loading cars...</div>';

        const formData = new FormData();
        formData.append('brands', JSON.stringify(selectedBrands));
        formData.append('types', JSON.stringify(selectedTypes));
        formData.append('search_term', searchTerm); 

        fetch('../api/filter_cars.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
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

    function displayCars(cars) {
        allCars = cars;

        if (cars.length === 0) {
            vehicleGrid.innerHTML = '<div class="no-results">No cars found matching your criteria.</div>';
            return;
        }

        const maxDisplayCars = 10;
        const carsToShow = isShowingAll ? cars : cars.slice(0, maxDisplayCars);
        const hasMoreCars = cars.length > maxDisplayCars && !isShowingAll;

        const carsHTML = carsToShow.map(car => createCarCard(car)).join('');
        const seeMoreCard = hasMoreCars ? createSeeMoreCard(cars.length - maxDisplayCars) : '';

        vehicleGrid.innerHTML = carsHTML + seeMoreCard;
    }

    function createCarCard(car) {
        let specs = {};
        try {
            if (car.specifications) {
                specs = JSON.parse(car.specifications);
            }
        } catch (e) {
            console.error('Failed to parse specifications for:', car.name);
        }

        return `
            <div class="vehicle-card">
                <a href="../php-user/details-car.php?slug=${car.slug}" class="card-link-wrapper">
                    <div class="vehicle-image-container">
                        <img src="../${car.image_url}" alt="${car.name}" onerror="this.src='../images/no-image.png'">
                    </div>
                    <div class="vehicle-info">
                        <div>
                            <p class="vehicle-brand">${car.brand_name || 'Brand'}</p>
                            <h3 class="vehicle-title">${car.name}</h3>
                        </div>
                        <div class="vehicle-price-container">
                            <p class="vehicle-price-label">Mulai dari</p>
                            <p class="vehicle-price">${car.formatted_price}</p>
                        </div>
                        <div class="vehicle-specs">
                            ${createSpecItem('Tahun', car.year, 'Tahun Pembuatan')}
                            ${createSpecItem('Kilometer', car.formatted_km, 'Kilometer')}
                            ${createEngineSpecItem(car.engine_cc)}
                            ${createOwnerSpecItem(car.previous_owners)}
                        </div>
                    </div>
                </a>
            </div>
        `;
    }

    function createSpecItem(label, value, title) {
        if (value == null) return '';
        return `
            <div class="spec-item" title="${title}">
                <span class="spec-label">${label}</span>
                <span class="spec-value">${value}</span>
            </div>
        `;
    }

    function createEngineSpecItem(engineCc) {
        if (engineCc == null) return '';
        const value = engineCc === 0 ? 'Listrik' : `${engineCc} cc`;
        return createSpecItem('CC Mesin', value, 'CC Mesin');
    }

    function createOwnerSpecItem(previousOwners) {
        if (previousOwners == null) return '';
        return createSpecItem('Pemilik', `Tangan ke-${previousOwners}`, 'Pemilik Sebelumnya');
    }

    function createSeeMoreCard(remainingCount) {
        return `
            <div class="vehicle-card see-more-card" onclick="showAllCars()">
                <div class="see-more-content">
                    <div class="see-more-icon">
                        <div class="car-icons">
                            ${createCarIcon()}
                            ${createCarIcon()}
                            ${createCarIcon()}
                        </div>
                    </div>
                    <div class="see-more-text">
                        <h3>Klik untuk melihat selengkapnya</h3>
                        <p>+${remainingCount} mobil lainnya</p>
                    </div>
                </div>
            </div>
        `;
    }

    function createCarIcon() {
        return `
            <svg class="car-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5H6.5C5.84 5 5.28 5.42 5.08 6.01L3 12V20C3 20.55 3.45 21 4 21H5C5.55 21 6 20.55 6 20V19H18V20C18 20.55 18.45 21 19 21H20C20.55 21 21 20.55 21 20V12L18.92 6.01ZM6.5 16C5.67 16 5 15.33 5 14.5S5.67 13 6.5 13 8 13.67 8 14.5 7.33 16 6.5 16ZM17.5 16C16.67 16 16 15.33 16 14.5S16.67 13 17.5 13 19 13.67 19 14.5 18.33 16 17.5 16ZM5 11L6.5 6.5H17.5L19 11H5Z"/>
            </svg>
        `;
    }

    window.showAllCars = function() {
        isShowingAll = true;
        displayCars(allCars);
    };

    function init() {
        initializeEventListeners();
        filterCars(); 
    }

    
    init();
});