//import _ from 'lodash';
import './scss/style.scss';
import 'bootstrap';

//////// Add Review Form file input//////
$(function () {
    $('.custom-file-input').on('change', function () {
        $(this).next('.custom-file-label').html($(this).val());
    })

});
// Preview and send
$(function () {
    var reviewBlock = $('.b-review:first').clone();
    var forms = $('.b-reviews-form form');

    forms.submit(function (event) {
        var res = validation(getDataFields());
        if (res === false) {
            event.preventDefault();
        }
    });

    $(".b-preview").click(function () {
        var data = getDataFields();
        var res = validation(data);
        if (res) {
            var block = createReviewBlock(data);
            $('.b-reviews .col-12').append(block);
        }
    });

    var validate = {
        isEmpty: function (value) {
            return (value.length === 0 || !value.trim());
        },
        isEmail: function (email) {
            var pattern = /@/;
            return !!email.match(pattern);
        },
        isValidImgFormat(img, format) {
            return true;
        }
    };

    function validation(data) {
        var valid = true;
        ///// name ////
        if (validate.isEmpty(data.name.val())) {
            valid = validError(data.name, 'Enter name');
        } else {
            validSuccess(data.name);
        }
        ////// email /////
        // if (validate.isEmpty(data.email.val())) {
        //     valid = validError(data.email, 'Enter email');
        // } else {
        //     if (!validate.isEmail(data.email.val())) {
        //         valid = validError(data.email, 'Wrong email');
        //     } else {
        //         validSuccess(data.email);
        //     }
        // }
        // ////// review //////
        // if (validate.isEmpty(data.review.val())) {
        //     valid = validError(data.review, 'Review can not be empty');
        // } else {
        //     validSuccess(data.review);
        // }
        // ////// file /////
        // if (validate.isValidImgFormat(data.file.val(), ['jpg', 'gif', 'png'])) {
        //     valid = validError(data.file, 'Wrong format');
        // } else {
        //     validSuccess(data.file);
        // }

        return valid;
    }

    function validError(element, message) {
        element.addClass('is-invalid');
        element.siblings('.b-error').text(message);
        element.siblings('.b-error').show();
        return false;
    }

    function validSuccess(element) {
        element.removeClass('is-invalid');
        element.addClass('is-valid');
        element.siblings('.b-error').hide();
    }

    function getDataFields() {
        var data = [];
        data.name = forms.find('#name');
        data.email = forms.find('#email');
        data.review = forms.find('#review');
        data.file = forms.find('#file');
        return data;
    }

    function createReviewBlock(data) {
        getImg(data.file);
        return reviewBlock
            .find('.b-review-img img')
                .attr('')
            .end()
            .find('.b-review-name')
                .text(data.name.val())
            .end()
            .find('.b-review-email')
                .text(data.email.val())
            .end()
            .find('.b-review-review')
                .text(data.review.val())
            .end();
    }

    function getImg(data) {
      //  var imgBlock = $(element);
        var render = new FileReader();
        var file = data[0].files[0];
        var url;
        var img = new Image();
        if (file){
            render.onload = function (e) {

                $('#image').attr('src', e.target.result);
            };
            render.readAsDataURL(file);
        }

    }


    function previewFile() {

        window.URL    = window.URL || window.webkitURL;
        var elBrowse  = document.getElementById("browse"),
            elPreview = document.getElementById("preview"),
            useBlob   = false && window.URL; // set to `true` to use Blob instead of Data-URL

        function readImage (file) {
            var reader = new FileReader();

            reader.addEventListener("load", function () {
                var image  = new Image();

                image.addEventListener("load", function () {
                    var imageInfo = file.name +' '+
                        image.width +'×'+
                        image.height +' '+
                        file.type +' '+
                        Math.round(file.size/1024) +'KB';

                    // Show image and info
                    elPreview.appendChild( this );
                    elPreview.insertAdjacentHTML("beforeend", imageInfo +'<br>');

                    if (useBlob) {
                        // Free some memory
                        window.URL.revokeObjectURL(image.src);
                    }
                });
                image.src = useBlob ? window.URL.createObjectURL(file) : reader.result;
            });

            reader.readAsDataURL(file);
        }
    }


});
