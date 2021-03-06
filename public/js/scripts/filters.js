var $ = require("jquery");

require('../components/multiselect');
require('../components/select');

var selectPatho = $('.filterTypePatho').select();
var multiselectMeridiens = $('.filterMeridiens').multiselect();
var multiselectCaracteristiques = $('.filterCaracteristiques').multiselect();

$('body').on('submit', '.filters__container', function(e) {
    e.preventDefault();
    var selectedPatho = selectPatho.getSelected();
    var selectedMeridiens = multiselectMeridiens.getSelected();
    var selectedCaracteristiques = multiselectCaracteristiques.getSelected();
    var keyword = $('#search-input').val();

    var url = 'getTypesPatho';

    if (selectedPatho) {
        url += '/pathos/' + selectedPatho.id;
    }

    if (selectedMeridiens.length) {
        url += '/meridiens';
        selectedMeridiens.forEach(function(el) {
            url += '/' + el.id;
        });
    }

    if (selectedCaracteristiques.length) {
        url += '/caracteristiques';
        selectedCaracteristiques.forEach(function(el) {
            url += '/' + el.id;
        });
    }

    if (keyword !== undefined && keyword.length > 0) {
        url += '/keyword/' + keyword;
    }

    $.ajax({
        url: url,
        method: 'get'
    }).done(function(data) {
        data = $.parseJSON(data);
        data = data.map(function(el) {
            return parseInt(el.idT);
        });

        $('.category__container').each(function() {
                $(this).attr('data-meridiens', null);
                $(this).attr('data-caracteristiques', null);
                $(this).hide();
                $(this).find('.pathologies__container').hide();
                $(this).find('.category_title__container').removeClass('active');
        });

        $('.category__container').each(function() {
            if(data.indexOf(parseInt($(this).data('id'))) !== -1) {
                var resultMeridiens = selectedMeridiens.map(function(el) {
                    return el.id;
                });
                var resultCaracteristiques = selectedCaracteristiques.map(function(el) {
                    return el.id;
                });

                $(this).fadeIn(400);
                $(this).attr('data-meridiens', resultMeridiens.join());
                $(this).attr('data-caracteristiques', resultCaracteristiques.join());
                $(this).attr('data-keyword', keyword);
            }
        });
    });
    return false;
});