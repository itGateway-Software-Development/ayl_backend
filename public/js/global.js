"use strict";

//sweetalert warning message
let warning_alert = (text) => {
    Swal.fire({
        title: text,
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "OK",
        customClass: {
            confirmButton: "d-none",
            cancelButton: "btn btn-outline-primary waves-effect",
        },
    });
};

//Toast message
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

//Toast message - success
let toast_success = (text) => {
    Toast.fire({
        icon: "success",
        title: text,
    });
};

//Toast message - error
let toast_error = (text) => {
    Toast.fire({
        icon: "error",
        title: text,
    });
};

//Confirm Message
let ask_confirm = (title = "Are you sure?", btn = "Yes, save it!", deny = false ) => {
    return Swal.fire({
        title: title,
        icon: "question",
        showDenyButton: deny,
        showCancelButton: true,
        confirmButtonText: btn,
        denyButtonText: `Cancel Order`,
        cancelButtonText: "Close",
        customClass: {
            confirmButton: "btn btn-primary me-3 waves-effect waves-light",
            cancelButton: "btn btn-outline-secondary waves-effect",
            denyButton: "btn btn-outline-danger waves-effect me-3",
        },
        buttonsStyling: false,
        backdrop: true,
        allowOutsideClick: () => !Swal.isLoading(),
    });
};

//Delete Message
let ask_delete = (title = "Are you sure to delete?", btn = "Yes, delete it!") => {
    return Swal.fire({
        title: title,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: btn,
        customClass: {
            confirmButton: "btn btn-primary me-3 waves-effect waves-light",
            cancelButton: "btn btn-outline-secondary waves-effect",
        },
        buttonsStyling: false,
        backdrop: true,
        allowOutsideClick: () => !Swal.isLoading(),
    });
};



