<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Real Face Detector</title>
  <script src="https://cdn.tailwindcss.com"></script>
  
</head>
<body class="flex items-center justify-center h-screen bg-gray-900 text-white">
  <div class="p-6 bg-gray-800 rounded-xl shadow-lg text-center">
    <h1 class="text-xl font-bold mb-4">Real Face Detector</h1>
    <video id="video" autoplay muted playsinline class="rounded-xl border border-gray-700 w-80 h-60"></video>
    <p id="status" class="mt-4 text-green-400 font-semibold">Loading...</p>
  </div>


   <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
const video = document.getElementById("video");
const statusEl = document.getElementById("status");

async function startCamera() {
  const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
  video.srcObject = stream;
}

async function init() {
  await faceapi.nets.tinyFaceDetector.loadFromUri("./models");
  await faceapi.nets.faceLandmark68Net.loadFromUri("./models");

  await startCamera();

  video.addEventListener("play", () => {
    const canvas = faceapi.createCanvasFromMedia(video);
    document.body.appendChild(canvas);

    const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.2 });

    let blinkCount = 0;

    setInterval(async () => {
      const result = await faceapi
        .detectSingleFace(video, options)
        .withFaceLandmarks();

      const ctx = canvas.getContext("2d");
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      if (result) {
        const resized = faceapi.resizeResults(result, { width: video.videoWidth, height: video.videoHeight });
        faceapi.draw.drawDetections(canvas, [resized]);
        faceapi.draw.drawFaceLandmarks(canvas, [resized]);

        const landmarks = result.landmarks;
        const leftEye = landmarks.getLeftEye();
        const rightEye = landmarks.getRightEye();

        // Average EAR of both eyes
        const EAR = (calcEAR(leftEye) + calcEAR(rightEye)) / 2.0;
        statusEl.textContent = `EAR: ${EAR.toFixed(3)}`;

        if (EAR < 0.28) {
          blinkCount++;
          if (blinkCount >= 2) {
            statusEl.textContent = "✅ Blink detected — real face!";
            statusEl.className = "mt-4 text-green-400 font-bold";
          }
        } else {
          blinkCount = 0; // reset if eyes are open
        }

      } else {
        statusEl.textContent = "❌ No face found";
        statusEl.className = "mt-4 text-red-400 font-bold";
      }
    }, 200);
  });
}

// Helper: Eye Aspect Ratio
function calcEAR(eye) {
  const vertical1 = euclideanDistance(eye[1], eye[5]);
  const vertical2 = euclideanDistance(eye[2], eye[4]);
  const horizontal = euclideanDistance(eye[0], eye[3]);
  return (vertical1 + vertical2) / (2.0 * horizontal);
}

function euclideanDistance(p1, p2) {
  return Math.hypot(p1.x - p2.x, p1.y - p2.y);
}

init();
</script>







</body>
</html>
