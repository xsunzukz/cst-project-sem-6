import cv2

def list_webcam_indexes():
    index = 0
    while True:
        # Try to capture from the webcam at the current index using the CAP_ANY backend
        cap = cv2.VideoCapture(index, cv2.CAP_ANY)
        if not cap.isOpened():
            print("No webcam detected or webcam index out of range.")
            break
        else:
            print(f"Webcam index {index}: Connected")
            cap.release()
        index += 1

# List webcam indexes using CAP_ANY backend (simulate webcam capture using a video file)
list_webcam_indexes()
