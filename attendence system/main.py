import cv2
from tkinter import Tk, Button, Label, StringVar, Entry, Toplevel, messagebox, OptionMenu, Frame, ttk
from deepface import DeepFace
import os
import mysql.connector
import webbrowser

# Set known faces folder
known_faces_folder = "D:/cst-project-sem-6/website/pfp-photos"

# Initialize MySQL connection
try:
    db_connection = mysql.connector.connect(
        host="localhost",
        port=9000,  # Replace with your actual port
        user="admin",
        password="admin123",
        database="bgp_database"
    )
    if db_connection.is_connected():
        print("Connected to MySQL database")
except mysql.connector.Error as err:
    print("Error connecting to MySQL database:", err)
    messagebox.showerror("Connection Error", f"Failed to connect to MySQL: {err}")

def verify_faces(img1_path, img2_path):
    try:
        # Use DeepFace to verify similarity between the images with high performance setting
        result = DeepFace.verify(img1_path=img1_path, img2_path=img2_path, model_name='VGG-Face', distance_metric='cosine')
        return result
    except Exception as e:
        print("Error verifying faces:", e)
        return {"verified": False}

def save_webcam_image():
    try:
        cap = cv2.VideoCapture(0)
        ret, frame = cap.read()
        cap.release()

        if ret:
            # Save the captured frame to a temporary file
            temp_file_path = "webcam_capture.jpg"
            cv2.imwrite(temp_file_path, frame)
            return temp_file_path
        else:
            print("Error capturing image from webcam.")
            return None
    except Exception as e:
        print("Error capturing image from webcam:", e)
        return None

def select_image():
    # Capture an image from the webcam and save it to a temporary file
    temp_file_path = save_webcam_image()
    if temp_file_path:
        # Set the image path as the value of the variable
        image1_path_var.set(temp_file_path)
    else:
        image1_path_var.set(None)  # Set to None if webcam capture failed
        result_label.config(text="Error capturing image from webcam.")

def verify_and_display():
    # Get the paths of the selected images
    img1_path = image1_path_var.get()

    if img1_path:
        # Loop through the images in the known_faces folder
        for filename in os.listdir(known_faces_folder):
            known_img_path = os.path.join(known_faces_folder, filename)

            # Verify similarity between the captured image and each known face
            verification_result = verify_faces(img1_path, known_img_path)

            # Check if the faces match
            if verification_result["verified"]:
                result_label.config(text=f"Attendance Marked: {os.path.splitext(filename)[0]}")

                # Insert data into the table if it exists
                dept = selected_dept.get()
                class_id = class_id_entry.get()
                if dept and class_id:
                    table_name = f"{dept} _class_{class_id}" 
                    cursor = db_connection.cursor()
                    cursor.execute(f"SHOW TABLES LIKE '{table_name}'")
                    result = cursor.fetchone()
                    if result:
                        email = os.path.splitext(filename)[0]  # Assuming filename is the email
                        status_attended = "Attended"

                        # Check if the email already exists in the table
                        cursor.execute(f"SELECT * FROM `{table_name}` WHERE email = %s", (email,))
                        existing_data = cursor.fetchone()
                        if existing_data:
                            messagebox.showwarning("Data Exists", "Your attendance has already been marked.")
                        else:
                            try:
                                cursor.execute(f"INSERT INTO `{table_name}` (email, status_attend) VALUES (%s, %s)",
                                               (email, status_attended))
                                db_connection.commit()
                            except mysql.connector.Error as e:
                                messagebox.showerror("SQL Error", f"Error inserting data: {e}")
                    else:
                        messagebox.showinfo("Table Check", f"The table '{table_name}' does not exist in the database.")
                else:
                    messagebox.showwarning("Input Error", "Please select a department and enter a class ID.")
                break  # Exit the loop if a match is found
        else:
            result_label.config(text="No match found.")
    else:
        result_label.config(text="Please capture an image first.")

def show_table_name():
    dept = selected_dept.get()
    class_id = class_id_entry.get()
    
    if dept and class_id:
        table_name = f"{dept} _class_{class_id}"
        cursor = db_connection.cursor()

        try:
            cursor.execute(f"SHOW TABLES LIKE '{table_name}'")
            result = cursor.fetchone()
            
            if result:
                messagebox.showinfo("Lock Table", f"Locked the '{table_name}'")
                
                # Disable department dropdown and class ID entry if table exists
                dept_menu.config(state="disabled")
                class_id_entry.config(state="disabled")
                
                # Enable other buttons
                capture_btn.config(state="enabled")
                verify_btn.config(state="enabled")
                table_name_btn.config(state="disabled")  # Disable the Lock Table button
            else:
                messagebox.showinfo("Table Lock", f"Can't Lock '{table_name}' because it does not exist in the database.")
        except mysql.connector.Error as e:
            messagebox.showerror("Database Error", f"Error checking table existence: {e}")
    else:
        messagebox.showwarning("Input Error", "Please select a department and enter a class ID.")

def open_smart_track():
    webbrowser.open("http://localhost/")  # Replace URL with your desired web address

def open_admin():
    webbrowser.open("http://localhost/admin.php")  # Replace URL with your desired web address

# Create the main window
root = Tk()
root.iconbitmap("favicon.ico")
root.title("SmartTrack - Attendance System")
root.geometry("400x600")  # Set initial window size
root.configure(bg="#ebebeb")  # Set background color

# Create a frame for department and class ID inputs
input_frame = Frame(root, bg="#ebebeb", bd=0, relief="groove")
input_frame.pack(pady=20)

# Create StringVar object to store the image path
image1_path_var = StringVar()

# Create a dropdown menu for selecting department
dept_options = ["Select Department", "CST", "CFS", "ID", "ELECTRICAL", "MECHATRONICS"]
selected_dept = StringVar()
selected_dept.set(dept_options[0])  # Set default value
dept_label = Label(input_frame, text="Select Department:", bg="#ebebeb", fg="#333", font=("Montserrat", 12, "bold"))
dept_label.grid(row=0, column=0, padx=5, pady=5)
dept_menu = ttk.OptionMenu(input_frame, selected_dept, *dept_options, style="Custom.TMenubutton")
dept_menu.config(width=20)  # Adjust width to match the input box
dept_menu.grid(row=0, column=1, padx=5, pady=5)


# Create an entry field for entering the class ID
class_id_label = Label(input_frame, text="Enter Class ID:", bg="#ebebeb", fg="#333", font=("Montserrat", 12, "bold"))
class_id_label.grid(row=1, column=0, padx=5, pady=5)
class_id_entry = ttk.Entry(input_frame, style="Custom.TEntry", width=20)  # Adjust width as needed
class_id_entry.grid(row=1, column=1, padx=5, pady=5)


# Create a button to show the table name
table_name_btn = ttk.Button(input_frame, text="Lock Table", command=show_table_name, style="Custom.TButton")
table_name_btn.grid(row=2, column=0, columnspan=2, padx=5, pady=10)

# Create a button for capturing an image from the webcam
capture_btn = ttk.Button(input_frame, text="Capture Image", command=select_image, style="Custom.TButton", state="disabled")
capture_btn.grid(row=3, column=0, columnspan=2, padx=5, pady=10)

# Create a button for verifying and displaying the result
verify_btn = ttk.Button(input_frame, text="Verify & Mark", command=verify_and_display, style="Custom.TButton", state="disabled")
verify_btn.grid(row=4, column=0, columnspan=2, padx=5, pady=10)

# Create buttons for opening external URLs
github_btn = ttk.Button(root, text="Open SmartTrack", command=open_smart_track, style="Custom.TButton")
github_btn.pack(pady=5)

google_btn = ttk.Button(root, text="Open Admin Panel", command=open_admin, style="Custom.TButton")
google_btn.pack(pady=5)

# Create a label for displaying the verification result
result_label = Label(root, text="", font=("Arial", 14), bg="#ebebeb")
result_label.pack(pady=20)

# Define the custom style for the buttons
root.style = ttk.Style()
root.style.theme_use("clam")
# Define the custom style for the buttons
# Define the custom style for the buttons
# Define the custom style for the buttons
root.style.configure(
    "Custom.TButton",
    foreground="#ebebebebebeb",
    background="#ff7300",
    font=("Montserrat", 12),
    width=20,
    borderwidth=0,
    relief="ridge",
    padding=10
)

# Define the style for disabled buttons
root.style.map(
    "Custom.TButton",
    foreground=[("pressed", "#000000"), ("active", "#ebebeb"), ("disabled", "#808080")],
    background=[("pressed", "#ff7300"), ("active", "#ff5e00"), ("disabled", "#d9d9d9")],
)


# Run the GUI main loop
root.mainloop()
