document.addEventListener('DOMContentLoaded', function() {
const carSelect = document.getElementById('car-id');
    const carPriceInput = document.getElementById('car-price');
    const adminFeeInput = document.getElementById('admin-fee');
    const discountInput = document.getElementById('discount');
    const finalPriceDisplay = document.getElementById('final-price-display');
    const finalPriceHidden = document.getElementById('final-price-hidden');

    const customerTypeRadios = document.querySelectorAll('input[name="customer_type"]');
    const registeredCustomerBlock = document.getElementById('registered-customer-block');
    const guestCustomerBlock = document.getElementById('guest-customer-block');
    const customerIdSelect = document.getElementById('customer-id');
    const guestCustomerNameInput = document.getElementById('guest-customer-name');

    const creditSection = document.querySelector('.calculator-grid');
    const leasingProviderSelect = document.getElementById('leasing-provider');
    const dpAmountInput = document.getElementById('dp-amount');
    const dpPercentageInput = document.getElementById('dp-percentage');
    const loanTenorSlider = document.getElementById('loan-tenor');
    const tenorDisplay = document.getElementById('tenor-display');

    const resultTotalDp = document.getElementById('result-total-dp');
    const resultMonthlyInstallment = document.getElementById('result-monthly-installment');
    const monthlyInstallmentHidden = document.getElementById('monthly-installment-hidden');

    const detailDp = document.getElementById('detail-dp');
    const detailAdminFee = document.getElementById('detail-admin-fee');
    const detailFirstInstallment = document.getElementById('detail-first-installment');
    const validationMessage = document.getElementById('dp-validation-message');
    
    const statusRadios = document.querySelectorAll('input[name="status"]');

    if (carSelect) {
        new TomSelect(carSelect, {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    }


    function calculateFinalPrice() {
        const carPrice = parseFloat(carPriceInput.value) || 0;
        const adminFee = parseFloat(adminFeeInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const total = carPrice + adminFee - discount;
        finalPriceDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
        finalPriceHidden.value = total;
    }

    function updateLeasingProviders(tenor) {
        const currentlySelected = leasingProviderSelect.value;
        leasingProviderSelect.innerHTML = '<option value="">-- Tunai / Tanpa Leasing --</option>';
        const interestRateKey = `interest_rate_${tenor}yr`;
        leasingRulesData.forEach(rule => {
            if (rule[interestRateKey] && parseFloat(rule[interestRateKey]) > 0) {
                const option = document.createElement('option');
                option.value = rule.id;
                option.textContent = rule.leasing_name;
                leasingProviderSelect.appendChild(option);
            }
        });
        leasingProviderSelect.value = currentlySelected;
    }

    function resetCreditResults() {
        resultTotalDp.textContent = 'Rp 0';
        resultMonthlyInstallment.textContent = 'Rp 0';
        monthlyInstallmentHidden.value = '0';
        detailDp.textContent = 'Rp 0';
        detailAdminFee.textContent = 'Rp 0';
        detailFirstInstallment.textContent = 'Rp 0';
    }

    function updateCreditCalculation() {
        validationMessage.textContent = '';
        dpAmountInput.classList.remove('is-invalid');

        const carPrice = parseFloat(carPriceInput.value) || 0;
        const dpAmount = parseFloat(dpAmountInput.value) || 0;
        const tenor = parseInt(loanTenorSlider.value, 10);
        const providerId = leasingProviderSelect.value;
        
        if (!carPrice || !providerId) {
            resetCreditResults();
            return;
        }

        const rule = leasingRulesData.find(r => r.id == providerId);
        if (!rule) return;

        if (dpAmount > 0) {
            const minDpAmount = carPrice * (parseFloat(rule.min_dp_percentage) / 100);
            const maxDpAmount = carPrice * (parseFloat(rule.max_dp_percentage) / 100);

            if (dpAmount < minDpAmount) {
                dpAmountInput.classList.add('is-invalid');
                validationMessage.textContent = `DP minimal ${rule.min_dp_percentage}% atau Rp ${minDpAmount.toLocaleString('id-ID')}`;
                resetCreditResults();
                return;
            } else if (dpAmount > maxDpAmount) {
                dpAmountInput.classList.add('is-invalid');
                validationMessage.textContent = `DP maksimal ${rule.max_dp_percentage}% atau Rp ${maxDpAmount.toLocaleString('id-ID')}`;
                resetCreditResults();
                return;
            }
        } else {
             resetCreditResults();
             return; 
        }
        
        const loanPrincipal = carPrice - dpAmount;
        const interestRateKey = `interest_rate_${tenor}yr`;
        const annualInterestRate = parseFloat(rule[interestRateKey]) / 100;
        const adminFee = parseFloat(rule.admin_fee);
        const totalInterest = loanPrincipal * annualInterestRate * tenor;
        const totalLoan = loanPrincipal + totalInterest;
        const monthlyInstallment = totalLoan / (tenor * 12);
        const totalDpPaid = dpAmount + adminFee + monthlyInstallment;
        
        resultTotalDp.textContent = 'Rp ' + Math.ceil(totalDpPaid).toLocaleString('id-ID');
        resultMonthlyInstallment.textContent = 'Rp ' + Math.ceil(monthlyInstallment).toLocaleString('id-ID');
        monthlyInstallmentHidden.value = Math.ceil(monthlyInstallment);
        detailDp.textContent = 'Rp ' + dpAmount.toLocaleString('id-ID');
        detailAdminFee.textContent = 'Rp ' + adminFee.toLocaleString('id-ID');
        detailFirstInstallment.textContent = 'Rp ' + Math.ceil(monthlyInstallment).toLocaleString('id-ID');
    }

    function resetCreditForm() {
        leasingProviderSelect.value = '';
        dpAmountInput.value = '';
        dpPercentageInput.value = '';
        loanTenorSlider.value = 5;
        tenorDisplay.textContent = '5';
        updateLeasingProviders(5);
        updateCreditCalculation();
    }

    function toggleCreditSection(enable) {
        if (enable) {
            creditSection.classList.remove('disabled');
            leasingProviderSelect.disabled = false;
            dpAmountInput.disabled = false;
            dpPercentageInput.disabled = false;
            loanTenorSlider.disabled = false;
        } else {
            creditSection.classList.add('disabled');
            leasingProviderSelect.disabled = true;
            dpAmountInput.disabled = true;
            dpPercentageInput.disabled = true;
            loanTenorSlider.disabled = true;
            resetCreditForm();
        }
    }

    carSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption ? selectedOption.getAttribute('data-price') : 0;
        carPriceInput.value = price || 0;
        calculateFinalPrice();
        updateCreditCalculation();
    });

    [adminFeeInput, discountInput].forEach(input => {
        input.addEventListener('input', calculateFinalPrice);
    });

    customerTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const isRegistered = this.value === 'registered';
            registeredCustomerBlock.style.display = isRegistered ? 'block' : 'none';
            customerIdSelect.required = isRegistered;
            guestCustomerBlock.style.display = isRegistered ? 'none' : 'block';
            guestCustomerNameInput.required = !isRegistered;
        });
    });

    loanTenorSlider.addEventListener('input', function() {
        const newTenor = this.value;
        tenorDisplay.textContent = newTenor;
        updateLeasingProviders(newTenor);
        updateCreditCalculation();
    });

    [leasingProviderSelect, dpAmountInput, dpPercentageInput].forEach(el => {
        el.addEventListener('input', updateCreditCalculation);
    });

    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            toggleCreditSection(this.value === 'dp');
        });
    });

    dpAmountInput.addEventListener('input', function() {
        const carPrice = parseFloat(carPriceInput.value) || 0;
        if (carPrice > 0 && this.value) {
            const percentage = (parseFloat(this.value) / carPrice) * 100;
            dpPercentageInput.value = percentage.toFixed(2);
        } else {
            dpPercentageInput.value = '';
        }
    });

    dpPercentageInput.addEventListener('input', function() {
        const carPrice = parseFloat(carPriceInput.value) || 0;
        if (carPrice > 0 && this.value) {
            const amount = (parseFloat(this.value) / 100) * carPrice;
            dpAmountInput.value = amount.toFixed(0); 
        } else {
            dpAmountInput.value = '';
        }
    });

    const initialStatus = document.querySelector('input[name="status"]:checked').value;
    toggleCreditSection(initialStatus === 'dp');
    updateLeasingProviders(loanTenorSlider.value);
    calculateFinalPrice();
});