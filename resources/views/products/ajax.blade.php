<script>
    $(function() {

        $("form").submit(function(e) {
            e.preventDefault();
        });

        $(".m3").hide()
        $("#div-tool").hide()

        function moveButton(selector1, selector2) {
            selector1.show()
            selector2.hide()
        }

        function stopButton(selector1, selector2) {
            selector2.show()
            selector1.hide()
        }

        function getitem() {
            axios.get("{{ route('get-products') }}").then(function(res) {
                var html = ``
                // console.log(res.data.length)
                for (i = 0; i < res.data.length; i++) {

                    var data = res.data[i]
                    html += `<tr>
                            <td>${i + 1}</td>
                            <td>${data.name}</td>
                            <td>`;
                    if (data.type == 1) {
                        html += "بالقطعة";
                    } else if (data.type == 2) {
                        html += " المتر المربع";
                    } else {
                        html += " المتر";
                    }
                    html += `
                            <td>${data.created_by}</td>"
                            <td><button  data-target='.bd-example-modal-lg' data-toggle='modal' class='btn btn-primary manage' data-id='${data.id}'>ادارة</button></td>
                        <td class='d-flex'>

                        <button  data-target='#edit-prodect' data-toggle='modal' class='btn btn-info btn-icon edit-product' id='${data.id}'><i class='mdi mdi-transcribe'></i></button>
                        <form action="{{ route('products.destroy', '') }}/${data.id}" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger text-white"><i class="fa fa-trash"></i></button>
                        </form>
                        </td></tr>`
                }
                $("#myTable").html(html)

            }).catch(function(error) {
                console.log(error)
            })
        }

        // دالة ارسال بايانات المادة الخام والتاكد منها
        function sendProductdata() {

            moveButton($("#add-mate .sp"), $("#add-mate .text"))

            axios.post("{{ route('products.store') }}", $('#form-add').serialize()).then(function(res) {

                stopButton($("#add-mate .sp"), $("#add-mate .text"))

                $('#form-add').trigger("reset");
                $("#modaldemo1").modal('hide')

                alertify.success('تم الاضافة بنجاح');
                getitem();
                GetFacesItem(res.data)
                GetFacesSelect(res.data)
                GetProductMterial(res.data)
                $(".id-product").val(res.data)
                $(".bd-example-modal-lg").modal("show")

            }).catch(function(res) {
                $("#add-mate .sp").hide()
                $("#add-mate .text").show()
                var error = res.response.data.errors
                if (error.name)
                    $("#error_name").text(error.name)
                if (error.type)
                    $("#error_name").text(error.type)
            })

        }

        getitem()

        // دالة تهيئة رسائل الاخطاء كلها
        function reset_add_form() {
            $(".error_add").text("")
        }

        $('#add-mate').click(function() {
            reset_add_form();
            sendProductdata();
        })
        // انتهاء اضافة منتج

        // بداية تعديل منتج

        $(document).on("click", ".edit-product", function() {
            axios.get("{{ route('get-products') }}/" + $(this).attr('id')).then(function(res) {
                data = res.data
                $("#name-edit").val(data.name)
                $("#type-edit").val(data.type)
                $("#id-edit").val(data.id)
            }).catch(function(error) {
                $("#edit-prodect").modal("hide")
                Swal.fire(
                    'لم تتم العملية!',
                    'حصل خطاء ما',
                    'warning'
                )
            })
        })

        // دالة تهيئة رسائل الاخطاء كلها
        function reset_edit_form() {
            $(".error_edit").text("")
        }

        $(".close_add").click(function() {
            reset_add_form();
            $('#form-add').trigger("reset");
        })

        $("#update-product").click(function() {

            reset_edit_form()

            moveButton($("#update-product .sp"), $("#update-product .text"))
            $.ajax({
                url: "{{ route('product.update', '') }}/" + $('#id-edit').val(),
                type: 'post',
                data: $("#form-edit").serialize(),
                success: function(res) {
                    stopButton($("#update-product .sp"), $("#update-product .text"))
                    getitem()
                    $('#form-add').trigger("reset");
                    $("#modaldemo1").modal('hide')
                    alertify.success('تم الاضافة بنجاح');
                },
                error: function(error) {

                    stopButton($("#update-product .sp"), $("#update-product .text"))
                    data = error.responseJSON.errors
                    $("#error-name-edit").text(data.name)
                    $("#error-type-edit").text(data.type)
                }
            })

        })
        // ================= product faces manage =============

        $(document).on('click', '.manage', function() {
            GetFacesItem($(this).data('id'))
            GetFacesSelect($(this).data('id'))
            GetProductMterial($(this).data('id'))
            $(".id-product").val($(this).data('id'))
        })

        function GetFacesSelect(id) {
            axios(`{{ route('face.item', '') }}/${id}`).then(function(res) {
                data = res.data
                html = "<option value=''>اختر الواجهه</option>"
                for (i = 0; i < data.length; i++) {
                    html += `<option value='${data[i].id}'>${data[i].title}</option>`
                }
                $("#faces-item").html(html)
            }).catch(function() {

            })
        }

        function MaterialSelect(id) {
            axios(`{{ route('face.material', '') }}/${id}`).then(function(res) {
                data = res.data
                console.log(data)
                html = ""
                for (i = 0; i < data.length; i++) {
                    html += `<option value='${data[i].id}'>${data[i].material_name}</option>`
                }
                $("#materials-item").html(html)
            }).catch(function() {

            })
        }

        function ToolSelect(id) {
            axios(`{{ route('face.tool', '') }}/${id}`).then(function(res) {
                data = res.data
                console.log(res)
                html = ""
                for (i = 0; i < data.length; i++) {
                    html += `<option value='${data[i].id}'>${data[i].title}</option>`
                }
                $("#tool-item").html(html)
            }).catch(function() {

            })
        }

        function GetFacesItem(id) {
            axios(`{{ route('face.item', '') }}/${id}`).then(function(res) {
                console.log(res)
                data = res.data
                html = ""
                for (i = 0; i < data.length; i++) {
                    coust = parseFloat((data[i].coust_material == null) ? 0 : data[i].coust_material.toFixed(2)) + parseFloat((data[i].coust_tool == null) ? 0 : data[i].coust_tool.toFixed(2))
                    html += `<div class="list-group-item pd-y-20">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6 class="tx-15 mb-2">${data[i].title} </h6>
                                                </div>
                                                <div class="col-md-6 d-flex">
                                                    <div class="form-group">
                                                        <label class="text-muted">التكلفة</label>
                                                        <input type="number" class="form-control cost"  data-id="" value='${coust}'>
                                                    </div>
                                                    <div class="form-group mx-1">
                                                        <label class="text-muted">السعر</label>
                                                        <input type="number" class="form-control price" value='${data[i].price}' data-id="${data[i].id}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button href="#" data-id='${data[i].id}' class="btn btn-outline-danger btn-icon ml-2 delete-face">
                                                        <span class="text"><i class="bx bx-trash"></i></span><span class="spinner-border spinner-border-sm sp"
                                                style="display: none"></span>
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                }
                $("#faces").html(html)
            }).catch(function() {})
        }

        function GetProductMterial(id) {
            axios(`{{ route('product.material', '') }}/${id}`).then(function(res) {
                data = res.data
                html = ""
                for (i = 0; i < data.length; i++) {
                    html += `<div class="list-group-item pd-y-20">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <h6 class="tx-15 mb-2">${data[i].title} - ${data[i].material_name} - كمية الاستهلاك ${data[i].quantity}</h6>
                                                </div>

                                                <div class="col-md-2">
                                                    <button href="#" data-id='${data[i].id}' class="btn btn-outline-danger btn-icon ml-2 delete-material-face">
                                                        <span class="text"><i class="bx bx-trash"></i></span><span class="spinner-border spinner-border-sm sp"
                                                style="display: none"></span>
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                }
                $("#materials").html(html)
            }).catch(function() {})

            axios(`{{ route('product.tool', '') }}/${id}`).then(function(res) {
                data = res.data
                html = ""
                for (i = 0; i < data.length; i++) {
                    html += `<div class="list-group-item pd-y-20">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <h6 class="tx-15 mb-2">${data[i].name}  - السعر للمتر ${data[i].price} - ${data[i].title}</h6>
                                                </div>

                                                <div class="col-md-2">
                                                    <button href="#" data-id='${data[i].id}' class="btn btn-outline-danger btn-icon ml-2 delete-tool-face">
                                                        <span class="text"><i class="bx bx-trash"></i></span><span class="spinner-border spinner-border-sm sp"
                                                style="display: none"></span>
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                }
                $("#tools").html(html)
            }).catch(function() {})
        }

        function InsertFace() {

            moveButton($("#add-face .sp"), $("#add-face .text"))

            $(".face-title-error").text("")

            axios.post("{{ route('face.store') }}",
             $("#form-faces").serialize()
             ).then(function(res) {
                stopButton($("#add-face .sp"), $("#add-face .text"))
                $("#form-faces").trigger("reset")
                GetFacesItem($(".id-product").val())
                GetFacesSelect($(".id-product").val())

            }).catch(function(res) {

                stopButton($("#add-face .sp"), $("#add-face .text"))
                $(".face-title-error").text(res.response.data.errors.title)
            })
        }

        $("#add-face").click(InsertFace)

        $("#title-face").keypress(function(e) {
            if (e.which == 13) {
                InsertFace()
            }
        })

        // ==================== face matirial =======================

        $("#faces-item").change(function() {
            MaterialSelect($(this).val())
            ToolSelect($(this).val())
        })

        function AddMaterialFace() {
            $(".fme").text("")
            const type = $(".checktype:checked").val();
            // console.log(type)
            moveButton($("#add-material-face .sp"), $("#add-material-face .text"))
            if(type == "1"){

            axios.post("{{ route('materialface.add') }}", $("#form-material-face").serialize()).then(function(
                res) {
                stopButton($("#add-material-face .sp"), $("#add-material-face .text"))
                $("#form-material-face").trigger("reset")
                alertify.success("تم الاضافة بنجاح")
                GetProductMterial($(".id-product").val())
                GetFacesItem($(".id-product").val())
                GetFacesSelect($(".id-product").val())
            }).catch(function(res) {

                stopButton($("#add-material-face .sp"), $("#add-material-face .text"))
                $("#fme-face").text(res.response.data.errors.face)
                $("#fme-material").text(res.response.data.errors.material)
                $("#fme-quantity").text(res.response.data.errors.quantity)
            })
        }else{

            axios.post("{{ route('toolface.add') }}",
            $("#form-material-face").serialize())
            .then(function(res) {
                stopButton($("#add-material-face .sp"), $("#add-material-face .text"))
                $("#form-material-face").trigger("reset")
                alertify.success("تم الاضافة بنجاح")
                GetProductMterial($(".id-product").val())
                GetFacesItem($(".id-product").val())
                GetFacesSelect($(".id-product").val())
            }).catch(function(res) {

                stopButton($("#add-material-face .sp"), $("#add-material-face .text"))
                // $("#fme-face").text(res.response.data.errors.face)
                $("#fme-tool").text(res.response.data.errors.tool)
                // $("#fme-quantity").text(res.response.data.errors.quantity)
            })

        }
        }

        $("#add-material-face").click(function() {
            AddMaterialFace()

        })
        $("#quantity-face").keypress(function(e) {
            if (e.which == 13)
                AddMaterialFace()
        });


        // ================= delete face =====================

        $(document).on('click', ".delete-face", function() {
            moveButton($(this).children(".sp"), $(this).children(".text"))
            Swal.fire({
                title: 'هل تريد حذف الواجهه ؟',
                showDenyButton: false,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'delete',
                confirmButtonColor: 'danger',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.get(`{{ route('delete.face', '') }}/${$(this).data('id')}`).then(
                        function(res) {
                            stopButton($(this).children(".sp"), $(this).children(".text"))
                            alertify.success("تم الحذف بنجاح")
                            GetProductMterial($(".id-product").val())
                            GetFacesItem($(".id-product").val())
                            GetFacesSelect($(".id-product").val())
                        }).catch(function(res) {

                    })
                } else {
                    stopButton($(this).children(".sp"), $(this).children(".text"))

                }
            })
        })

        $(document).on('click', ".delete-material-face", function() {
            moveButton($(this).children(".sp"), $(this).children(".text"))
            Swal.fire({
                title: 'هل تريد حذف الواجهه ؟',
                showDenyButton: false,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'delete',
                confirmButtonColor: 'danger',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.get(`{{ route('delete.material_face', '') }}/${$(this).data('id')}`)
                        .then(
                            function(res) {
                                stopButton($(this).children(".sp"), $(this).children(".text"))
                                alertify.success("تم الحذف بنجاح")
                                GetProductMterial($(".id-product").val())
                                GetFacesItem($(".id-product").val())
                            }).catch(function(res) {

                        })
                } else {
                    stopButton($(this).children(".sp"), $(this).children(".text"))

                }
            })
        })

        $(document).on('click', ".delete-tool-face", function() {
            moveButton($(this).children(".sp"), $(this).children(".text"))
            Swal.fire({
                title: 'هل تريد حذف العملية ؟',
                showDenyButton: false,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'delete',
                confirmButtonColor: 'danger',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.get(`{{ route('delete.tool_face', '') }}/${$(this).data('id')}`)
                        .then(
                            function(res) {
                                stopButton($(this).children(".sp"), $(this).children(".text"))
                                alertify.success("تم الحذف بنجاح")
                                GetProductMterial($(".id-product").val())
                                GetFacesItem($(".id-product").val())
                            }).catch(function(res) {

                        })
                } else {
                    stopButton($(this).children(".sp"), $(this).children(".text"))

                }
            })
        })

        $("#save-all").click(function() {
            var check = false
            for (var i = 0; i < $('.price').length; i++) {
                axios.post("{{ route('price.store') }}", {
                    "_token": "{{ csrf_token() }}",
                    "price": $(`.price:eq(${i})`).val(),
                    "id": $(`.price:eq(${i})`).data(`id`)
                }).then(function(res) {
                    if (i == $('.price').length) {
                        $("#manage-model").modal('hide')
                        Swal.fire(
                            ' حفظ !',
                            'تمت العملية بنجاح',
                            'success'
                        )
                    }
                }).catch(function(res) {
                    $("#manage-model").modal('hide')
                    Swal.fire(
                        ' خطاء !',
                        'حصل خطاء ما',
                        'warning'
                    )
                })

            }
        })

        $(".checktype").change(function() {
            if ($(this).val() == 1) {
                $("#div-tool").hide()
                $("#div-material").show()
            } else {
                $("#div-tool").show()
                $("#div-material").hide()
            }
        })

        $("#length,#width").keyup(function(){

            var leng = $("#length").val() != ""?$("#length").val():1;
            var width = $("#width").val() != ""?$("#width").val():1;
            $("#quantity-face").val(leng * width)
        })
    })
</script>
