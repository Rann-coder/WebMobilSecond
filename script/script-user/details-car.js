document.addEventListener('DOMContentLoaded', function(){
    const scrollLeftBtn = document.getElementById('scroll-left-thumb');
    const scrollRightBtn = document.getElementById('scroll-right-thumb');
    const thumbnailContainer = document.querySelector('.thumbnail-container');

    const mainImage = document.getElementById('main-car-image');
    const thumbnails = document.querySelectorAll('.thumbnail-item');

    if(scrollLeftBtn && scrollRightBtn && thumbnailContainer) {
        scrollLeftBtn.addEventListener('click', () => {
            thumbnailContainer.scrollBy({left: -136, behavior: 'smooth'})
        });

        scrollRightBtn.addEventListener('click', () => {
            thumbnailContainer.scrollBy({left: 136, behavior: 'smooth'})
        });
    }

    //Elemen kalkulator
    const hargaMobilEl = document.getElementById('harga-mobil');
    const leasingSelectEl = document.getElementById('mitra-pembiayaan');
    const dpRpEl = document.getElementById('uang-muka-rp');
    const dpPersenEl = document.getElementById('uang-muka-persen');
    const tenorSliderEl = document.getElementById('tenor-pinjaman');
    const tenorValueEl = document.getElementById('tenor-value');

    //Elemen Hasil
    const hasilTotalDpEl = document.getElementById('hasil-total-dp');
    const hasilAngsuranEl = document.getElementById('hasil-angsuran');
    const rincianDpEl = document.getElementById('rincian-dp');
    const rincianAdminEl = document.getElementById('rincian-admin');
    const rincianCicilan1El = document.getElementById('rincian-cicilan1');

    let leasingRules = [];

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    const formatAngka = (number) => new Intl.NumberFormat('id-ID').format(number);
    const debounce = (func, delay) => {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    function calculateCredit(){
        const hargaMobil = parseFloat(hargaMobilEl.value);
        const dpRp = parseFloat(dpRpEl.value.replace(/[^0-9]/g, '')) || 0;
        const tenorTahun = parseInt(tenorSliderEl.value);

        const selectedLeasing = leasingRules.find(rule => rule.leasing_name === leasingSelectEl.value);
        if (!selectedLeasing || !hargaMobil || isNaN(dpRp)) return;

        //Validasi DP minimum
        const minDpPercentage = parseFloat(selectedLeasing.min_dp_percentage);
        const minDpRp = hargaMobil * (minDpPercentage/100);
        if(dpRp < minDpRp) {
           hasilAngsuranEl.textContent = 'Rp -';
           hasilTotalDpEl.textContent = 'DP Min. ' + formatRupiah(minDpRp) + ' (' + minDpPercentage + '%)';
           return; 
        }

        //Hitung Jumlah yang dipinjam
        const pokokHutang = hargaMobil - dpRp;
        if(pokokHutang <= 0){ //Jika DP lunas atau lebih
            hasilAngsuranEl.textContent = 'Rp 0';
            hasilTotalDpEl.textContent = formatRupiah(hargaMobil);
            return;
        }

        const interestRateKey = `interest_rate_${tenorTahun}yr`;
        const sukuBungaTahunan = parseFloat(selectedLeasing[interestRateKey]);
        const totalBunga = (pokokHutang * (sukuBungaTahunan / 100)) * tenorTahun;
        const totalHutang = pokokHutang + totalBunga;
        const angsuranBulanan = totalHutang / (tenorTahun * 12);
        const biayaAdmin = parseFloat(selectedLeasing.admin_fee);
        const totalDpBayar = dpRp + biayaAdmin + angsuranBulanan;

        hasilTotalDpEl.textContent = formatRupiah(totalDpBayar);
        hasilAngsuranEl.textContent = formatRupiah(angsuranBulanan);
        rincianDpEl.textContent = formatRupiah(dpRp);
        rincianAdminEl.textContent = formatRupiah(biayaAdmin);
        rincianCicilan1El.textContent = formatRupiah(angsuranBulanan);
    }


    //Event Listener Input
    //uang-muka-rp
    dpRpEl.addEventListener('input', debounce(() =>{
        const hargaMobil = parseFloat(hargaMobilEl.value);
        const dpRp = parseFloat(dpRpEl.value.replace(/[^0-9]/g, '')) || 0;

        dpRpEl.value = new Intl.NumberFormat('id-ID').format(dpRp);

        if(hargaMobil>0){
            const persen = ((dpRp / hargaMobil) * 100).toFixed(1);
            dpPersenEl.value = persen;
        }
        calculateCredit()
    }, 300));

    dpPersenEl.addEventListener('input', debounce(() => {
        const hargaMobil = parseFloat(hargaMobilEl.value);
        const persen = parseFloat(dpPersenEl.value) || 0;
        const dpRp = Math.round((hargaMobil * persen) / 100);
        dpRpEl.value = formatAngka(dpRp);
        calculateCredit();
    }, 300));

    tenorSliderEl.addEventListener('input', () => {
        tenorValueEl.textContent = tenorSliderEl.value;
        calculateCredit();
    });

    

    leasingSelectEl.addEventListener('change', calculateCredit);

    fetch('../api/get-leasing-rules.php') 
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            leasingRules = result.data;
            console.log('Data leasingRules berhasil dimuat:', leasingRules);

            //Isi dropdown mitra pembiayaan
            if (leasingSelectEl && leasingRules.length > 0) {
                leasingSelectEl.innerHTML = leasingRules
                    .map(rule => `<option value="${rule.leasing_name}">${rule.leasing_name}</option>`)
                    .join('');
            }
            calculateCredit(); 
        } else {
            console.error('API Error:', result.error);
        }
    })
    .catch(error => {
        console.error('Gagal total dalam proses fetch:', error);
    });

    
});