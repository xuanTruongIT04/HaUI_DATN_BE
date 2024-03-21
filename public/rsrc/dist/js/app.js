    $(document).ready(function (e) {
        // Upload image ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.upload_file').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image_upload_file').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#form_add').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('admin/ajax/upload/store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    this.reset();
                    alert('Image has been uploaded using jQuery ajax successfully');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });

        // Check all checkbox
        $("input[name='checkAll']").click(function () {
            var status = $(this).prop('checked');
            $('.table-checkall tbody tr td input[type="checkbox"]').prop("checked", status);
        });

    });
