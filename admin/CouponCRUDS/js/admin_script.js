function openModal(action, id = null, name = '', discount_percentage = '', start_date = '', exp_date = '') {
    if (action === 'add') {
        document.getElementById("addCouponModal").style.display = 'block';
    } else if (action === 'edit') {
        document.getElementById("editCouponModal").style.display = 'block';
        document.getElementById("edit_coupon_id").value = id;
        document.getElementById("edit_coupon_name").value = name;
        document.getElementById("edit_coupon_discount_percentage").value = discount_percentage;
        document.getElementById("edit_coupon_start_date").value = start_date;
        document.getElementById("edit_coupon_exp_date").value = exp_date;
    } else if (action === 'delete') {
        document.getElementById("deleteCouponModal").style.display = 'block';
        document.getElementById("delete_coupon_id").value = id;
    }
}

function closeModal(action) {
    if (action === 'add') {
        document.getElementById("addCouponModal").style.display = 'none';
    } else if (action === 'edit') {
        document.getElementById("editCouponModal").style.display = 'none';
    } else if (action === 'delete') {
        document.getElementById("deleteCouponModal").style.display = 'none';
    }
}

window.onclick = function(event) {
    if (event.target === document.getElementById("addCouponModal") || event.target === document.getElementById("editCouponModal") || event.target === document.getElementById("deleteCouponModal")) {
        closeModal('add');
        closeModal('edit');
        closeModal('delete');
    }
}
