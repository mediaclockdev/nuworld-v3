function loadSwalCDN() {
  const bp = window.BASE_PATH || "";
  const rootUrl = bp ? "/" + bp.replace(/^\/|\/$/g, "") + "/" : "/";

  const script = document.createElement("script");
  script.src = rootUrl + "public/common/js/sweetalert2.min.js";
  script.async = true;
  script.onload = initializeSwal;
  document.head.appendChild(script);
}

loadSwalCDN();

let swalSettings;

function initializeSwal() {
  swalSettings = {
    success: { icon: "success", confirmButtonColor: "#3085d6" },
    error: { icon: "error", confirmButtonColor: "#d33" },
    warning: { icon: "warning", confirmButtonColor: "#f1c40f" },
    info: { icon: "info", confirmButtonColor: "#3498db" },
    confirm: {
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#0acf97",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
      cancelButtonText: "Cancel"
    }
  };
}

function swalNotify(title, message, type) {
  const settings = swalSettings[type] || {};
  Swal.fire({ title, text: message, ...settings });
}

function swalConfirm(title, message) {
  return Swal.fire({ title, text: message, ...swalSettings.confirm });
}
