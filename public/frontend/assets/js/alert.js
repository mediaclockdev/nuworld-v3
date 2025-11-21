function loadiziToast() {
    var script = document.createElement("script");
    script.setAttribute(
        "src",
        "https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"
    );
    script.async = true;
    script.onload = initializeIziToast; // Initialize iziToast settings after loading
    document.head.appendChild(script);

    // Load iziToast CSS
    var link = document.createElement("link");
    link.setAttribute("rel", "stylesheet");
    link.setAttribute(
        "href",
        "https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css"
    );
    document.head.appendChild(link);
}

// Load iziToast
loadiziToast();

let iziToastSettings;

const iziToastDefaultSettings = {
    theme: "dark",
    // position: "topRight",
    position: "center",
    progressBarColor: "#28a745",
    backgroundColor: "#28a745",
    messageColor: "#ffffff",
    titleColor: "#ffffff",
    timeout: 3000,
};

function initializeIziToast() {
    iziToastSettings = {
        success: { ...iziToastDefaultSettings, icon: "ri-check-line" },
        error: {
            ...iziToastDefaultSettings,
            icon: "ri-close-circle-line",
            progressBarColor: "#dc3545",
            backgroundColor: "#dc3545",
        },
        warning: {
            ...iziToastDefaultSettings,
            icon: "ri-error-warning-line",
            progressBarColor: "#ffc107",
            backgroundColor: "#ffc107",
        },
        info: {
            ...iziToastDefaultSettings,
            icon: "ri-information-line",
            progressBarColor: "#17a2b8",
            backgroundColor: "#17a2b8",
        },
        confirm: {
            theme: "dark",
            icon: "ri-error-warning-line",
            position: "center",
            progressBarColor: "#0acf97",
            backgroundColor: "#333",
            messageColor: "#ffffff",
            titleColor: "#ffffff",
            buttons: [
                [
                    "<button>Yes</button>",
                    (instance, toast) =>
                        instance.hide(
                            { transitionOut: "fadeOut" },
                            toast,
                            "confirm"
                        ),
                    true,
                ],
                [
                    "<button>Cancel</button>",
                    (instance, toast) =>
                        instance.hide(
                            { transitionOut: "fadeOut" },
                            toast,
                            "cancel"
                        ),
                ],
            ],
        },
    };
}

function iziNotify(title, message, type) {
    const settings = iziToastSettings[type] || {};
    iziToast.show({
        title: title,
        message: message,
        ...settings,
    });
}

function iziConfirm(title, message) {
    return new Promise((resolve) => {
        const settings = iziToastSettings.confirm;
        iziToast.question({
            title: title,
            message: message,
            ...settings,
            onClosing: function (instance, toast, closedBy) {
                resolve(closedBy === "confirm");
            },
        });
    });
}
