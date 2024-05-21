"use strict";
const path = '/';
// datatable
function datatableServerside(array) {
  $('#table-1').DataTable({
    responsive : true,
    processing: true,
    serverSide: true,
    ajax: array['url'],
    columns: array['columns'],
    columnDefs: array['columnDefs'],
    order:  array['order'],
    language: {
      processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
      paginate: {
          next: '<i class="fas fa-angle-right"></i>',
          previous: '<i class="fas fa-angle-left"></i>'
      }
    }
  });
}
$("#table-2").dataTable({
  // show 10
  "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
  "language": {
    "paginate": {
      "next": '<i class="fas fa-angle-right"></i>',
      "previous": '<i class="fas fa-angle-left"></i>'
    }
  },
  "searching": true,

});

function AjaxFunction(array){
  $.ajax({
    url: array.url,
    type: array.type,
    dataType: 'json',
    data: array.data,
    beforeSend: function(){
      $('#form_data button[type=submit]').attr('disabled', true);
    },
    success: function(data){
      if(data.status){
        swal({
          title: "Success",
          text: data.message,
          icon: "success",
          button: "OK",
        }).then(function(){
          location.reload();
        });
      }else{
        swal({
          title: "Error",
          text: data.message,
          icon: "error",
          button: "OK",
        });
      }
    },
    error: function(xhr, status, error){
      swal({
        title: "Error",
        text: error,
        icon: "error",
        button: "OK",
      });
    },
    complete: function(){
      $('#form_data button[type=submit]').attr('disabled', false);
    }
  });
}


var minutes_30 = 30 * 60 * 1000;
//  countdown
function countdown() {
  var now = new Date().getTime();
  var countDownDate = now + minutes_30;
  var distance = countDownDate - now;
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  document.getElementById("countdown").innerHTML = minutes + "m " + seconds + "s ";
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "EXPIRED";
  }
}
// countdown();
var timer2 = "30:01";
var interval = setInterval(function() {
  var timer = timer2.split(':');
  //by parsing integer, I avoid all extra string processing
  var minutes = parseInt(timer[0], 10);
  var seconds = parseInt(timer[1], 10);
  --seconds;
  minutes = (seconds < 0) ? --minutes : minutes;
  if (minutes < 0) clearInterval(interval);
  seconds = (seconds < 0) ? 59 : seconds;
  seconds = (seconds < 10) ? '0' + seconds : seconds;
  //minutes = (minutes < 10) ?  minutes : minutes;
  $('#countdown').html(minutes + ':' + seconds);
  timer2 = minutes + ':' + seconds;
  if (minutes == 0 && seconds == 0) {
    document.getElementById('logout-form').submit();
  }
// if mouse move
  $(document).mousemove(function() {
    timer2 = "30:01";
  });
}, 1000);
//  countdown

$('#myTab3 a[data-toggle="tab"]').on('show.bs.tab', function(e) {
  let target = $(e.target).data('target_seo');

  $('.'+target)
      .addClass('active show')
      .siblings('.tab-pane.active')
      .removeClass('active show')
});
function editor_config_builder(className){
  var editor_config_builder = {
    path_absolute : "/",
    height: 500,
    selector: className,
    relative_urls: false,
    object_resizing : false,
    verify_html: false,
    extended_valid_elements: '*[*]',
    content_css : path + "templates/styles.css",
    remove_redundant_brs : false,
    setup: function(ed) {
      // if full screen
      ed.on('FullscreenStateChanged', function(e) {
        if (ed.plugins.fullscreen.isFullscreen()) {
          $('.main-content').addClass('mce-fullscreen-custom');
        }
        else{
          $('.main-content').removeClass('mce-fullscreen-custom');
        }
      });
      ed.on('keyup', function(event) {
        tinyMCE.triggerSave();
        var id = ed.id
        if ( $('#'+id).val() == '' ) {
          $('#'+id).parent().find('.tox-tinymce').addClass('color-fail');
          $('#'+id).parent().find('.tox-tinymce').removeClass('color-success');
        }else{
          $('#'+id).parent().find('.tox-tinymce').removeClass('color-fail');
          $('#'+id).parent().find('.tox-tinymce').addClass('color-success');
        }
      });
    },
    templates: [
      {
        title: 'Image 1',
        description: 'image 1',
        url: path + 'templates/image-1.html'
      },
      {
        title: 'Image 2',
        description: 'image 2',
        url: path + 'templates/image-2.html'
      },
      {
        title: 'Image 3',
        description: 'image 3',
        url: path + 'templates/image-3.html'
      },
      {
        title: 'Iframe',
        description: 'Iframe',
        url: path + 'templates/iframe.html'
      },
  ],
  style_formats: [
      {title: 'Table row 1', selector: 'tr', classes: 'border-b border-[#E7E3E3] bg-[#f2f2f2]'},
      {title: 'Table row 2', selector: 'tr', classes: 'border-b border-[#E7E3E3] bg-[#ffffff]'},
  ],
    table_class_list: [
      {title: 'None', value: ''},
      {title: 'No Borders', value: 'table_no_borders'},
      {title: 'Red borders', value: 'table_red_borders'},
      {title: 'Blue borders', value: 'table_blue_borders'},
      {title: 'Green borders', value: 'table_green_borders'}
    ],
    image_class_list: [
      { title: 'None', value: 'img-fluid' },
      { title: 'Responsive', value: 'img-responsive' },
      { title: 'Full', value: 'img-full' },
      { title: 'Center', value: 'img-center' },
      { title: 'Left', value: 'img-left' },
      { title: 'Right', value: 'img-right' },
      { title: 'With Caption', value: 'img-fluid figure-img' }
    ],
    plugins: 'accordion advlist template anchor autolink autosave charmap charmap code codesample directionality emoticons fullscreen help image importcss insertdatetime link lists media nonbreaking pagebreak preview quickbars save searchreplace table visualblocks visualchars wordcount',
    menubar: 'file edit format insert view help',
    toolbar: [
      'blocks fontfamily fontsize',
      'bold italic underline strikethrough | forecolor backcolor removeformat | align numlist bullist | link image media table template | pagebreak anchor codesample charmap | code preview fullscreen',
    ],
    toolbar_mode: 'sliding',
    promotion: false,
    branding: false,
    file_picker_callback : function(callback, value, meta) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
  
      var cmsURL = editor_config_builder.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
      if (meta.filetype == 'image') {
        cmsURL = cmsURL + "&type=image";
      }else if(meta.filetype == 'media') {
        cmsURL = cmsURL + "&type=video"; 
      }else {
        cmsURL = cmsURL + "&type=file";
      }
      
      tinyMCE.activeEditor.windowManager.openUrl({
        url : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no",
        onMessage: (api, message) => {
          callback(message.content);
        }
      });
    },
  };
  tinymce.init(editor_config_builder);
}
var lfm = function(id, type) {
  let button = document.getElementById(id);
  if (button) {
    button.addEventListener('click', function () {
      var route_prefix = '/laravel-filemanager';
      var target_input = document.getElementById(button.getAttribute('data-input'));
      var target_preview = document.getElementById(button.getAttribute('data-preview'));

      window.open(route_prefix + '?type=' + type || 'file', 'FileManager', 'width='+screen.availWidth+',height='+screen.availHeight+'');
      window.SetUrl = function (items) {
        var file_path = items.map(function (item) {
          return item.url;
        }).join(',');
        // set the value of the desired input to image url
        var domain = window.location.origin;
        file_path = file_path.replace(domain, '');
        target_input.value = file_path;
        target_input.dispatchEvent(new Event('change'));

        // clear previous preview
        if (type == 'image') {
          target_preview.innerHtml = '';
          // set or change the preview image src
          items.forEach(function (item) {
            target_preview.setAttribute('src', item.thumb_url);
          });
          target_preview.dispatchEvent(new Event('change'));
        }
        if (type == 'video') {
          target_preview.setAttribute('src', file_path);
          target_preview.dispatchEvent(new Event('change'));
        }
        if (type == 'file') {
          target_preview.setAttribute('src', file_path);
          target_preview.dispatchEvent(new Event('change'));
        }
        if (type == 'document') {
          target_preview.setAttribute('src', file_path);
          target_preview.dispatchEvent(new Event('change'));
        }
      };
    });
  }
};

// Select2
if(jQuery().select2) {
  $(".select2").select2();
}
const slugify = str =>
  str
    .toLowerCase()
    .trim()
    .replace(/[^\w\s-]/g, '')
    .replace(/[\s_-]+/g, '-')
    .replace(/^-+|-+$/g, '');

$('[name="name[]"]').each(function(){
  $(this).on('keyup', function() {
    let slug = slugify($(this).val());
    $(this).parent().parent().find('[name="slug[]"]').val(slug);
  });
});

// confirm-delete display none
$('.confirm-delete').css('display', 'none');
$(document).ready(function () {
  $('.confirm-delete').css('display', 'inline-block');
  editor_config_builder('.tinymce_plugins');
  editor_config_builder('.tinymce_plugins_2');
  lfm('lfm', 'image');
  lfm('lfm-xs', 'image');
  lfm('lfm-md', 'image' );
  lfm('lfm-lg', 'image' );
  lfm('lfm-sm', 'image' );
  lfm('lfm-logo', 'image' );
  lfm('lfm-logo-brown', 'image' );
  lfm('lfm-logo-yellow', 'image' );
  lfm('lfm-favicon', 'image' );
  lfm('lfm-seo-image', 'image' );
  lfm('lfm-video', 'video' );
  var errors = $('.is-invalid')
  if (errors.length) {
      $(document).scrollTop(errors.offset().top)
  }
  $(".needs-validation").submit(function(event) {
    var form = $(this);
    var err = false;
    $('.tab-content .tab-pane').each(function() {
        var id = $(this).attr('id');
        $('.nav-pills').find('a[href^="#' + id + '"]').find('small').remove();
    });
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        $('.tab-content .tab-pane:has(.form-control:invalid)').each(function() {
            err = true;
            var id = $(this).attr('id');
            if ($('.nav-pills').find('a[href^="#' + id + '"]').find('small').length == 0) {
              $('.nav-pills').find('a[href^="#' + id + '"]').append('<small class="ml-1 required"><i class="fa fa-times"></i></small>');
            }
        });
        event.stopPropagation();
    }
    $('.tab-content .tab-pane:has(.form-control:valid)').each(function() {
        var id = $(this).attr('id');
        if ($('.nav-pills').find('a[href^="#' + id + '"]').find('small').length == 0) {
          $('.nav-pills').find('a[href^="#' + id + '"]').append('<small class="ml-1 required"><i class="fas fa-check"></i></small>');
        }
    });
    $('[name="overview[]"]').each(function(){
        $(this).parent().find('.tox-tinymce').removeClass('color-fail');
        $(this).parent().find('.tox-tinymce').addClass('color-success');
        // if ($(this).val() == '') {
        //   event.preventDefault();
        //   status_overview = true;
        //   err = true;
        //   $(this).focus();
        //   $(this).parent().find('.tox-tinymce').addClass('color-fail');
        //   $(this).parent().find('.tox-tinymce').removeClass('color-success');
        //   event.stopPropagation();
        // }else{
        //   $(this).parent().find('.tox-tinymce').removeClass('color-fail');
        //   $(this).parent().find('.tox-tinymce').addClass('color-success');
        // }
    });
    $('[name="description[]"]').each(function(){
        $(this).parent().find('.tox-tinymce').removeClass('color-fail');
        $(this).parent().find('.tox-tinymce').addClass('color-success');
        // if ($(this).val() == '') {
        //   event.preventDefault();
        //   status_description = true;
        //   err = true;
        //   $(this).focus();
        //   $(this).parent().find('.tox-tinymce').addClass('color-fail');
        //   $(this).parent().find('.tox-tinymce').removeClass('color-success');
        //   event.stopPropagation();
        // }else{
        //   $(this).parent().find('.tox-tinymce').removeClass('color-fail');
        //   $(this).parent().find('.tox-tinymce').addClass('color-success');
        // }
    });
    if (err) {
      iziToast.error({
        title: 'Warning',
        message: 'Please fill all required fields',
        position: 'topRight'
      });
    }
    form.addClass('was-validated');
    $('.bootstrap-tagsinput').addClass('color-success');
  });




  $('#table-custom').DataTable({
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    // "pageLength": 50,
    "language": {
      "paginate": {
        "next": '<i class="fas fa-angle-right"></i>',
        "previous": '<i class="fas fa-angle-left"></i>'
      }
    },
    'sort': false,
  });
});
$(document).on('click', '.confirm-delete', function(e) {
  e.preventDefault();
  var link = $(this).attr('href');
  swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this file!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
  })
  .then((willDelete) => {
      if (willDelete) {
          swal("Poof! Your file has been deleted!", {
              icon: "success",
          });
          // wait
          setTimeout
          (
              function()
              {
                  window.location.href = link;
              }
              , 1000
          );
      } 
  });
});
function sendOrderToServer(data) {
  var array = [];
  $('tr.s1').each(function() {
      array.push($(this).attr('data-id'));
  });
  $.ajax({
      url: data.route,
      type: "POST",
      data: {
          array: array,
          model: data.model,
          _token: data.token,
      },
      success: function(data) {
          iziToast.success({
              title: 'Success',
              message: 'Update Menu Order Success',
              position: 'topRight'
          });
      },
      error: function(data) {
          console.log(data);
          iziToast.error({
              title: 'Error',
              message: 'Update Menu Order Failed',
              position: 'topRight'
          });
      }
  });
}

function DeleteImage(id,holder,link) {
  // confirmation
  swal({
      title: "Are you sure?",
      buttons: true,
      dangerMode: true,
  }).then((willDelete) => {
      if (willDelete) {
          // delete image
          $('#'+id).val('');
          $('#'+holder).attr('src', link);
      }
  });
}
$('.daterange-cus').daterangepicker({
  locale: {format: 'YYYY-MM-DD'},
  drops: 'down',
  opens: 'right'
});






