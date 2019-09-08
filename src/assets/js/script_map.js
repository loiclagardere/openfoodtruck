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
                return response.json() // a promise is return
            }).then(function (data) {
                // users = data
                // console.log(data);
               let map = L.map('mapid').setView([46.66502, 2.406393], 5);
                let mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
                L.tileLayer(
                    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; ' + mapLink + ' Contributors',
                        maxZoom: 18,
                    }).addTo(map);

                for (let i = 0; i < data.length; i++) {
                 if (data[i].company_latitude != null && data[i].company_longitude != null) {
                    let marker = new L.marker([+data[i].company_latitude, +data[i].company_longitude])

                        .bindPopup(
                            "<b>"+data[i].company_name+"</b><br"+data[i].company_label+"<br><img src=\"assets/images/icone-logo.png\" width=\"60\" height=\"40\">")
                        .addTo(map);
                        }
                }

            })
    } catch (e) {
        console.log('catch exception fetch :', e);
    }
}

// getUsers();


console.log(getUsers());


// function mycallback(users) {
//     users.map((user) => {
//         console.log(user.user_id)
//     })
// }



// ////////////////////        TEST        ///////////////////////////
// let locations = [
//     ["LOCATION_1", 11.8166, 122.0942],
//     ["LOCATION_2", 11.9804, 121.9189],
//     ["LOCATION_3", 10.7202, 122.5621],
//     ["LOCATION_4", 11.3889, 122.6277],
//     ["LOCATION_5", 10.5929, 122.6325]
// ];

//let locations = getUsers();





//////////////////////////////////////////////////////////////////


// // initial position on the map
// let mymap = L.map('mapid').setView([43.627925, -1.406389], 10); // 43.487155, -1.48707

// // marker on the map
// let marker = L.marker([41.927925, -1.406389]).addTo(mymap); // 43.487155, -1.48707

// // Pop-up : marker
// marker.bindPopup(
//     "<b>Open Food Truck</b><br>This is the place to be<br><img src=\"assets/images/truck.gif\" width=\"45\" height=\"45\">"
// ).openPopup();

// // Pop-up : coodronnées geolocalisation
// let popup = L.popup();

// function onMapClick(e) {
//     popup
//         .setLatLng(e.latlng)
//         .setContent("You clicked the map at " + e.latlng.toString())
//         .openOn(mymap);
// }
// mymap.on('click', onMapClick);


// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
//     maxZoom: 18,
// }).addTo(mymap);