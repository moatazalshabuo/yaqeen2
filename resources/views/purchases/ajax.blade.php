<script>
    $(function() {


		$('#bill_id').keyup(function(e){
			var code = (e.keyCode ? e.keyCode : e.which)
			if (code==13) {
				location.replace("{{ route('Purchasesbill','') }}/"+$(this).val())
			}
		})
		// ======================================

		function get_totel(){
		$.ajax({
			url:"{{ route('getItempurbill',$data->id) }}",
			type:"get",
			success:function(e){
				$data = JSON.parse(e)
				$("#total").val(Math.ceil($data['total']))
				$("#sincere").val(parseFloat($data['sincere']))
				$("#Residual").val(parseFloat($data['Residual']))
				$("tbody").html($data['tbody'])
			},error:function(e){
				// console.log(e)
			}
		})
	}
	get_totel()

//=========================================
        $("#mate").change(function() {
            if ($(this).val() != "") {
                $.ajax({
                    url: "{{ route('getoldprice', '') }}/" + $(this).val(),
                    type: "get",
                    success: function(res) {
                        // console.log(res)
                        data = JSON.parse(res)
                        $("#old_price").val(data['price'])
                        $("#old_quantity").val(data['quantity'])
                    }
                })
            }
        })
        //==================================
        function reset() {
            $("#q_error").text("")
            $("#product_error").text("")
            $("#price_error").text("")
            $("#name_err").html("")
            $("#phone_err").html("")
            $(".war").text("")
        }
        // ======================================
        function add_item() {
            $("#addItem .sp").show()
            $("#addItem .text").hide()
            reset();
            $.ajax({
                url: "{{ route('add_puritem') }}",
                type: "POST",
                data: $("#input-item").serialize() + "&id={{ $data->id }}",
                success: function(r) {
                    $("#addItem .sp").hide()
                    $("#addItem .text").show()
                    if (r == 1) {
                        get_totel()
                        alertify.success('تم الاضافة بنجاح');
                        $("#input-item").trigger("reset");
                    } else {
                        Swal.fire(r)
                    }
                },
                error: function(ers) {
                    $("#addItem .sp").hide()
                    $("#addItem .text").show()
                    r = ers.responseJSON;
                    // console.log(r.errors.quantity)
                    $("#q_error").text(r.errors.quantity || r.errors.length + " " + r.errors.width)
                    $("#product_error").text(r.errors.material)
                    $("#price_error").text(r.errors.price)
                }
            })
        }
        //=========================================




        // =========================================

        $("#addItem").click(function() {
            add_item();
        })

        $('#price').keypress(function(e) {
            if (e.which == 13) {
                add_item()
            }
        })
        //========================================
        $("#price").keyup(function() {
            var quan = $("#quantity").val() || $("#length").val() * $("#width").val()
            $("#totel").val(Math.ceil($(this).val() * quan))
        });

        $("#phone_couts,#address,#email").keypress(function(e) {
            if (e.which == 13) {
                saveClient();
            }
        })


        $(document).on('click', ".dele", function() {
            $(this).children('.sp').show()
            $(this).children(".text").hide()
            $(this).attr("disabled", "disabled")
            $.ajax({
                url: "{{ route('deletePurItem', '') }}/" + $(this).attr('id'),
                type: "get",
                success: function(r) {
                    $(this).children('.sp').hide()
                    $(this).children(".text").show()
                    get_totel()
                    if (r == 1) {
                        alertify.success('تم الحذف بنجاح');
                    } else {
                        Swal.fire(r)
                    }
                },
                error: function(r) {
                    $(this).children('.sp').hide()
                    $(this).children(".text").show()
                    console.log(r.responseJSON)
                    $(this).children('.sp').hide()
                    $(this).children(".text").show()
                }
            })
        })


        $(document).on('click', ".edit-item", function() {
            $(this).children('.sp').show()
            $(this).children(".text").hide()
            $(this).attr("disabled", "disabled")
            $.ajax({
                url: "{{ route('editPurItem', '') }}/" + $(this).attr('id'),
                type: "get",
                success: function(r) {
                    $(this).children('.sp').hide()
                    $(this).children(".text").show()
                    $(this).removeAttr("disabled")
                    data = JSON.parse(r)
                    if (data['type'] == 1) {

                        $("#price").val(parseFloat(data['price']))
                        $("#totel").val(parseFloat(data['total']))
                        $("#quantity").val(parseFloat(data['quantity']))
                        $("#mate").val(data['mate']).change()
                        get_totel()
                    } else {
                        Swal.fire(data['massege'])
                    }
                },
                error: function(r) {
                    $(this).removeAttr("disabled")
                    $(this).children('.sp').hide()
                    $(this).children(".text").show()
                    console.log(r.responseJSON)
                }
            })
        })

        function getClient(id = "") {
            $.ajax({
                url: "{{ route('customSelect', '') }}/" + id,
                type: "get",
                success: function(res) {
                    // console.log(res)
                    $("#custom").html(res)
                },
                error: function(re) {
                    // console.log(re.responseJSON)
                }
            })

        }

        client = "{{ $data->custom }}"
        getClient(client)

        function saveClient() {
            $("#save-client").children('.sp').show()
            $("#save-client").children(".text").hide()
            $.ajax({
                url: "{{ route('createCustom') }}",
                type: "post",
                data: $("#form-client-add").serialize(),
                success: function(e) {
                    $("#save-client").children('.sp').hide()
                    $("#save-client").children(".text").show()
                    getClient(e)
                    $("#select2modal").modal("hide")
                    $("#form-client-add").trigger("reset")
                    // location.reload()
                    alertify.success('تم الاضافة بنجاح');
                    reset()
                },
                error: function(e) {
                    re = e.responseJSON
                    $("#save-client").children('.sp').hide()
                    $("#save-client").children(".text").show()
                    $("#name_err").html(re.errors.name)
                    $("#phone_err").html(re.errors.phone)
                }
            })
        }
        $("#save-client").click(function() {
            // console.log($("#form-client").serialize())
            saveClient()
        })


        $("#close-bill").click(function() {

            $(this).attr("disabled", "disabled")
            $.ajax({
                url: "{{ route('purchasesbill_save') }}",
                type: "post",
                data: "_token={{ csrf_token() }}&client=" + $("#custom").val() +
                    "&id={{ $data->id }}",
                success: function(re) {
                    $(this).removeAttr("disabled")
                    res = JSON.parse(re)
                    if (res['id']) {
                        location.replace("{{ route('Purchasesbill', '') }}/" + res['id'])
                    } else {
                        Swal.fire(res['mass'])
                    }
                    // console.log(res)
                },
                error: function(res) {
                    $(this).removeAttr("disabled")
                    error = res.responseJSON
                    // console.log(error)
                    alertify.error('يوجد خطاء اثناء الحفظ');
                    $("#client-err").text(error.errors.client)
                    $("#sincere-err").text(error.errors.sincere)
                }
            })
        })
        // ====================================

        function reset_add_form() {
            $(".error_add").text("")
        }

        function sendMetadata() {
            $("#add-mate").children('.sp').show()
            $("#add-mate").children(".text").hide()
            $.ajax({
                url: "{{ route('rawmaterials.store') }}",
                type: "post",
                data: $('#form-add').serialize(),
                success: function(res) {
                    // console.log(res);
                    $("#add-mate").children('.sp').hide()
                    $("#add-mate").children(".text").show()
                    $('#form-add').trigger("reset");
                    $("#modaldemo1").modal('hide')
                    alertify.success('تم الاضافة بنجاح');
                    getitem();
                },
                error: function(e) {
                    $data = e.responseJSON;
                    $("#add-mate").children('.sp').hide()
                    $("#add-mate").children(".text").show()
                    $("#error_material_name").text($data.errors.material_name)
                    $("#error_hisba_type").text($data.errors.hisba_type)
                    $("#error_quantity").text($data.errors.quantity || $data.errors.length + " " +
                        $data.errors.width)
                    $("#error_price_mate").text($data.errors.price)
                }
            })
        }

        $('#add-mate').click(function() {
            reset_add_form();
            sendMetadata();
            location.reload()
        })
        $("#price_mate").keypress(function(e) {
            if (e.which == 13) {
                reset_add_form();
                sendMetadata();
                location.reload()
            }
        })
        var count_click = 0
        $("#on").click(function(){
            if(count_click == 0)
           $.ajax({
                url:"{{ route('CancelReceive',$data->id) }} ",
                type:"get",
                success:function(res){

                    if(res['stat'] == 1){
                    $(this).toggleClass('on');
                    alertify.success('الفاتورة في حالة غير مستلمة');
                    location.reload()
                }else{
                    Swal.fire(
                        'لم تتم العملية!',
                        'لا يمكن التعديل يرجى الغاء الايصال لهذه الفاتورة اولا',
                        'warning'
                    )
                }
            }
            })
            count_click++;
        })
        $("#off").click(function(){
            if(count_click == 0)
            $.ajax({
                url:"{{ route('ToReceive',$data->id) }}",
                type:"get",
                success:function(res){

                    if(res['stat'] == 1){
                    $(this).toggleClass('on');
                    alertify.success('الفاتورة في حالة مستلمة');
                    location.reload()
                }else{
                    Swal.fire(
                        'لم تتم العملية!',
                        'لا يمكن التعديل يرجى الغاء الايصال لهذه الفاتورة اولا',
                        'warning'
                    )
                }
                }
            })
            count_click++;
        })
    })

</script>
@if (session()->get('err'))
    <script>
        Swal.fire(
            'نجاح العملية!',
            '{{ session()->get('err') }}!',
            'success'
        )
    </script>
@endif
