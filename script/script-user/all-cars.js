document.addEventListener('DOMContentLoaded', function () {
    let selectedBrands = ['All'];
    let selectedTypes = ['All'];
    let searchTerm = '';
    let allCarsData = [];

    const brandsList = document.getElementById('brands-list');
    const typesList = document.getElementById('types-list');
    const vehicleGrid = document.querySelector('.vehicle-grid');
    const searchInput = document.getElementById('car-search-input');
    const searchButton = document.getElementById('search-button');
    const toggleButton = document.getElementById('toggle-filters');
    const filtersPanel = document.getElementById('filters-panel');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const resultsCountElement = document.getElementById('results-count');
    const scrollLeftBrands = document.getElementById('scroll-left-btn-brands');
    const scrollRightBrands = document.getElementById('scroll-right-btn-brands');
    const scrollLeftTypes = document.getElementById('scroll-left-btn-types');
    const scrollRightTypes = document.getElementById('scroll-right-btn-types');

    function filterCars() {
        vehicleGrid.innerHTML = '<div class="loading">Memuat mobil...</div>';
        const currentFilters = getFilterValues();
        const formData = new FormData();

        for (const key in currentFilters) {
            const value = currentFilters[key];
            if (value && value !== null && value !== '') {
                if (Array.isArray(value)) {
                    formData.append(key, JSON.stringify(value));
                } else {
                    formData.append(key, value);
                }
            }
        }
        
        fetch('../api/all-cars.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                allCarsData = data.data;
                resultsCountElement.textContent = `${data.count} mobil ditemukan`;
                displayCars();
            } else {
                vehicleGrid.innerHTML = `<div class="error">Error: ${data.error || 'Gagal memuat data.'}</div>`;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            vehicleGrid.innerHTML = '<div class="error">Jaringan gagal. Silakan coba lagi.</div>';
        });
    }
    
    function displayCars() {
        if (!allCarsData || allCarsData.length === 0) {
            vehicleGrid.innerHTML = '<div class="no-results">Tidak ada mobil yang cocok dengan kriteria Anda.</div>';
            return;
        }

        const carsHTML = allCarsData.map(createCarCard).join('');
        vehicleGrid.innerHTML = carsHTML;
    }

    function getFilterValues() {
        const values = {
            brands: selectedBrands,
            types: selectedTypes,
            searchTerm: searchTerm,
            priceMin: document.getElementById('price-min').value,
            priceMax: document.getElementById('price-max').value,
            yearMin: document.getElementById('year-min').value,
            yearMax: document.getElementById('year-max').value,
            kmMin: document.getElementById('km-min').value,
            kmMax: document.getElementById('km-max').value,
            transmission: document.getElementById('transmission-filter').value,
            owners: document.getElementById('owner-filter').value,
            engine: document.getElementById('engine-filter').value,
        };
        const fuelCheckboxes = document.querySelectorAll('.fuel-checkbox:checked');
        if (fuelCheckboxes.length > 0) {
            values.fuelType = Array.from(fuelCheckboxes).map(cb => cb.value);
        }
        return values;
    }

    function handleBrandSelection(button) {
        const brandName = button.textContent.trim();
        if (brandName === 'All') {
            selectedBrands = ['All'];
            brandsList.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        } else {
            if (selectedBrands.includes('All')) {
                selectedBrands = [];
                brandsList.querySelector('[data-brand="All"]').classList.remove('active');
            }
            const index = selectedBrands.indexOf(brandName);
            if (index > -1) {
                selectedBrands.splice(index, 1);
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
            typesList.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        } else {
            if (selectedTypes.includes('All')) {
                selectedTypes = [];
                typesList.querySelector('[data-type="All"]').classList.remove('active');
            }
            const index = selectedTypes.indexOf(typeName);
            if (index > -1) {
                selectedTypes.splice(index, 1);
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

    function clearAllFilters() {
        document.querySelectorAll('.filters-grid input, .filters-grid select').forEach(input => input.value = '');
        document.querySelectorAll('.fuel-checkbox').forEach(cb => cb.checked = false);
        searchInput.value = '';
        searchTerm = '';
        
        const allBrandButton = brandsList.querySelector('[data-brand="All"]');
        if(allBrandButton) handleBrandSelection(allBrandButton);
        
        const allTypeButton = typesList.querySelector('[data-type="All"]');
        if(allTypeButton) handleTypeSelection(allTypeButton);

        filterCars();
    }
    
    function createCarCard(car) {
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
    
    function populateYearOptions() {
        const currentYear = new Date().getFullYear();
        const startYear = 2000;
        const yearMinSelect = document.getElementById('year-min');
        const yearMaxSelect = document.getElementById('year-max');

        if (!yearMinSelect || !yearMaxSelect) return;

        for (let year = currentYear; year >= startYear; year--) {
            yearMinSelect.add(new Option(year, year));
            yearMaxSelect.add(new Option(year, year));
        }
        yearMinSelect.value = '';
        yearMaxSelect.value = '';
    }

    function initializeEventListeners() {
        searchButton.addEventListener('click', () => {
            searchTerm = searchInput.value.trim();
            filterCars();
        });
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                searchTerm = searchInput.value.trim();
                filterCars();
            }
        });

        brandsList.addEventListener('click', (e) => {
            if (e.target.classList.contains('filter-btn')) handleBrandSelection(e.target);
        });

        typesList.addEventListener('click', (e) => {
            if (e.target.classList.contains('filter-btn')) handleTypeSelection(e.target);
        });

        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                toggleButton.classList.toggle('active');
                filtersPanel.classList.toggle('active');
            });
        }
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', filterCars);
        }
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', clearAllFilters);
        }

        scrollLeftBrands.addEventListener('click', () => brandsList.scrollBy({ left: -200, behavior: 'smooth' }));
        scrollRightBrands.addEventListener('click', () => brandsList.scrollBy({ left: 200, behavior: 'smooth' }));
        scrollLeftTypes.addEventListener('click', () => typesList.scrollBy({ left: -200, behavior: 'smooth' }));
        scrollRightTypes.addEventListener('click', () => typesList.scrollBy({ left: 200, behavior: 'smooth' }));
    }

    populateYearOptions();
    initializeEventListeners();
    filterCars();
});