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
                        if(product.type ==2){
                            html +=`<div class="col-md-2">
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
                            </div>`}else if(product.type == 0){
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
                                        <input checked="" class="material" id="${face[i].id}-${material[j].id}" data-price="${material[j].price}" data-face='${face[i].id}' name="face_material${face[i].id}[]" value="${material[j].id}" type="checkbox">
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
                if(res.data.success == 1){
                    $("#salesbill-model").modal("hide")
                    Swal.fire(
                        'تمت العملية بنجاح!',
                        'تم اضافة العنصر للفاتورة!',
                        'success'
                        )
                        itemBill()
                }else if(res.data.error == 1){
                    var message = ``;
                    for(var i of res.data[0]){
                        message +=`العنصر ${i.material_name} غير كافي للمنتج- ${i.note} <br/>`;
                    }
                    Swal.fire(
                        'لم تتم العملية!',
                        message,
                        'warning'
                        )
                }
            }).catch(function(res) {
                if(res.response.status == 423){
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
                for ([index, items] of response.data.entries()) {
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
            var tcount = ($(`#count`).val() != undefined) ? $(`#count`).val() : 1;
            for (var i of face) {
                var height = ($(`#height${i.id}`).val() != undefined) ? $(`#height${i.id}`).val() : 0;
                var width = ($(`#width${i.id}`).val() != undefined) ? $(`#width${i.id}`).val() : 0;
                var count = ($(`#count${i.id}`).val() != undefined) ? $(`#count${i.id}`).val() : 0;
                totel += ((height * width) * count) * $(`#price${i.id}`).val()
            }
            $("#price").val(totel * tcount)
        }
        function CountCoust(){
            var totel = 0;
            for (var i of face) {
                var cost = 0;
                for (var j of material){
                    if(i.id == j.face_id){
                        cost += ($(`#${i.id}-${j.id}`).is(":checked"))?$(`#${i.id}-${j.id}`).data("price"):0;
                    }
                }
                for (var x of tool){
                    if(i.id == x.product_faces_id){
                        cost += ($(`#tool-${i.id}-${x.id}`).is(":checked"))?$(`#tool-${i.id}-${x.id}`).data("price"):0;
                    }
                }
                $(`#cout${i.id}`).val(cost)
                totel += cost
            }
            $("#coust").val(totel)
        }
        // CountPrice()
        $(".close-modal").click(function(){
            $("#product").val('').change()
        })
        $(document).on("keyup", ".count-totel", CountPrice)
        $(document).on('change',".material",CountCoust)
    })
</script>
