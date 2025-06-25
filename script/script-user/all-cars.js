document.addEventListener('DOMContentLoaded', function(){
    const toggleButton = document.getElementById('toggle-filters');
    const filtersPanel = document.getElementById('filters-panel');

    if(toggleButton && filtersPanel){
        toggleButton.addEventListener('click', function() {
            toggleButton.classList.toggle('active');
            filtersPanel.classList.toggle('active');
        });
    }
});