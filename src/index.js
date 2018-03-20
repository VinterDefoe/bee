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
        var name = data.name.val();
        console.log(data.name.val());
        var res = validation(getDataFields());
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
        if (validate.isEmpty(data.email.val())) {
            valid = validError(data.email, 'Enter email');
        } else {
            if (!validate.isEmail(data.email.val())) {
                valid = validError(data.email, 'Wrong email');
            } else {
                validSuccess(data.email);
            }
        }
        ////// review //////
        if (validate.isEmpty(data.review.val())) {
            valid = validError(data.review, 'Review can not be empty');
        } else {
            validSuccess(data.review);
        }
        ////// file /////
        if (validate.isValidImgFormat(data.file.val(), ['jpg', 'gif', 'png'])) {
            valid = validError(data.file, 'Wrong format');
        } else {
            validSuccess(data.file);
        }

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
        return reviewBlock
            .find('.b-review-name')
            .text(data.name.val())
            .end()
            .find('.b-review-email')
            .text('')
            .end();
    }
});
