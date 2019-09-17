'use strict'
// test fetch ///////////////
// fetch('includes/api_map.php')
// .then(function(response) {
//     return response.json() // a promise is return
// }).then(function (data) {
//     console.log(data)
// })


// with es6 /////////////
// fetch('includes/api_map.php')
// .then(response => response.json().then(console.log))



let users;

/**
 * 
 * request api map with fetch function
 * 
 */
const getUsers = function () {
    try {
        fetch('includes/api_map.php')
            .then(function (response) {
                return response.json(); // a promise is return
            }).then(function (data) {
                console.log(data);

                // Create the map
                let map = L.map('mapid').setView([46.66502, 2.406393], 5);

                // Leaflet contributors
                let mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
                L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; ' + mapLink + ' Contributors',
                    maxZoom: 18,
                }).addTo(map);

                // Craete markers
                for (let i = 0; i < data.length; i++) {
                    if (data[i].company_latitude != null && data[i].company_longitude != null) {
                        let marker = new L.marker([+data[i].company_latitude, +data[i].company_longitude])

                            .bindPopup(
                                "<b>" + data[i].company_name + "</b><br" + data[i].company_label + "<br><img src=\"assets/images/icone-logo.png\" width=\"60\" height=\"40\">")
                            .addTo(map);
                    }
                }
            })
    } catch (e) {
        console.log('catch exception fetch :', e);
    }
}

getUsers();