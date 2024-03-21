$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    //UPDATE MULTIFILE IMAGE
    function readURL(input) {
        $(".list-image-upload-multi img").remove();
        if (input.files) {
            for (var i = 0; i < input.files.length; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.list-image-upload-multi').append(`<img src="${e.target.result}" style="margin: 15px 15px 15px 0;width:150px; height: 150px; border: 1px solid #000;" />`);
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    }

    $("#update_multi_thumb").change(function (e) {
        readURL(e.target)
    });

    $(".upload_file").click(function () {
        // Soft delete old images
        $(".notifiExists").text("Thông báo: Đang thêm hình ảnh chính");
    });

    $("#update_multi_thumb").click(function () {
        // Soft delete old images
        $(".list-image-upload-multi").empty();
        $(".notifiLoading").text("Thông báo: Đang thêm hình ảnh phụ với sản phẩm có màu như trên!");
    });

    function showSubImage(listImage) {
        $(".list-image-upload-multi").empty();
        listImage.forEach((imageLink) => {
            const imageContainer = $('<div class="image-container"></div>');
            const img = new Image();
            img.onload = function () {
                const imageElement = $(img);
                imageElement.appendTo(imageContainer);
                imageContainer.appendTo('.list-image-upload-multi');
            };
            img.src = `${imageLink}`;
        });
    }

    function removeImage() {
        $("#image_upload_file").attr("src", "/rsrc/dist/img/credit/product-thumb-default.jpg");
        $(".list-image-upload-multi").empty();
    }

    $("#color-id-prev").change(function () {
        var idColor = $(this).val();
        var currentUrl = window.location.href;
        var parts = currentUrl.split("/");
        var idProduct = parts[parts.length - 1];
        if (isNaN(idProduct)) {
            idProduct = document.getElementById("product-id-prev").value;
        }

        data = {
            idColor: idColor,
            idProduct: idProduct,
        };
        $.ajax({
            url: "/api/image/get-thumb",
            data: data,
            cache: false,
            type: "POST",
            accept: "JSON",
            success: function (d) {
                $("#image_upload_file").attr("src", d.data.main_image);
                $(".notifiExists").text("Thông báo: Đã tồn tại hình ảnh chính!");
                $(".notifiLoading").text("Thông báo: Đã tồn tại hình ảnh phụ với sản phẩm có màu như trên!");
                $(".text-danger").hide();
                showSubImage(d.data.sub_images);
            },
            error: function (error) {
                $(".notifiExists").text("");
                $(".notifiExists").text("Thông báo: Chưa có ảnh chính nào với sản phẩm có màu như trên!");
                $(".notifiLoading").text("Thông báo: Chưa có ảnh phụ nào với sản phẩm có màu như trên")
                removeImage();
            },
        });
    });


});
