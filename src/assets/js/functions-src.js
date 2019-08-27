// Slelect Adress
$('#address').select2({
    debug: true,
    ajax: {
        url: function (params) {
            return 'https://api-adresse.data.gouv.fr/search/?q=' + params.term;
        },
        // url: 'https://api-adresse.data.gouv.fr/search/',
        dataType: 'json',
        // data: function (params) {
        //     let queryParams = {
        //         q: params.term,

        //     }
        //     // Query parameters will be ?search=[term]&type=public
        //     return queryParams;
        // },
        processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            console.log(data);
            const results = data.features.map(address => ({
                id: address.properties.id,
                text: address.properties.label
            }));
            return {
                results
            };
        },

        // transport: function (params, success, failure) {
        //     let $request = $.ajax(params);

        //     $request.then(success);
        //     $request.fail(failure);

        //     return $request;
        // }
    }
});

$(".js-example-language").select2({
    language: "fr"
});

// $('#address').select2({
//     ajax: {
//         url: function (params) {
//             'https://api-adresse.data.gouv.fr/search/?q=' + params.term;
//         },
//         dataType: 'json',
//         processResults: function (data) {
//             const results = data.features.map(address => ({
//                 id: address.features.properties.citycode,
//                 text: address.features.properties.street
//             }));
//             console.log(processResults);
//             return {
//                 // results
//                 results :[{id:2,text:'lol'}]
//             };
//         }
//     }
// });




// $('#geo_city').select2({
//     ajax: {
//         url: function (params) {
//             return 'https://geo.api.gouv.fr/communes?nom=' + params.term + '&fields=nom,code,centre&format=json&geometry=centre';
//         },
//         dataType: 'json',
//         processResults: function (data) {
//             const results = data.map(city => ({
//                 id: city.code,
//                 text: city.nom
//             }));
//             return {
//                 results
//             };
//         }
//     }
// });