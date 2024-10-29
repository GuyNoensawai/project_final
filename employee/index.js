//var product = [{
//    id: 1,
//    img: 'https://images.unsplash.com/photo-1616967520023-5d658b3cd0c6?q=80&w=1035&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
//    name: 'Shoe',
//    price: 700,
//    description: 'Shoe Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil soluta voluptas obcaecati molestiae natus deserunt possimus sit nam optio delectus.',
//    type: 'shoe'
//}, {
//    id: 2,
//   img: 'https://images.unsplash.com/photo-1531390979850-32568e0159ce?q=80&w=1031&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
//    name: 'Water',
//    price: 1200,
//    description: 'Water Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil soluta voluptas obcaecati molestiae natus deserunt possimus sit nam optio delectus.',
//    type: 'water'
//}, {
//    id: 3,
//    img: 'https://images.unsplash.com/photo-1608587070000-86389cc7291e?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
//    name: 'Food',
//    price: 900,
//    description: 'Food Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil soluta voluptas obcaecati molestiae natus deserunt possimus sit nam optio delectus.',
//    type: 'food'
//}];


var product;

$(document).ready(() => {

    $.ajax({
        method: 'get',
        url: '../employee/api/getallproduct.php',
        success: function(response) {
            console.log(response)
            if(response.RespCode == 200) {

                product = response.Result;
                var html = '';
                for (let i = 0; i < product.length; i++) {
                    html += `<div onclick="openProductDetail(${i})" class="product-item ${product[i].type}">
                            <img class="product-img" src="../img/${product[i].img}" alt="">
                            <p style="font-size: 1.2vw;">${product[i].name}</p>
                            <p style="font-size: 0.9vw;">${numberWithCommas(product[i].price)} THB</p></a>
                            ${product[i].stock > 0 ? 
                                `` : 
                                `<button disabled class="btn btn-light btn-add-to-card">สินค้าหมด</button>`
                            }
                        </div>`;
                }
                $("#productlist").html(html);

    
                // Create a set to store unique product types
                var uniqueTypes = new Set();
                for (let i = 0; i < product.length; i++) {
                    uniqueTypes.add(product[i].type);
                }

                // Convert the set to an array
                var uniqueTypesArray = Array.from(uniqueTypes);
                var html = '';
                for (let i = 0; i < uniqueTypesArray.length; i++) {
                    html += `<a onclick="searchproduct('${uniqueTypesArray[i]}')" class="sidebar-menu-filter" style="cursor: pointer;">${uniqueTypesArray[i]}</a>`;
                }
                $("#menufilterlist").html(html);
            }
        }, error: function(err) {
            console.log(err);
        }
    })

    
})

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function searchsome(elem) {
    var value = $('#'+elem.id).val().toLowerCase();
    console.log(value)

    var html = '';
    for (let i = 0; i < product.length; i++) {
        if(product[i].name.includes(value)  || product[i].type.toLowerCase().includes(value) || product[i].productid === value) {
            html += `<div onclick="openProductDetail(${i})" class="product-item ${product[i].type}">
                    <img class="product-img" src="../img/${product[i].img}" alt="">
                    <p style="font-size: 1.2vw;">${product[i].name}</p>
                    <p style="font-size: 0.9vw;">${numberWithCommas(product[i].price)} THB</p></a>
                </div>`;
        }
    }
    if(html == '') {
        $("#productlist").html(`<p>ไม่มีสินค้า</p>`);
    } else {
        $("#productlist").html(html);
    }
}

function searchproduct(param) {
    console.log(param) 
    $(".product-item").css('display', 'none')
    if(param == 'all') {
        $(".product-item").css('display', 'block')
    } else {
        $("." + param).css('display', 'block')
    }
}


var productindex = 0;
function openProductDetail(i) {
    productindex = i;
    console.log(productindex)
    if (product[i]) {
        $("#modalDesc").css('display', 'flex')
        $("#md-img").attr('src', '../img/' + product[i].img);
        $("#md-productname").text(product[i].name);
        $("#md-price").text(numberWithCommas(product[i].price) + " THB");
        $("#md-stock").text("คลัง : " + product[i].stock);
        $("#md-description").text(product[i].description)
        $("#button-add").html(`${product[i].stock > 0 ? 
            `<button onclick="addtocart(${i})" class="btn btn-success btn-add-to-card">เพิ่มลงรถเข็น</button>` : 
            `<button disabled class="btn btn-light btn-add-to-card">สินค้าหมด</button>`
        }`);
    } else {
        console.error('Product not found');
    }
}

function cancelModal() {
    $(".modal").css('display', 'none')
}


var cart = [];
var successSound = new Audio('../audio/success.mp3');
var errorSound = new Audio('../audio/error.mp3');
function addtocart() {
    var pass = true;

    for (let i = 0; i < cart.length; i++) {
        if( productindex == cart[i].index ) {
            console.log('found same product')
            cart[i].count++;
            pass = false;
        }
    }

    if(pass) {
        var obj = {
            index: productindex,
            id: product[productindex].id,
            name: product[productindex].name,
            price: product[productindex].price,
            img: product[productindex].img,
            count: 1
        };
        // console.log(obj)
        cart.push(obj)
    }
    console.log(cart)

    Swal.fire({
        icon: 'success',
        title: 'Add ' + product[productindex].name + ' to cart !'
    })

    // เล่นเสียงเมื่อ add to cart สำเร็จ
    successSound.play();
    
    $("#cartcount").css('display','flex').text(cart.length)
    $("#myprice").css('display','block')
}


function openCart() {
    $('#modalCart').css('display', 'flex')
    rendercart();
    renderprice();
}

function openQr() {
    $('#modalQr').css('display', 'flex')
}

function rendercart() {
    if(cart.length > 0) {
        var html = '';
                
        for (let i = 0; i < cart.length; i++) {
            html += `<div class="cartlist-item">
                        <div class="cartlist-left">
                            <img src="../img/${cart[i].img}" alt="">
                            <div class="cartlist-detail">
                                <p style="font-size: 1.5vw">${cart[i].name}</p>
                                <p id="priceproduct${i}" style="font-size: 1.2vw">${(cart[i].price * cart[i].count)} THB</p>
                            </div>
                        </div>

                        <div class="cartlist-right">
                            <p onclick="deinitems('subtract', ${i})" class="btn-con" style="font-size: 1.5vw">-</p>
                            <p id="countitems${i}" class="btn-text" style="font-size: 1.5vw">${cart[i].count}</p>
                            <p onclick="deinitems('add', ${i})" class="btn-con" style="font-size: 1.5vw">+</p>
                        </div>
                    </div>`;
        }
        $("#mycart").html(html)

        
    } else {
        $("#mycart").html(`<p>ไม่มีสินค้าในตะกร้า</p>`)
    }
}

function renderprice() {
    if(cart.length > 0) {
        var html = '';
        var shiping = 0;
        var totalAmount = 0;
        var totalVat = 0;
        var totalShipping = 0;
                
        for (let i = 0; i < cart.length; i++) {
            var amount = cart[i].price * cart[i].count;
            var vat = (amount) * 7 /100;
            var netamount = amount + shiping + vat;

            totalAmount += amount; // Accumulate total amount before VAT and shipping
            totalVat += vat;
            totalShipping = 0;

            html += `<div class="menu-price">
                        <p>ชื่อสินค้า : ${cart[i].name}</p> &nbsp;&nbsp;
                        <p>ค่าจัดส่ง : ${shiping}</p> &nbsp;&nbsp;
                        <p id="pricevat${i}">Vat : ${vat}</p> &nbsp;&nbsp;
                        <p id="pricenetamount${i}">ยอดรวม : ${netamount}</p>
                    </div>`;

        }

        var totalWithVat = totalAmount + totalVat;
        var totalGrand = totalAmount + totalVat + totalShipping;

        $("#myprice").html(html + `<div class="total-amount">
            <br>
            <p style="font-size: 1.2vw">ยอดรวม</p>
            <p>ยอดรวมค่าจัดส่ง : ${totalShipping.toFixed(1)}</p>
            <p>ยอดรวมสินค้า (ไม่รวมค่าจัดส่ง) : ${totalAmount.toFixed(1)} + ${totalVat.toFixed(1)} = ${totalWithVat.toFixed(1)}</p>
            <p>ยอดรวมทั้งหมด : ${totalGrand.toFixed(1)}</p>
        </div>`)

        
    } else {

    }
}

function deinitems(action, i) {

    var shiping = 0;
    var amount = cart[i].price * cart[i].count;
    var vat = (amount) * 7 /100;
    var netamount = amount + shiping + vat;

    if(action == 'subtract') {
        if(cart[i].count > 0) {
            cart[i].count--;
            $("#countitems"+i).text(cart[i].count)
            $("#priceproduct"+i).text(cart[i].price * cart[i].count + " THB")
            $("#pricevat"+i).text("Vat : " + cart[i].price * cart[i].count  * 7 /100)
            $("#pricenetamount"+i).text("ยอดรวม : " + ((cart[i].price * cart[i].count) + ((cart[i].price * cart[i].count)* 7 /100) + 0))


            if(cart[i].count <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure to delete?',
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then((res) => {
                  if(res.isConfirmed) {
                     cart.splice(i, 1) 
                     console.log(cart)
                     rendercart();
                     $("#cartcount").css('display','flex').text(cart.length)
                     renderprice();
                     
                     if(cart.length <= 0) {
                        $("#cartcount").css('display','none')
                        $("#myprice").css('display', 'none');
                     }
                  } else {
                    cart[i].count++;
                    $("#countitems"+i).text(cart[i].count)
                    $("#priceproduct"+i).text(cart[i].price * cart[i].count + " THB")
                    $("#pricevat"+i).text("Vat : " + cart[i].price * cart[i].count  * 7 /100)
                    $("#pricenetamount"+i).text("ยอดรวม : " + ((cart[i].price * cart[i].count) + ((cart[i].price * cart[i].count)* 7 /100) + 0))
                    renderprice();
                  }
                })
            }
        }
    } else if(action == 'add') {
        cart[i].count++;
        $("#countitems"+i).text(cart[i].count)
        $("#priceproduct"+i).text(cart[i].price * cart[i].count + " THB")
        $("#pricevat"+i).text("Vat : " + cart[i].price * cart[i].count  * 7 /100)
        $("#pricenetamount"+i).text("ยอดรวม : " + ((cart[i].price * cart[i].count) + ((cart[i].price * cart[i].count)* 7 /100) + 0))
    }

    renderprice();
}

function buynow() {
    $.ajax ({
        method: 'post',
        url: '../employee/api/buynow.php',
        data: {
            product: cart
        }, success: function(response) {
            console.log(response)
            if(response.RespCode == 200) {

                // เล่นเสียงเมื่อ buy สำเร็จ
                successSound.play();

                Swal.fire ({
                    icon: 'success',
                    title: 'Thank you',
                    html: `<p>ราคาสินค้า : ${response.Amount.Amount}</p>
                        <p>ค่าจัดส่ง : ${response.Amount.Shipping}</p>
                        <p>Vat : ${response.Amount.Vat}</p>
                        <p>ยอดรวม : ${response.Amount.Netamount}</p>`
                }).then((res => {
                    if(res.isConfirmed) {
                        cart = [];
                        cancelModal();
                        $("#cartcount").css('display', 'none')
                    }
                }))
            } else {

                errorSound.play();

                Swal.fire ({
                    icon: 'error',
                    title: 'Something is Went wrong'
                })
            }
        }, error: function(err) {
            console.log(err)
        }
    })

}

function openGraphDay() {
    $('#modalCart').css('display', 'flex')
    rendercart();
    renderprice();
}

function openGraphMouth() {
    $('#modalCart').css('display', 'flex')
    rendercart();
    renderprice();
}

function openGraphTotal() {
    $('#graph-total').css('display', 'flex')
    $('#graph-type').css('display', 'none')
}

function openGraphType() {
    $("#graph-type").css('display', 'flex')
    $('#graph-total').css('display', 'none')
}

function searchorder(elem) {
    var value = $('#' + elem.id).val().toLowerCase();
    console.log(value);

    $('#orderlist tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}

function searchorder(elem) {
    var value = $('#' + elem.id).val().toLowerCase();
    console.log(value);

    var html = '';
    for (let i = 0; i < orders.length; i++) {
        if (orders[i].orderid.toLowerCase().includes(value) || orders[i].username.toLowerCase().includes(value) || orders[i].operation.toLowerCase().includes(value)) {

            // Create an HTML table for the orderlist
            var orderTable = '<table class="table table-light table-bordered table-hover"><tr><th>ชื่อ</th><th>จำนวน</th><th>ราคาต่อชิ้น</th></tr>';
            for (let j = 0; j < orders[i].orderlist.length; j++) {
                var item = orders[i].orderlist[j];
                var price = item.price ? item.price + ' บาท' : '';
                orderTable += '<tr><td>' + item.name + '</td><td>' + item.count + '</td><td>' + price + '</td></tr>';
            }
            orderTable += '</table>';

            html += `<tr>
                        <td>${orders[i].id}</td>
                        <td>${orders[i].orderid}</td>
                        <td>${orderTable}</td>
                        <td>${orders[i].netamount}</td>
                        <td>${orders[i].updated_at}</td>
                        <td>${orders[i].username}</td>
                        <td>${orders[i].address}</td>
                        <td>${orders[i].phone}</td>
                        <td>${orders[i].operation}</td>
                    </tr>`;
        }
    }

    if (html === '') {
        $("#orderlist").html('<tr><td colspan="9">ไม่มีออเดอร์</td></tr>');
    } else {
        $("#orderlist").html(html);
    }

    // เรียกใช้ฟังก์ชันเปลี่ยนสีเซลล์ในตารางสถานะ
    changeCellColor();
}