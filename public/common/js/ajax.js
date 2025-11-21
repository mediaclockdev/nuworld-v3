const mainCheckbox = $("#maincheck");
let checkboxes = $("tbody .form-check-input[id]");
const deleteBtn = $("#deleteBtn");
const csrfToken = $('meta[name="csrf-token"]').attr("content");

function toggleDeleteButton() {
    const anyChecked = checkboxes.is(":checked");
    deleteBtn.toggle(anyChecked);
    mainCheckbox.prop(
        "checked",
        checkboxes.length === checkboxes.filter(":checked").length
    );
}

mainCheckbox.on("change", function () {
    if ($(".form-check-input[id]").length > 1) {
        checkboxes.prop("checked", this.checked);
        toggleDeleteButton();
    } else mainCheckbox.prop("checked", false).prop("disabled", true);
});

// Individual checkbox click event
checkboxes.on("change", toggleDeleteButton);

function changeStatusAjax(url, id) {
    swalConfirm("Are you sure to change status?", "").then(async (result) => {
        if (result.isConfirmed) {
            let response = await ajaxPostCall(url);
            if (response.success) {
                swalNotify("Success!", response.message, "success");
                updateBadgeStatus(id, response.newStatus);
            } else {
                swalNotify("Oops!", response.message, "error");
            }
        }
    });
}

function deleteAjax(url) {
    swalConfirm(
        "Are you sure to delete?",
        "You won't be able to revert this!"
    ).then(async (result) => {
        if (result.isConfirmed) {
            let response = await ajaxPostCall(url);
            if (response.success) {
                swalNotify("Success!", response.message, "success");
                Livewire.dispatch("refreshComponent");
            } else {
                swalNotify("Oops!", response.message, "error");
                Livewire.dispatch("refreshComponent");
            }
        }
    });
}

function deleteMultipleAjax(url) {
    let selectedIds = [];
    checkboxes = $("tbody .form-check-input");
    checkboxes.filter(":checked").each(function () {
        let rowId = $(this).closest("tr").attr("id").replace("row_", "");
        selectedIds.push(rowId);
    });
    swalConfirm(
        "Are you sure to delete?",
        "You won't be able to revert this!"
    ).then(async (result) => {
        if (result.isConfirmed) {
            let response = await ajaxPostCall(url, { ids: selectedIds });
            if (response.success) {
                swalNotify("Success!", response.message, "success");
                Livewire.dispatch("refreshComponent");
                clearCheckbox();
            } else {
                swalNotify("Oops!", response.message, "error");
            }
        }
    });
}

function ajaxPostCall(url, data = {}) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            type: "POST",
            data: { ...data, _token: csrfToken },
            success: function (response) {
                resolve(response);
            },
            error: function (error) {
                swalNotify("Error!", error.responseJSON.message, "error");
            },
        });
    });
}
