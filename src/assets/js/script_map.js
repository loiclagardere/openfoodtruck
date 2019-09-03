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


const returnVar = function (a) {
return a;
}


/**
 * 
 * request api map with fetch function
 * 
 */
const getUsersFetch = async function () { // async doesn't stop script execution
    try {
        let response = await fetch('includes/api_map.php'); // use try catch
        // console.log(response) // promise
        if (response.ok) { // booleen - http status between 200 and 299
            let data = await response.json();
            mycallback(data);
            
        } else {
            // console.log('Serveur : ', reponse.status);
        }
    } catch (e) {
        // console.log('catch exception fetch :', e);
    }
}

getUsersFetch();


/**
 * 
 * request api map with xhr object
 */
const getUsersXhr = function (callback) {
    const xhrUser = new XMLHttpRequest();
    xhrUser.onreadystatechange = function (event) {
        // console.log(xhrUser.readyState);
        let xhrUserParse = JSON.parse(xhrUser.response);
        // console.log('data', xhrUserParse);
        if (this.readyState === XMLHttpRequest.DONE) {
            if (xhrUser.status === 200) {
                // console.log("Réponse reçue: %s", this.responseText);
            } else {
                // console.log("Status de la réponse: %d (%s)", this.status, this.statusText);
            }
        }
        // console.log((xhrUser.responseText));
    }
    xhrUser.open('GET', 'includes/api_map.php', true);
    xhrUser.send(null);
}

function mycallback(users) {
    users.map((user) => {
        console.log(user)
    })
}


// getUsersXhr(mycallback);


// console.log();
/**
 * 
 * get all users infromations
 */
const getUsers = function () {
    // if (window.fetch) {
    //     console.log('get users with fetch');
        getUsersFetch();

    // } else {
    // console.log('get users with xhr');
    // getUsersXhr();
    // }
}

const getAllUsers = getUsers();
// console.log(typeof(getAllUsers));

// ////////////////////        TEST        ///////////////////////////

// const markerUser = function (array) {
//     console.log('test');
//     var markers = new L.layerGroup();
//     for (var i = 0; i < array.length; i++) {
//         var item = array[i];
//         marker = new L.marker([array[1],array[2]]).bindPopup(array[0]);
//         markers.addLayer(marker);
//     }
//     map.addLayer(markers);
// }

// getUsers().forEach(function(element) {
//     console.log(element);
//   });

// // Display all users on the map
// let  marker = L.marker([43.487155, -1.48707]).addTo(mymap);

//////////////////////////////////////////////////////////////////





// // initial position on the map
// let mymap = L.map('mapid').setView([43.627925, -1.406389], 10); // 43.487155, -1.48707

// // marker on the map
// let marker = L.marker([43.627925, -1.406389]).addTo(mymap); // 43.487155, -1.48707

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
//     id: 'mapbox.streets',
// }).addTo(mymap);