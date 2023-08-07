<script>
    $(function() {
        // $("#global-loader").fadeOut("slow");
        // الدوال اولا تم تاتي العمليات
        // دالة احضار عناصر المواد الخام وعرضها في الصفحة



        function getitem() {
            $.ajax({
                url: "{{ route('getitem-mate') }}",
                type: "get",
                success: function(e) {
                    $('tbody').html(e)
                }
            })
        }

        // دالة ارسال بايانات المادة الخام والتاكد منها
        function sendMetadata() {
            $("#add-mate .sp").show()
            $("#add-mate .text").hide()
            $.ajax({
                url: "{{ route('rawmaterials.store') }}",
                type: "post",
                data: $('#form-add').serialize(),
                success: function(res) {
                    // console.log(res);
                    $("#add-mate .sp").hide()
                    $("#add-mate .text").show()
                    $('#form-add').trigger("reset");
                    $("#modaldemo1").modal('hide')
                    alertify.success('تم الاضافة بنجاح');
                    getitem();
                },
                error: function(e) {
                    $("#add-mate .sp").hide()
                    $("#add-mate .text").show()
                    $data = e.responseJSON;
                    $("#error_material_name").text($data.errors.material_name)
                    $("#error_hisba_type").text($data.errors.hisba_type)
                    $("#error_quantity").text($data.errors.quantity)
                    $("#error_price").text($data.errors.price)
                    $("#error_hiegth").text($data.errors.hiegth)
                    $("#error_width").text($data.errors.width)
                }
            })
        }

        function update_mate() {
            $("#update .sp").show()
            $("#update .text").hide()
            $.ajax({
                url: "{{ route('materialupdate') }}",
                type: "post",
                data: $('#form-edit').serialize(),
                success: function(res) {
                    // console.log(res);
                    $("#update .sp").hide()
                    $("#update .text").show()
                    $('#form-edit').trigger("reset");
                    $("#edit_material").modal('hide')
                    alertify.success('تم التعديل بنجاح');
                    getitem();
                },
                error: function(e) {
                    $data = e.responseJSON;
                    // console.log($data)
                    $("#update .sp").hide()
                    $("#update .text").show()
                    $("#error_material_name_e").text($data.errors.material_name)
                    $("#error_hisba_type_e").text($data.errors.hisba_type)
                    $("#error_quantity_e").text($data.errors.quantity)
                    $("#error_price_e").text($data.errors.price)
                    // $("#error_quantity_e").text($data.errors.quantity)
                    // $("#error_price_e").text($data.errors.price)

                }
            })
        }

        // دالة تهيئة رسائل الاخطاء كلها
        function reset_add_form() {
            $(".error_add").text("")
        }

        // دالة تهيئة رسائل الاخطاء كلها
        function reset_edit_form() {
            $(".error_edit").text("")
        }
        $(".close_add").click(function() {
            reset_add_form();
            $('#form-add').trigger("reset");
        })
        $(".close_edit").click(function() {
            reset_edit_form();
            $('#form-edit').trigger("reset");
        })

        getitem()
        $('#add-mate').click(function() {
            reset_add_form();
            sendMetadata();
        })

        $("#price_meta").keypress(function(e) {
            if (e.which == 13) {
                reset_add_form();
                sendMetadata();
            }
        })
        $("#price_e").keypress(function(e) {
            if (e.which == 13) {
                reset_edit_form();
                update_mate()
            }
        })

        $("#hisba_type,#hisba_type_e").change(function(){
            if($(this).val() == 1){
                $(".hiegth-div, .width-div").show()
                $('#hipa_type1').text("الطول في القطعة الواحدة")
            }else if($(this).val() == 2){
                $(".hiegth-div, .width-div").hide()
                $(".hiegth-div").show()
                $('#hipa_type1').text("الطول في القطعة الواحدة")
            }else{
                $(".hiegth-div, .width-div").hide()
                $(".hiegth-div").show()
                $('#hipa_type1').text("العدد في القطعة ")
            }
        })
        $(document).on("click", ".edit_mate", function() {
            $.ajax({
                url: "{{ route('materialedit', '') }}/" + $(this).attr('id'),
                type: "get",

                success: function(res) {

                    $("#id_e").val((res['id']))
                    $("#material_name_e").val((res['material_name']))
                    $("#hisba_type_e").val((res['material_type'])).change()
                    $("#quantity_e").val(parseFloat(res['quantity']))
                    $("#price_e").val(parseFloat(res['price']))
                    if($("#hisba_type_e").val() == 1){
                        $(".hiegth-div, .width-div").show();
                        $("#hiegth_e").val(parseFloat(res['hiegth']))
                        $("#width_e").val(parseFloat(res['width']))
                        $('#hipa_type2').text("الطول في القطعة الواحدة")
                    }else if($("#hisba_type_e").val() == 2){
                        $(".hiegth-div, .width-div").hide()
                        $("#hiegth_e").val(parseFloat(res['hiegth']))
                        $(".hiegth-div").show()
                        $('#hipa_type2').text("الطول في القطعة الواحدة")
                    }else{
                        $(".hiegth-div, .width-div").hide()
                        $("#hiegth_e").val(parseFloat(res['hiegth']))
                        $(".hiegth-div").show()
                        $('#hipa_type2').text("العدد في القطعة ")
                    }
                }
            })
        })

        //============================================
        $('#update').click(function() {
            reset_edit_form();
            // console.log($('#form-edit').serialize());
            update_mate()
        })

        $('#refresh').click(function() {
            location.reload();
        })
    })
</script>
