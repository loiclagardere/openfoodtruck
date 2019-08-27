// Select Address
$('#address').select2({
    debug: true,
    minimumInputLength: 2,
    ajax: {
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
    }
});


$('#address').on('select2:select', function (e) {
    let arrayResults = e.params.data.id.split('|');
    console.log(arrayResults);
    $('#lng').val(arrayResults[0]);
    $('#lat').val(arrayResults[1]);
    $('#street').val(arrayResults[2]);
    $('#postcode').val(arrayResults[3]);
    $('#city').val(arrayResults[4]);
});

// Select coocking types
