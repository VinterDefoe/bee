//import _ from 'lodash';
import "./scss/style.scss";
import "bootstrap";

// filter reviews
$(function () {
    /// Sort by name
    $('.sort-name').on('click', function () {
        tab($(this));
        var reviews = $('.b-review');
        var reviewsBlock = $('.b-reviews .col-12');
        reviewsBlock.find('.b-review').remove();
        reviews = sortByName(reviews,'b-review-name');
        reviewsBlock.append(reviews);
    });
    // Sort by email
    $('.sort-email').on('click',function () {
        tab($(this));
        var reviews = $('.b-review');
        var reviewsBlock = $('.b-reviews .col-12');
        reviewsBlock.find('.b-review').remove();
        reviews = sortByName(reviews,'b-review-email');
        reviewsBlock.append(reviews);
    });
    // Sort by date
    $('.sort-date').on('click',function () {
        tab($(this));
        var reviews = $('.b-review');
        var reviewsBlock = $('.b-reviews .col-12');
        reviewsBlock.find('.b-review').remove();
        reviews = sortByDate(reviews,'b-review-date');
        reviewsBlock.append(reviews);
    });
    
    function tab(element) {
        element.addClass('btn-danger');
        element.removeClass('btn-dark');
        element.siblings().removeClass('btn-danger');
        element.siblings().addClass('btn-dark');
    }
    
    function sortByName(arr,valueClass) {
        return arr.sort(function (a, b) {
            var a = a.getElementsByClassName(valueClass)[0].textContent.toLowerCase();
            var b = b.getElementsByClassName(valueClass)[0].textContent.toLowerCase();
            if(a < b){return -1;}
            if(a > b){return 1;}
            return 0;
        });
    }

    function sortByDate(arr,valueClass) {
       return arr.sort(function (a, b) {
           var a = a.getElementsByClassName(valueClass)[0].textContent;
           var b = b.getElementsByClassName(valueClass)[0].textContent;
            return new Date(b) - new Date(a);
        });
    }
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
            var imgNode = block.find('.b-review-img');
            if (data.file.val()) {
                getImg(data.file, imgNode);
            }
            $('.b-reviews .col-12').append(block);
        }
    });

    $('.custom-file-input').on('change', function () {
        $(this).next('.custom-file-label').html($(this).val());
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
            format = format.map(function (e) {
                return e = '.' + e + '$';
            });
            return (new RegExp(format.join('|'))).test(img);
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
        ////// file /////
        // if (!validate.isValidImgFormat(data.file.val(), ['jpg', 'gif', 'png'])) {
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
        var date = new Date();
        date = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);

        return reviewBlock
            .find('.b-review-img img')
            .remove()
            .end()
            .find('.b-review-name')
            .text(data.name.val())
            .end()
            .find('.b-review-email')
            .text(data.email.val())
            .end()
            .find('.b-review-date')
            .text(date)
            .end()
            .find('.b-review-review')
            .text(data.review.val())
            .end();
    }

    function getImg(data, element) {
        var file = data[0].files[0];
        var reader = new FileReader();
        reader.addEventListener("load", function () {
            var image = new Image();
            image.addEventListener("load", function () {
                var result = resizeImg(image.width, image.height, 320, 240);
                image.width = result.width;
                image.height = result.height;
                element.append(this);
            });
            image.src = window.URL.createObjectURL(file);
        });
        reader.readAsDataURL(file);
    }

    function resizeImg(srcWidth, srcHeight, maxWidth, maxHeight) {
        if (srcWidth < maxWidth && srcHeight < maxHeight ){
            return {width: srcWidth, height: srcHeight};
        }
        var ratio = [maxWidth / srcWidth, maxHeight / srcHeight];
        ratio = Math.min(ratio[0], ratio[1]);
        return {width: srcWidth * ratio, height: srcHeight * ratio};
    }
});
