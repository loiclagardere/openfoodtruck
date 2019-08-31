/**
 * Select Address
 * 
 */
$('#company-situation').select2({
    debug: true,
    minimumInputLength: 2,
    ajax: {
        placeholder: {
            id: '-1',
            text: 'Saisissez l\'adresse de votre food truck'
        },
        url: function (params) {
            return 'https://api-adresse.data.gouv.fr/search/?q=' + params.term;
        },
        dataType: 'json',
        processResults: function (data) {
            const results = data.features.map(situation => ({
                id: situation.properties.citycode,
                text: situation.properties.label,
                longitude: situation.geometry.coordinates[0],
                latitude: situation.geometry.coordinates[1],
                name: situation.properties.name,
                postcode: situation.properties.postcode,
                city: situation.properties.city
            }));
            console.log(results);
            return {
                results
            };
        }
    },
});

$('#company-situation').on('select2:select', function (e) {
    let results = e.params.data;
    console.log(results);
    $('#company-label').val(results.text);
    $('#company-street').val(results.name);
    $('#company-postcode').val(results.postcode);
    $('#company-city').val(results.city);
    $('#company-latitude').val(results.latitude);
    $('#company-longitude').val(results.longitude);
});


/**
 * Select coocking diets
 * 
 */
$(document).ready(function () {
    $('#coocking-diet').select2({
        closeOnSelect: false,
        placeholder: {
            id: '-1',
            text: 'Aucun'
        }
    });
});


/**
 * Select coocking types
 * 
 */
$(document).ready(function () {
    $('#coocking-type').select2({
        closeOnSelect: false,
        placeholder: {
            id: '-1',
            text: 'Aucun'
        }
    });
});


/**
 * Select coocking origins
 * 
 */
$(document).ready(function () {
    $('#coocking-origin').select2({
        closeOnSelect: false,
        placeholder: {
            id: '-1',
            text: 'Aucun'
        }
    });
});