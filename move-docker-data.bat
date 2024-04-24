@echo off
color 0A
wsl -l -v
wsl -l
wsl --shutdown
mkdir D:\docker-data
wsl -l -v
wsl --export docker-desktop-data D:\docker-data\dockerdesktop.tar
wsl --unregister docker-desktop-data
wsl -l -v
wsl --import docker-desktop-data D:\docker-data\desktop D:\docker-data\dockerdesktop.tar
wsl -l -v
