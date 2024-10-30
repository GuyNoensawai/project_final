$(document).ready(() => {
    $.ajax({
        method: 'get',
        url: '../user/api/getallproduct.php',
        success: function(response) {
            console.log(response);
            if(response.RespCode == 200) {
                var product = response.Result;
                var html = '';

                for (let i = 0; i < product.length; i++) {
                    html += `<div onclick="openProductDetail(${i})" class="product-item ${product[i].type}" style="background-color: rgb(201, 220, 255); display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                            <img class="product-img" src="../img/${product[i].img}" alt="">
                            <p style="font-size: 0.9vw; padding-left: 6px;">${product[i].name}</p>

                            <p style="font-size: 1.2vw; margin-top: auto; padding-left: 6px;">
                                ${numberWithCommas(product[i].price)} บาท

                                ${product[i].stock > 0 ? 
                                    `` : 
                                    `<button disabled class="btn btn-light btn-add-to-card">สินค้าหมด</button>`
                                }
                            </p>
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

                // Call the function to change cell colors after the menu is populated
                changeCellColor();
            }
        }, 
        error: function(err) {
            console.log(err);
        }
    });
});

function searchsome(elem) {
    var value = $('#'+elem.id).val().toLowerCase();
    console.log(value)

    var html = '';
    for (let i = 0; i < product.length; i++) {
        if(product[i].name.includes(value)  || product[i].type.toLowerCase().includes(value)) {
            html += `<div onclick="openProductDetail(${i})" class="product-item ${product[i].type}" style="background-color: rgb(201, 220, 255); display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                    <img class="product-img" src="../img/${product[i].img}" alt="">
                    <p style="font-size: 0.9vw;">${product[i].name}</p>
                    <p style="font-size: 1.2vw;">
                        ${numberWithCommas(product[i].price)} บาท

                        ${product[i].stock > 0 ? 
                            `` : 
                            `<button disabled class="btn btn-light btn-add-to-card">สินค้าหมด</button>`
                        }
                    </p>
                </div>`;
        }
    }
    if(html == '') {
        $("#productlist").html(`<p>ไม่มีสินค้า</p>`);
    } else {
        $("#productlist").html(html);

        changeCellColor();
    }
}

// Function to change the cell color based on type
function changeCellColor() {
    // Select all cells in the table
    const cells = document.querySelectorAll('.sidebar-menu-filter');

    // Check each cell
    cells.forEach(cell => {
        if (cell.textContent.trim() === 'ปุ๋ย') {
            cell.style.backgroundColor = '#c9f7ff';
        } else if (cell.textContent.trim() === 'เครื่องมือการเกษตร') {
            cell.style.backgroundColor = '#c9dcff';
        } else if (cell.textContent.trim() === 'อาหารสัตว์') {
            cell.style.backgroundColor = '#d1c9ff';
        } else if (cell.textContent.trim() === 'ปั้มน้ำ') {
            cell.style.backgroundColor = '#ffc9f7';
        }
    });

    const productItems = document.querySelectorAll('.product-item');

        productItems.forEach(item => {
            if (item.classList.contains('ปุ๋ย')) {
                item.style.backgroundColor = '#c9f7ff';
            } else if (item.classList.contains('เครื่องมือการเกษตร')) {
                item.style.backgroundColor = '#c9dcff';
            } else if (item.classList.contains('อาหารสัตว์')) {
                item.style.backgroundColor = '#d1c9ff';
            } else if (item.classList.contains('ปั้มน้ำ')) {
                item.style.backgroundColor = '#ffc9f7';
            }
        });
}