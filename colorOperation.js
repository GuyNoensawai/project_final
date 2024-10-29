function changeCellColor() {
    // เลือกทุกเซลล์ในตาราง
    const cells = document.querySelectorAll('table td');

    // ตรวจสอบแต่ละเซลล์
    cells.forEach(cell => {
        if (cell.textContent.trim() === 'จัดส่งสําเร็จ') {
            cell.style.backgroundColor = 'green';
        } else if (cell.textContent.trim() === 'รอชำระเงิน') {
            cell.style.backgroundColor = 'red';
        } else if (cell.textContent.trim() === 'รอตรวจสอบ') {
            cell.style.backgroundColor = 'orange';
        } else if (cell.textContent.trim() === 'กำลังจัดส่ง') {
            cell.style.backgroundColor = 'greenyellow';
        }
    });
}

// เรียกใช้ฟังก์ชันเมื่อโหลดหน้าเสร็จแล้ว
window.onload = changeCellColor;