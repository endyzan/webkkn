<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peta Brakas Dajah</title>

    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        #map {
            width: 100%;
            height: 500px;
        }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([-7.1653, 112.9256], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 🔹 Polygon batas wilayah (contoh)
    const batasBrakasDajah = [
        [-7.1585, 112.9170],
        [-7.1585, 112.9340],
        [-7.1725, 112.9380],
        [-7.1760, 112.9270],
        [-7.1700, 112.9150]
    ];

    L.polygon(batasBrakasDajah, {
        color: 'blue',
        fillColor: '#4da6ff',
        fillOpacity: 0.4
    }).addTo(map)
      .bindPopup("Batas Wilayah Brakas Dajah");
    // 🔴 Area dengan jumlah penduduk
    const areaPenduduk = L.circle([-7.1653, 112.9256], {
        radius: 300, // meter
        color: 'red',
        fillColor: '#ff4d4d',
        fillOpacity: 0.5
    }).addTo(map);

    areaPenduduk.bindPopup("<b>Jumlah Penduduk:</b> 100 Orang");

    // 🔢 Angka di tengah lingkaran
    const labelPenduduk = L.marker([-7.1653, 112.9256], {
        icon: L.divIcon({
            className: 'label-penduduk',
            html: '<div style="font-size:18px;font-weight:bold;color:black;">100</div>'
        })
    }).addTo(map);
</script>

</body>
</html>
