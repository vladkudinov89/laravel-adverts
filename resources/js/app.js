
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

$(document).on('click', '.phone-button', function () {
    var button = $(this);
    axios.post(button.data('source')).then(function (response) {
        button.find('.number').html(response.data)
    }).catch(function (error) {
        console.error(error);
    });
});

// $('.region-selector').each(function () {
//     var block = $(this);
//     var selected = block.data('selected');
//     var url = block.data('source');
//
//     var buildSelect = function (parent, items) {
//         var current = items[0];
//         var select = $('<select class="form-control">');
//         var group = $('<div class="form-group">');
//
//         select.append($('<option value=""></option>'));
//         group.append(select);
//         block.append(group);
//
//         axios.get(url, {params: {parent: parent}})
//             .then(function (response) {
//                 response.data.forEach(function (region) {
//                     select.append(
//                         $("<option>")
//                             .attr('name', 'regions[]')
//                             .attr('value', region.id)
//                             .attr('selected', region.id === current)
//                             .text(region.name)
//                     );
//                 });
//                 if (current) {
//                     buildSelect(current, items.slice(1))
//                 }
//             })
//             .catch(function (error) {
//                 console.error(error);
//             });
//     };
//     buildSelect(null, selected);
// });
