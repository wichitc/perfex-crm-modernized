<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>

    let scannerRunning = false;
    async function toggleScanner() {
        "use strict";

        const box = document.getElementById("scanner-wrapper");

        if (scannerRunning) {
            stopScanner();
            box.style.display = "none";
        } else {
            box.style.display = "block";

            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === "videoinput");

            $("#cameraSelect").empty();

            let backCam = null;

            videoDevices.forEach(d => {
                const label = d.label.toLowerCase();
                if (label.includes("back") || label.includes("rear") || label.includes("environment")) {
                    backCam = d.deviceId;
                }
                $("#cameraSelect").append(new Option(d.label || "Camera", d.deviceId));
            });

            if (!backCam && videoDevices.length > 1) {
                backCam = videoDevices[1].id;
            }

            let cameraId = backCam || (videoDevices[1]?.deviceId || videoDevices[0]?.deviceId);

            $("#cameraSelect").val(cameraId);

            startScanner(cameraId);
            $("#cameraSelect").off("change").on("change", function () {
                stopScanner();
                startScanner($(this).val());
            });
        }
    }

    function startScanner(cameraId) {
        "use strict";

        if (scannerRunning) {
            Quagga.stop();
            scannerRunning = false;
        }

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector("#reader"),
                constraints: {
                    deviceId: cameraId,
                    facingMode: "environment",
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    aspectRatio: { ideal: 1.777 } // 16:9
                }
            },
            locator: {
                patchSize: "large",
                halfSample: true
            },
            decoder: {
                readers: [
                    "ean_reader",
                    "ean_8_reader",
                    "upc_reader",
                    "upc_e_reader",
                    "code_128_reader",
                    "code_39_reader"
                ]
            },
            locate: true,
            numOfWorkers: 4,
            // ROI (Region Of Interest)
            region: {
                left: "15%",    
                top: "15%",     
                right: "15%",   
                bottom: "15%"   
            }
        }, function (err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
            scannerRunning = true;
        });

        Quagga.onProcessed(function (result) {
            const drawingCtx = Quagga.canvas.ctx.overlay;
            const drawingCanvas = Quagga.canvas.dom.overlay;

            drawingCtx.clearRect(0, 0, drawingCanvas.width, drawingCanvas.height);

            // Draw search boxes
            if (result && result.boxes) {
                result.boxes
                    .filter(box => box !== result.box)
                    .forEach(box => {
                        Quagga.ImageDebug.drawPath(
                            box,
                            { x: 0, y: 1 },
                            drawingCtx,
                            { color: "rgba(0,255,0,0.5)", lineWidth: 2 }
                        );
                    });
            }

            //Draw the detected barcode
            if (result && result.box) {
                Quagga.ImageDebug.drawPath(
                    result.box,
                    { x: 0, y: 1 },
                    drawingCtx,
                    { color: "red", lineWidth: 3 }
                );
            }

            // Highlight scan lines
            if (result && result.codeResult && result.line) {
                Quagga.ImageDebug.drawPath(
                    result.line,
                    { x: 'x', y: 'y' },
                    drawingCtx,
                    { color: "blue", lineWidth: 3 }
                );
            }
        });

        let lastCode = null;
        let lastCount = 0;
        const requiredCount = 3;  // need to detect this code at least 3 times
        const sameCodeInterval = 100; // ms — if the detection times are close together
        let lastTime = 0;

        Quagga.onDetected(function (result) {
            const code = result.codeResult.code;
            const now = Date.now();

            // 1. Bounding box check — make sure the code isn't too small
            const box = result.box;   // 4 point of bounding box
            const width = Math.hypot(box[1].x - box[0].x, box[1].y - box[0].y);
            const height = Math.hypot(box[2].x - box[1].x, box[2].y - box[1].y);
            const minBoxSize = 100;

            if (width < minBoxSize && height < minBoxSize) {
                // Bounding box too small → ignore
                return;
            }

            // 2. Check the code consecutively
            if (code === lastCode && (now - lastTime) < sameCodeInterval) {
                lastCount++;
            } else {
                lastCode = code;
                lastCount = 1;
            }
            lastTime = now;

            // 3. If enough times detect → trigger
            if (lastCount >= requiredCount) {
                // Scan successful — code processing
                $("#last_code").text(code);
                $("#barcode_filter").val(code);
                $('table.table-table_inventory').DataTable().ajax.reload();
                $('table.table-table_commodity_list').DataTable().ajax.reload();
                playBeep();
                // (Optional) Temporarily lock additional scans for 1s to avoid duplicates
                Quagga.pause();
                setTimeout(() => Quagga.start(), 1000);

                // reset counter
                lastCount = 0;
                lastCode = null;
            }
        });

    }

    function stopScanner() {
        "use strict";

        if (scannerRunning) {
            Quagga.stop();
            scannerRunning = false;
        }
    }

    function playBeep() {
        "use strict";

        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = ctx.createOscillator();
        oscillator.type = "sine";
        oscillator.frequency.value = 800;
        oscillator.connect(ctx.destination);
        oscillator.start();
        oscillator.stop(ctx.currentTime + 0.1);
    }


</script>