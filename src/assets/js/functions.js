/**
 * Select Address
 * 
 */
$('#address').select2({
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
            const results = data.features.map(address => ({
                id: address.geometry.coordinates[0] + '|' + address.geometry.coordinates[1] 
                + '|' + address.properties.name + '|' + address.properties.postcode + '|' + address.properties.city,
                text: address.properties.label
            }));
            console.log(results);
            return {
                results
            };
        }
    },
});

$('#address').on('select2:select', function (e) {
    let arrayResults = e.params.data.id.split('|');
    // console.log(arrayResults);
    $('#longitude').val(arrayResults[0]);
    $('#latitude').val(arrayResults[1]);
    $('#street').val(arrayResults[2]);
    $('#postcode').val(arrayResults[3]);
    $('#city').val(arrayResults[4]);
});


/**
 * Select coocking diets
 * 
 */
$(document).ready(function() {
    $('#coocking-diets').select2({
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
$(document).ready(function() {
    $('#coocking-types').select2({
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
$(document).ready(function() {
    $('#coocking-origins').select2({
        closeOnSelect: false,
        placeholder: {
            id: '-1',
            text: 'Aucun'
          }
      });
});
