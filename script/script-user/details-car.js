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

    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const newImageSrc = this.dataset.fullImage;
                mainImage.src = newImageSrc;
                mainImage.alt = this.querySelector('img').alt;
                
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }


    const likeBtn = document.getElementById('like-btn');

    if (likeBtn) {
        likeBtn.addEventListener('click', function () {
            const carId = this.dataset.carId;

            fetch('../php-user/like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'car_id=' + encodeURIComponent(carId)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.liked) {
                        likeBtn.classList.add('liked');
                        likeBtn.querySelector('.like-text').textContent = 'Liked';
                    } else {
                        likeBtn.classList.remove('liked');
                        likeBtn.querySelector('.like-text').textContent = 'Like';
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                alert('Gagal koneksi ke server.');
                console.error(err);
            });
        });
    }

    const shareBtn = document.getElementById('share-btn');

    if (shareBtn && navigator.share) {
        shareBtn.addEventListener('click', () => {
        const shareData = {
            title: document.title,
            text: 'Lihat mobil ini di MobilSecond.id!',
            url: window.location.href
        };

        navigator.share(shareData)
            .then(() => console.log('Berhasil dibagikan'))
            .catch((error) => console.log('Gagal membagikan', error));
        });
    } else if (shareBtn) {
        shareBtn.addEventListener('click', () => {
        alert("Fitur berbagi tidak didukung di browser ini.\nSalin link secara manual: " + window.location.href);
        });
    }
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
    const rincianDpMinEl = document.getElementById('rincian-dp-min');
    const rincianDpMaxEl = document.getElementById('rincian-dp-max');


    let leasingRules = [];

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    const formatAngka = (number) => new Intl.NumberFormat('id-ID').format(number);
    
    const parseRupiah = (rupiahString) => {
        if (!rupiahString) return 0;
        const cleanString = rupiahString.toString().replace(/[^0-9]/g, '');
        return parseFloat(cleanString) || 0;
    };

    const debounce = (func, delay) => {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    function calculateCredit(){
        console.log('Calculating credit...'); 
        
        const hargaMobil = parseRupiah(hargaMobilEl.value);
        const dpRp = parseRupiah(dpRpEl.value);
        const tenorTahun = parseInt(tenorSliderEl.value);

        console.log('Harga mobil:', hargaMobil); 
        console.log('DP Rp:', dpRp); 
        console.log('Tenor:', tenorTahun); 
        console.log('Selected leasing:', leasingSelectEl.value); 

        const selectedLeasing = leasingRules.find(rule => rule.leasing_name === leasingSelectEl.value);
        
        if (!selectedLeasing) {
            console.log('No leasing selected');
            return;
        }
        
        if (!hargaMobil || hargaMobil <= 0) {
            console.log('Invalid harga mobil'); 
            return;
        }

        if (isNaN(dpRp) || dpRp < 0) {
            console.log('Invalid DP'); 
            return;
        }

        console.log('Selected leasing data:', selectedLeasing); 

        const minDpPercentage = parseFloat(selectedLeasing.min_dp_percentage);
        const maxDpPercentage = parseFloat(selectedLeasing.max_dp_percentage);
        const minDpRp = hargaMobil * (minDpPercentage/100);
        const maxDpRp = hargaMobil * (maxDpPercentage/100);
        if(dpRp < minDpRp) {
           hasilAngsuranEl.textContent = 'Rp -';
           hasilTotalDpEl.textContent = 'DP Min. ' + formatRupiah(minDpRp) + ' (' + minDpPercentage + '%)';
           rincianDpMinEl.textContent =  formatRupiah(minDpRp) + ' (' + minDpPercentage + '%)';
           rincianDpMaxEl.textContent = formatRupiah(maxDpRp) + ' (' + maxDpPercentage + '%)';
           return; 
        }

        const pokokHutang = hargaMobil - dpRp;
        if(pokokHutang <= 0){ 
            hasilAngsuranEl.textContent = 'Rp 0';
            hasilTotalDpEl.textContent = formatRupiah(hargaMobil);
            rincianDpEl.textContent = formatRupiah(dpRp);
            rincianAdminEl.textContent = 'Rp 0';
            rincianCicilan1El.textContent = 'Rp 0';
            return;
        }

        const interestRateKey = `interest_rate_${tenorTahun}yr`;
        const sukuBungaTahunan = parseFloat(selectedLeasing[interestRateKey]);
        
        if (isNaN(sukuBungaTahunan)) {
            console.log('Invalid interest rate for tenor:', tenorTahun); 
            return;
        }

        const totalBunga = (pokokHutang * (sukuBungaTahunan / 100)) * tenorTahun;
        const totalHutang = pokokHutang + totalBunga;
        const angsuranBulanan = totalHutang / (tenorTahun * 12);
        const biayaAdmin = parseFloat(selectedLeasing.admin_fee) || 0;
        const totalDpBayar = dpRp + biayaAdmin + angsuranBulanan;

        console.log('Calculation results:', {
            pokokHutang,
            sukuBungaTahunan,
            totalBunga,
            totalHutang,
            angsuranBulanan,
            biayaAdmin,
            totalDpBayar
        }); 

        hasilTotalDpEl.textContent = formatRupiah(totalDpBayar);
        hasilAngsuranEl.textContent = formatRupiah(angsuranBulanan);
        rincianDpEl.textContent = formatRupiah(dpRp);
        rincianAdminEl.textContent = formatRupiah(biayaAdmin);
        rincianCicilan1El.textContent = formatRupiah(angsuranBulanan);
    }

    dpRpEl.addEventListener('input', debounce(() =>{
        const hargaMobil = parseRupiah(hargaMobilEl.value);
        const dpRp = parseRupiah(dpRpEl.value);

        if (dpRp > 0) {
            dpRpEl.value = formatAngka(dpRp);
        }

        if(hargaMobil > 0 && dpRp >= 0){
            const persen = ((dpRp / hargaMobil) * 100).toFixed(1);
            dpPersenEl.value = persen;
        }
        calculateCredit();
    }, 300));

    dpPersenEl.addEventListener('input', debounce(() => {
        const hargaMobil = parseRupiah(hargaMobilEl.value);
        const persen = parseFloat(dpPersenEl.value) || 0;
        
        if (hargaMobil > 0 && persen >= 0) {
            const dpRp = Math.round((hargaMobil * persen) / 100);
            dpRpEl.value = formatAngka(dpRp);
        }
        calculateCredit();
    }, 300));

    tenorSliderEl.addEventListener('input', () => {
        tenorValueEl.textContent = tenorSliderEl.value;
        calculateCredit();
    });

    leasingSelectEl.addEventListener('change', () => {
        console.log('Leasing changed to:', leasingSelectEl.value); 
        calculateCredit();
    });

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

            if (leasingSelectEl && leasingRules.length > 0) {
                leasingSelectEl.innerHTML = '<option value="">-- Pilih Mitra Pembiayaan --</option>' + 
                    leasingRules
                        .map(rule => `<option value="${rule.leasing_name}">${rule.leasing_name}</option>`)
                        .join('');
            }
            
        } else {
            console.error('API Error:', result.error);
            alert('Gagal memuat data pembiayaan: ' + result.error);
        }
    })
    .catch(error => {
        console.error('Gagal total dalam proses fetch:', error);
        alert('Gagal memuat data pembiayaan. Silakan refresh halaman.');
    });
});