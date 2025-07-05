document.addEventListener('DOMContentLoaded', function () {
    let selectedBrands = ['All'];
    let selectedTypes = ['All'];

    const brandsList = document.getElementById('brands-list');
    const typesList = document.getElementById('types-list');
    const vehicleGrid = document.querySelector('.vehicle-grid');
    const advancedFilterInputs = [
        'price-min', 'price-max', 'year-min', 'year-max', 'km-min', 'km-max',
        'transmission-filter', 'owners-filter', 'engine-filter'
    ];

    const scrollLeftBrands = document.getElementById('scroll-left-btn-brands');
    const scrollRightBrands = document.getElementById('scroll-right-btn-brands');
    
    const scrollLeftTypes = document.getElementById('scroll-left-btn-types');
    const scrollRightTypes = document.getElementById('scroll-right-btn-types');

    const toggleButton = document.getElementById('toggle-filters');
    const filtersPanel = document.getElementById('filters-panel');

    populateYearOptions();
    //EVENT LISTENER
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


    if (toggleButton && filtersPanel) {
        toggleButton.addEventListener('click', function () {
            toggleButton.classList.toggle('active');
            filtersPanel.classList.toggle('active');
        });
    }

    function populateYearOptions() {
        const currentYear = new Date().getFullYear();
        const startYear = 2000;
        const yearMinSelect = document.getElementById('year-min');
        const yearMaxSelect = document.getElementById('year-max');

        if (!yearMinSelect || !yearMaxSelect) {
            return;
        }

        for (let year = currentYear; year >= startYear; year--) {
            const optionMin = document.createElement('option');
            optionMin.value = year;
            optionMin.textContent = year;
            yearMinSelect.appendChild(optionMin);

            const optionMax = document.createElement('option');
            optionMax.value = year;
            optionMax.textContent = year;
            yearMaxSelect.appendChild(optionMax);
        }
    }

    advancedFilterInputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            const eventType = element.tagName.toLowerCase() === 'select' ? 'change' : 'input';
            element.addEventListener(eventType, debounce(filterCars, 500));
        }
    });

    function handleBrandSelection(button) {
        const brandName = button.textContent.trim();

        if (brandName === 'All') {
            selectedBrands = ['All'];
            brandsList.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        } else {
            //Remove All
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
        console.log("merek terpilih: ", selectedBrands);
        filterCars();
    }

    function handleTypeSelection(button) {
        const typeName = button.textContent.trim();

        if(typeName === 'All'){
            selectedTypes = ['All']
            typesList.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active')
            });
            button.classList.add('active');
        } else {
            if(selectedTypes.includes('All')){
                selectedTypes = [];
                typesList.querySelector('[data-type="All"]').classList.remove('active');
            }

            if(selectedTypes.includes(typeName)){
                selectedTypes = selectedTypes.filter(type => type !== typeName);
                button.classList.remove('active');
            } else {
                selectedTypes.push(typeName);
                button.classList.add('active');
            }

            if(selectedTypes.length === 0) {
                selectedTypes = ['All'];
                typesList.querySelector('[data-type="All"]').classList.add('active');
            }
        }
        console.log("tipe terpilih: ", selectedTypes);
        filterCars();
    }

    function getFilterValues(){
        const values = {
            brands: selectedBrands,
            types: selectedTypes,
        };

        advancedFilterInputs.forEach(id => {
            const element = document.getElementById(id);
            if(element){
                const key = id.replace(/-(\w)/g, (_, c) => c.toUpperCase());
                values[key] = element.value || null;
            }
        });

        return values;
    }


    function filterCars(){
        vehicleGrid.innerHTML = '<div class="loading">Loading cars...</div>';

        const allFilters = getFilterValues();
        console.log("Filter yang sedang aktif:", allFilters);
        const formData = new FormData();

        for(const key in allFilters){
            if(Array.isArray(allFilters[key])){
                formData.append(key, JSON.stringify(allFilters[key]));
            } else if(allFilters[key] !== null){
                formData.append(key, allFilters[key]);
            }
        }

        fetch('../api/all-cars.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
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
            vehicleGrid.innerHTML = '<div class="error">Jaringan gagal. Silakan dicoba ulang</div>';
        });
    }

    function displayCars(cars){
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
                        <p class="vehicle-price">${car.formatted_price}</p>
                    </div>
                </a>
            </div>
        `).join('');
        vehicleGrid.innerHTML = carsHTML;
    }
    

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    filterCars();

    
});