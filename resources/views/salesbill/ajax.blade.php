<script src="{{ URL::asset('assets/js/axois.js') }}"></script>
<script>
    $(function() {
        // $('#salesbill-model').modal({backdrop: 'static', keyboard: false})

        $("#product").select2()
        var face;
        var material;
        $("#product").change(function() {
            if ($(this).val() != "")
                axios.get(`{{ route('product.information', '') }}/${$(this).val()}`).then(function(
                    res) {

                    product = res.data.product
                    face = res.data.face
                    material = res.data.material
                    tool = res.data.tools
                    $("#product_id").val(product.id)
                    var html = "";
                    for (i = 0; i < face.length; i++) {
                        html += ` <div class="row faces_product">
                                <div class="col-md-2">
                                    <h6 id="product-name">${face[i].title}</h6>
                                </div>`;
                        if (product.type == 2) {
                            html += `<div class="col-md-2">
                                <label>الطول</label>
                                <input type="number" class="form-control count-totel" name="height${face[i].id}" data-face='${face[i].id}' id="height${face[i].id}">
                            </div>
                            <div class="col-md-2">
                                <label>العرض</label>
                                <input type="number" name="width${face[i].id}" class="count-totel form-control" id="width${face[i].id}" data-face='${face[i].id}'>
                            </div>
                            <div class="col-md-2">
                                <label>العدد</label>
                                <input type="number" name="count${face[i].id}" class="form-control count-totel" id="count${face[i].id}" data-face='${face[i].id}'>
                            </div>`
                        } else if (product.type == 0) {
                            html += `
                                <div class="col-md-2">
                                <label>الكمية</label>
                                <input type="number" name="quantity${face[i].id}" class="count-totel form-control" id="quantity${face[i].id}" data-face='${face[i].id}'>
                                </div>
                                `
                        }
                        html += `<div class="col-md-2">
                                    <label>التكلفة</label>
                                    <input type="number" class="form-control" id="cout${face[i].id}" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label>السعر</label>
                                    <input type="number" name="price${face[i].id}" class="form-control count-totel" data-face='${face[i].id}' id="price${face[i].id}" value="${face[i].price}" >
                                </div>
                                <div class="col-md-12  mt-3">
                                    <div class="row">`
                        for (j = 0; j < material.length; j++) {
                            if (material[j].face_id == face[i].id)
                                html += `<div class="col-md-3 mg-t-20 mg-lg-t-0">
                                    <label class="ckbox">
                                        <input checked="" class="material" id="${face[i].id}-${material[j].id}" data-price="${material[j].price}" data-quantity="${material[j].quantity}"  data-face='${face[i].id}' name="face_material${face[i].id}[]" value="${material[j].id}" type="checkbox">
                                        <span>${material[j].material_name}</span>
                                    </label>
                                </div>`
                        }
                        for (x = 0; x < tool.length; x++) {
                            if (tool[x].product_faces_id == face[i].id)
                                html += `<div class="col-md-3 mg-t-20 mg-lg-t-0">
                                    <label class="ckbox">
                                        <input checked="" class="material" id="tool-${face[i].id}-${tool[x].id}" data-price="${tool[x].price}" data-face='${face[i].id}' name="face_tool${face[i].id}[]" value="${tool[x].id}" type="checkbox">
                                        <span>${tool[x].title}</span>
                                    </label>
                                </div>`
                        }
                        html += `
                        </div>
                    </div>
                </div>
                <hr>
                    `
                    }
                    $("#content-form").html(html)
                    $("#salesbill-model").modal('show')
                    CountPrice()
                    CountCoust()
                }).catch(function(res) {
                    console.log(res)
                })
        })

        $("#store-sales").click(function() {
            axios.post(`{{ route('store.sales') }}`, $("#form-product").serialize()).then(function(
                res) {
                console.log(res)
                if (res.data.success == 1) {
                    $("#salesbill-model").modal("hide")
                    Swal.fire(
                        'تمت العملية بنجاح!',
                        'تم اضافة العنصر للفاتورة!',
                        'success'
                    )
                    itemBill()
                } else if (res.data.error == 1) {
                    var message = ``;
                    for (var i of res.data[0]) {
                        message += `العنصر ${i.material_name} غير كافي للمنتج- ${i.note} <br/>`;
                    }
                    Swal.fire(
                        'لم تتم العملية!',
                        message,
                        'warning'
                    )
                }
            }).catch(function(res) {
                if (res.response.status == 423) {
                    Swal.fire(
                        'لم تتم العملية!',
                        res.response.data.error,
                        'warning'
                    )
                }
            })
        })

        function itemBill() {
            axios.get("{{ route('getItembill', $data->id) }}").then((response) => {
                var html = ``;
                for ([index, items] of response.data.salesitem.entries()) {
                    html += `
                    <tr>
                        <td>${index+1}</td>
                        <td>${items.name}</td>
                        <td>${items.descripe}</td>
                        <td>${items.quantity}</td>
                        <td>${items.descont}</td>
                        <td>${items.totel}</td>
                        <td>-</td>
                        <td>${items.created_at}</td>
                        <td><button class="btn btn-danger btn-sm delet-items" id="${items.id}"><i class="fa fa-trash"></i></button></td>
                    </tr>
                    `
                }
                $("#tbody").html(html)
                $("#totel").val(parseFloat(response.data.salesbill['totel']))
                $("#sincere").val(parseFloat(response.data.salesbill['sincere']))
                $("#Residual").val(parseFloat(response.data.salesbill['Residual']))
            })
        }
        itemBill()

        $(document).on("click", ".delet-items", function() {
            axios.get("{{ route('delete-item', '') }}/" + $(this).attr("id")).then((response) => {
                console.log(response)
                itemBill()
            })
        })

        function CountPrice() {
            var totel = 0;
            var coust = 0;
            var tcount = ($(`#count`).val() != undefined) ? $(`#count`).val() : 1;
            for (var i of face) {
                var height = ($(`#height${i.id}`).val() != undefined) ? $(`#height${i.id}`).val() : 0;
                var width = ($(`#width${i.id}`).val() != undefined) ? $(`#width${i.id}`).val() : 0;
                var count = ($(`#count${i.id}`).val() != undefined) ? $(`#count${i.id}`).val() : 0;
                totel += ((height * width) * count) * $(`#price${i.id}`).val()
                coust += ((height * width) * count) * $(`#cout${i.id}`).val()
            }
            $("#price").val(totel * tcount)
            $("#coust").val(coust * tcount)
        }

        function CountCoust() {
            var totel = 0;
            for (var i of face) {
                var cost = 0;
                for (var j of material) {
                    if (i.id == j.face_id) {
                        cost += ($(`#${i.id}-${j.id}`).is(":checked")) ?
                            $(`#${i.id}-${j.id}`).data("price") * $(`#${i.id}-${j.id}`).data('quantity') :
                            0;
                    }
                }
                for (var x of tool) {
                    if (i.id == x.product_faces_id) {
                        cost += ($(`#tool-${i.id}-${x.id}`).is(":checked")) ?
                            $(`#tool-${i.id}-${x.id}`).data(
                                "price") : 0;
                    }
                }
                $(`#cout${i.id}`).val(cost)
                totel += cost
            }
            $("#coust").val(totel)
        }
        // CountPrice()
        $(".close-modal").click(function() {
            $("#product").val('').change()
        })
        $(document).on("keyup", ".count-totel", CountPrice)
        $(document).on('change', ".material", function() {
            CountCoust()
            CountPrice()
        })



        function getClient(id = "") {
            $.ajax({
                url: "{{ route('clientSelect', '') }}/" + id,
                type: "get",
                success: function(res) {
                    // console.log(res)
                    $("#client").html(res)
                },
                error: function(re) {
                    console.log(re.responseJSON)
                }
            })
        }

        client = {{ $data->client }}
        getClient(client)

        function add_client() {
            $("#save-client").children('.sp').show()
            $("#save-client").children(".text").hide()
            $.ajax({
                url: "{{ route('createClient') }}",
                type: "post",
                data: $("#form-client-add").serialize(),
                success: function(e) {
                    $("#save-client").children('.sp').hide()
                    $("#save-client").children(".text").show()
                    getClient(e)
                    $("#select2modal").modal("hide")
                    $("#form-client-add").trigger("reset")
                    alertify.success('تم الاضافة بنجاح');
                    rest()
                },
                error: function(e) {
                    $("#save-client").children('.sp').hide()
                    $("#save-client").children(".text").show()
                    re = e.responseJSON
                    console.log(re)
                    $("#name_err").html(re.errors.name)
                    $("#phone_err").html(re.errors.phone)
                }
            })
        }
        $("#save-client").click(function() {
            add_client();
        })
        $("#phone,#address,#email").keypress(function(e) {
            if (e.which == 13) {
                add_client();
            }
        })

        /*
        ####################### close bill ####################3#
        */
        $("#close-bill").click(function() {
            $("#client-err").text("")
            $("#sincere-err").text("")
            $.ajax({
                url: "{{ route('salesbill_save') }}",
                type: "post",
                data: "_token={{ csrf_token() }}&client=" + $("#client").val() + "&sincere=" +
                    $("#sincere").val() + "&id={{ $data->id }}",
                success: function(re) {
                    res = JSON.parse(re)
                    if (res['id']) {
                        location.replace("{{ route('salesbill', '') }}/" + res['id'])
                    } else {
                        Swal.fire(res['mass'])
                    }
                    // console.log(res)
                },
                error: function(res) {
                    error = res.responseJSON
                    // console.log(error)
                    alertify.error('يوجد خطاء اثناء الحفظ');
                    $("#client-err").text(error.errors.client)
                    $("#sincere-err").text(error.errors.sincere)
                }
            })
        })

        var count_click = 0
        $("#on").click(function() {
            if (count_click == 0)
                $.ajax({
                    url: "{{ route('CancelReceiveSales', $data->id) }} ",
                    type: "get",
                    success: function(res) {

                        if (res['stat'] == 1) {
                            $(this).toggleClass('on');
                            alertify.success('الفاتورة في حالة غير مستلمة');
                            location.reload()
                        } else {
                            Swal.fire(
                                'لم تتم العملية!',
                                'لا يمكن التعديل يرجى الغاء  الاصناف الفاتورة اولا',
                                'warning'
                            )
                        }
                    }
                })
            count_click++;
        })
        $("#off").click(function() {
            if (count_click == 0)
                $.ajax({
                    url: "{{ route('ToReceiveSales', $data->id) }}",
                    type: "get",
                    success: function(res) {

                        if (res['stat'] == 1) {
                            $(this).toggleClass('on');
                            alertify.success('الفاتورة في حالة cnc');
                            location.reload()
                        } else {
                            Swal.fire(
                                'لم تتم العملية!',
                                'لا يمكن التعديل يرجى الغاء  الاصناف الفاتورة اولا',
                                'warning'
                            )
                        }
                    }
                })
            count_click++;
        })

        $("#check-m-3").change(function() {
            if ($(this).is(":checked")) {
                $(".wl-cnc").show()
            } else {
                $(".wl-cnc").hide()
            }
        })

        $(".w-cnc").keyup(function() {
            var height = ($(`#h-cnc`).val() != undefined) ? $(`#h-cnc`).val() : 1;
            var width = ($(`#w-cnc`).val() != undefined) ? $(`#w-cnc`).val() : 1;
            $("#quantity_cnc").val(height * width)
        })

        $("#addItem").click(function() {
            $(".error").text("")
            axios.post(`{{ route('add_item') }}`, $("#input-item").serialize()).then(function(res) {


                    Swal.fire(
                        'تمت العملية بنجاح!',
                        'تم اضافة العنصر للفاتورة!',
                        'success'
                    )
                    itemBill()
                    $("#input-item").trigger("reset")
            }).catch(function(res) {
                console.log(res)
                data = res.response.data.errors

                $("#cnc-tools-error").text(data.cnc_tools)
                $("#quantity_error").text(data.quantity)
            })
        })

        $("#cnc-tools").change(function(){
            axios.get(`{{route('cnc.tool.data','')}}/${$(this).val()}`).then(function(res){
                $("#price_cnc").val(res.data.price)
            })
        })
        $("#quantity_cnc").keyup(function(){
            $("#price_totel").val($(this).val() * $("#price_cnc").val())
        })

        $("#start-work").click(function(){
            axios.get(`{{ route('salesbill.check','') }}/${$('#salesbill').val()}`).then((res)=>{
                if(res.data == 2){
                    location.replace(`{{route('startwork.index','')}}/${$('#salesbill').val()}`)
                }else{
                    Swal.fire("يجب اغلاق الفاتورة اولا ");
                }
            })
        })
    })

</script>
