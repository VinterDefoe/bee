//import _ from 'lodash';
// import "lodash/lodash";
import "./scss/style.scss";
// import "popper.js/dist/umd/popper";
import "bootstrap/dist/js/bootstrap.bundle";

$(function () {
    var taskBlock = $('.b-task:first').clone();
    var forms = $('.b-task-form form');

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
            var block = createTaskBlock(data);
            var imgNode = block.find('.b-task-img');
            if (data.file.val()) {
                getImg(data.file, imgNode);
            }
            $('.b-preview-block .col-12').append(block);
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
        //// email /////
        if (validate.isEmpty(data.email.val())) {
            valid = validError(data.email, 'Enter email');
        } else {
            if (!validate.isEmail(data.email.val())) {
                valid = validError(data.email, 'Wrong email');
            } else {
                validSuccess(data.email);
            }
        }
        ////// task //////
        if (validate.isEmpty(data.task.val())) {
            valid = validError(data.task, 'Task can not be empty');
        } else {
            validSuccess(data.task);
        }
        //// file /////
        if (!validate.isEmpty(data.file.val())) {
            if (!validate.isValidImgFormat(data.file.val(), ['jpg', 'gif', 'png'])) {
                valid = validError(data.file, 'Wrong format');
            } else {
                validSuccess(data.file);
            }
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
        data.task = forms.find('#task');
        data.file = forms.find('#file');
        return data;
    }

    function createTaskBlock(data) {
        var date = new Date();
        date = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);

        return taskBlock
            .find('.b-task-img img')
            .remove()
            .end()
            .find('.b-task-name')
            .text(data.name.val())
            .end()
            .find('.b-task-email')
            .text(data.email.val())
            .end()
            .find('.b-task-date')
            .text(date)
            .end()
            .find('.b-task-text')
            .text(data.task.val())
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
