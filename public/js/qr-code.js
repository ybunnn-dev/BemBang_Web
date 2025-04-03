let qr_main;

document.addEventListener("DOMContentLoaded", function () {
    // Function to start scanning
    function startqr_main() {
        const qrReaderDiv = document.getElementById('qr-reader-main');

        if (!qr_main) {
            qr_main = new Html5Qrcode(qrReaderDiv.id);

            qr_main.start(
                { facingMode: "environment" },
                { fps: 20, qrbox: 250 },
                (decodedText) => {
                    qr_main.stop();
                },
                (errorMessage) => {
                    console.warn("QR Scan Error:", errorMessage);
                }
            );
        }
    }

    // Function to stop scanning
    function stopqr_main() {
        if (qr_main) {
            qr_main.stop().then(() => {
                qr_main.clear();
                qr_main = null;
            });
        }
    }

    // 🔹 Listen for when the modal opens and start the QR scanner
    document.getElementById("qr-modal").addEventListener("shown.bs.modal", function () {
        startqr_main();
    });

    // 🔹 Listen for when the modal closes and stop the QR scanner
    document.getElementById("qr-modal").addEventListener("hidden.bs.modal", function () {
        stopqr_main();
    });
});
