# Face and Blink Detection

This repository contains a project for **real-time face detection and blink detection**. It is designed to detect faces in images or video streams and monitor eye blinks, useful for applications like attendance systems, drowsiness detection, or interactive systems.

---

## Features

- Detect faces in images using OpenCV.
- Detect eye blinks in real-time.
- Lightweight and easy to integrate into other projects.
- Works with images and video input.

---

## Files

- `facereal.jpg` - Sample image with a visible face.  
- `noface.jpg` - Sample image with no faces.  
- `facereal2.jpg` - Another sample image with a face.  

These images are used for testing and demonstration.

---

## Requirements

- Python 3.x
- OpenCV (`opencv-python`)
- dlib (for landmark detection)
- imutils (optional, for convenience)

Install dependencies using:

```bash
pip install opencv-python dlib imutils
