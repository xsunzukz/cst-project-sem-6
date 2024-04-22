
# Welcome to CST project of 6th sem
1. Clone the project repository to your local machine using Git:
   ```bash
   git clone https://github.com/xsunzukz/cst-project-sem-6.git
# HOW TO INSTALL DOCKER CONTAINER ?
**Step 1: Enable Hyper-V and WSL (Windows Subsystem for Linux)**

1. Open the search bar and type "Turn Windows features on and off".
2. Check the boxes next to "Hyper-V" and "WSL" or "Windows Subsystem for Linux".
3. Restart your computer to apply the changes.

**Step 2: Download and Install Docker Desktop**

1. Go to [Download Docker Desktop](https://www.docker.com/products/docker-desktop/).
2. Create an account if you don't have one already.
3. Download the Docker Desktop installer.
4. Run the installer with administrator privileges.
5. During installation, make sure to select the option to use WSL instead of Hyper-V (if prompted).
6. Wait for the installation to complete, which may take some time.
7. After installing Docker Desktop, restart your computer.
# HOW TO MOVE DOCKER FROM C:/  TO D:/ (GUIDE) (RECOMANDED)
 Run this script: move-docker-data.bat

 # HOW TO SWITCH TO DOCKER FROM XAMPP ?
 **Before Starting:**

- Ensure that XAMPP is closed and not running.
- Download the project files from the provided link and create a new folder to paste them.
- Confirm that you are not inside the website folder and can see both the `docker-compose.yml` file and the `Dockerfile`.
- Make sure Docker is installed and running on your system.

**Installation Steps:**

1. Open CMD and navigate to the folder where you've placed the project files.
2. Run the command 
```bash
docker-compose up
```
   - This command will download all the necessary files and start the container.
   - Access phpMyAdmin by visiting `localhost:8001` in your web browser.
   - Initially, use "admin" for both the ID and password. If it doesn't work, keep trying and monitor the console.
   - After a while, you'll see a message in the console indicating that the `bgp_database` has been created.
   - Once the database restarts, you'll be able to access phpMyAdmin properly.
3. Go to the `bgp_database` and import the SQL file provided.
4. Visit `localhost:80` in your browser to access the project.
5. For future use, simply open Docker Desktop and run the containers. Everything should work seamlessly.

These steps ensure a smooth transition from XAMPP to Docker, providing a more reliable and scalable environment for your project.
