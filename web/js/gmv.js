$('.as-form').on('click',function(){
    var $form = $('<form/>').hide();

    //form options
    $form.attr({
        'action' : $(this).attr('href'),
        'method':'post'
    })

    //adding the _method hidden field
    $form.append($('<input/>',{
        type:'hidden',
        name:'_method'
    }).val($(this).data('method')));
    //add form to parent node
    $(this).parent().append($form);
    $form.submit();
    return false;
});


$(document).ready(function(){
    $('.dropdown-submenu a.test').on("click", function(e){
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
});


toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}


