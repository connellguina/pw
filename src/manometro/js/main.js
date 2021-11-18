$('.collapse-pozo').on('show.bs.collapse', function (e) {
    $(this).find('input').attr('disabled', $(this).attr('id') !== $(e.target).attr('id'));
    $(this).find('textarea').attr('disabled', $(this).attr('id') !== $(e.target).attr('id'));
});

$('.collapse-pozo').on('hidden.bs.collapse', function (e) {
    $(this).find('input').attr('disabled', $(this).attr('id') === $(e.target).attr('id'));
    $(this).find('textarea').attr('disabled', $(this).attr('id') === $(e.target).attr('id'));
});

$('.collapse-medida').on('show.bs.collapse', function (e) {
    $(this).find('input').attr('disabled', $(this).attr('id') !== $(e.target).attr('id'));
    $(this).find('textarea').attr('disabled', $(this).attr('id') !== $(e.target).attr('id'));
});

$('.collapse-medida').on('hidden.bs.collapse', function (e) {
    $(this).find('input').attr('disabled', $(this).attr('id') === $(e.target).attr('id'));
    $(this).find('textarea').attr('disabled', $(this).attr('id') === $(e.target).attr('id'));
});