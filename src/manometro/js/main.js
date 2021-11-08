$('.collapse-pozo').on('show.bs.collapse', function (e) {
    $(this).find('input').attr('disabled', $(this).attr('id') !== $(e.target).attr('id'));
    $(this).find('textarea').attr('disabled', $(this).attr('id') !== $(e.target).attr('id'));
})