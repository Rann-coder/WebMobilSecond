document.addEventListener("DOMContentLoaded", function () {
  const cityField = document.getElementById("user-city");

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(async function (position) {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;

      try {
        const res = await fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}');
        const data = await res.json();
        let city = data.address.city || data.address.town || data.address.village || 'Tidak diketahui';

        city = city.replace(/^(City of\s+|City\s+)/i, '').trim();

        cityField.value = city;
      } catch (err) {
        cityField.value = "Gagal memuat kota";
      }
    }, function (err) {
      cityField.value = "Izin lokasi ditolak";
    });
  } else {
    cityField.value = "Geolokasi tidak didukung";
  }
});