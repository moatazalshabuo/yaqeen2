<script>
    $(function() {
        $("#select-user").select2({
            dropdownParent: $('#users_move')
        });

        function moveButton(selector1, selector2) {
            selector1.show()
            selector2.hide()
        }

        function stopButton(selector1, selector2) {
            selector2.show()
            selector1.hide()
        }

        function getData($id) {
            axios.get(`/start-work/get-users/${$id}`).then((res) => {

                user_list = res.data.user_list
                user_select = res.data.user_select
                html = `<option value=''>اختر المستخدم</option>`
                for (const item of user_select) {
                    html += `<option value=${item.id}>${item.name}</option>`
                }
                $("#select-user").html(html)
                html = ``
                for (const item of user_list) {
                    html += `<li class='list-group-item'>
                        ${item.name} - (${item.order})`
                    if (item.status == 0)
                        html += `<span class="badge bg-secondary text-white">غير مستلمة</span>`
                    else if (item.status == 1)
                        html += `<span class="badge bg-primary text-white">في انتظار الاستلام</span>`
                    else if (item.status == 2)
                        html += `<span class="badge bg-warning text-white">قيد العمل</span>`
                    else if (item.status == 3)
                        html += `<span class="badge bg-success text-white">منتهيه</span>`
                    html += `<button data-id='${item.id}' class='float-left btn btn-danger btn-sm delete'>
                            <span class="spinner-border spinner-border-sm sp" style="display: none">
                                    </span>
                                    <span class="text"><i class='fa fa-trash'></i></span>
                            </button>
                            </li>`
                }
                console.log(html)
                $("#list-user").html(html)
            })
        }

        $(".user-model").click(function() {
            getData($(this).data("salesitem"))
            $("#salesitem").val($(this).data('salesitem'))
        })

        $("#save_work").click(function() {
            $(".error").text("")
            moveButton($("#save_work .sp"), $("#save_work .text"))
            axios.post('/start-work/save', $("#form_work").serialize()).then((res) => {
                if (res.data.error == 0) {
                    getData($("#salesitem").val())
                    stopButton($("#save_work .sp"), $("#save_work .text"))
                    $("#form_work").trigger("reset")
                } else {
                    stopButton($("#save_work .sp"), $("#save_work .text"))
                    $("#order_error").text("هذا الترتيب موجود مسبقا");
                }

            }).catch((res) => {
                data = res.response.data.errors
                stopButton($("#save_work .sp"), $("#save_work .text"))
                $("#user_error").text(data.user)
                $("#order_error").text(data.order)
            })
        })

        $(document).on("click", ".delete", function() {
            moveButton($(this).children(".sp"), $(this).children(".text"))
            var id = $(this).data('id');
            axios.get(`/start-work/delete/${id}`).then((res) => {
                stopButton($(this).children(".sp"), $(this).children(".text"))
                getData($("#salesitem").val())
                if (res.data.error = 1) {
                    $("#error-massage").text(res.data.mssg)
                }
            })
        })

        $(".active-work").click(function() {
            var id = $(this).attr('id')
            Swal.fire({
                title: 'هل انت متأكد?',
                text: "تريد تفعيل هذه المهمه!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم بداء'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.replace(`/start-work/active/${id}`)
                }
            })
        })
    })
</script>
