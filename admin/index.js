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
                        <td><img class="img_product" src="../uploads/${orders[i].slip}"></td>
                        <td class="text-nowrap">
                            <center>
                                <a href="edit_operation.php?update_id=${orders[i].id}" class="btn btn-warning">แก้ไข</a>
                                <a href="?delete_id=${orders[i].id}" class="btn btn-danger">ลบ</a>
                            </center>
                        </td>
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
