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
                console.log(data)

            })
    } catch (e) {
        console.log('catch exception fetch :', e);
    }
}

getUsers();
// console.log(users);


// function mycallback(users) {
//     users.map((user) => {
//         console.log(user.user_id)
//     })
// }


// getUsersXhr(mycallback);


// const getAllUsers = getUsers();
// console.log(typeof(getAllUsers));





// ////////////////////        TEST        ///////////////////////////
var locations = [
    ["LOCATION_1", 11.8166, 122.0942],
    ["LOCATION_2", 11.9804, 121.9189],
    ["LOCATION_3", 10.7202, 122.5621],
    ["LOCATION_4", 11.3889, 122.6277],
    ["LOCATION_5", 10.5929, 122.6325]
];

var map = L.map('mapid').setView([11.206051, 122.447886], 8);
var mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; ' + mapLink + ' Contributors',
        maxZoom: 18,
    }).addTo(map);

for (var i = 0; i < locations.length; i++) {
    var marker = new L.marker([locations[i][1], locations[i][2]])
        .bindPopup(
            "<b>Open Food Truck</b><br>This is the place to be<br><img src=\"assets/images/truck.gif\" width=\"45\" height=\"45\">")
        .addTo(map);
}



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