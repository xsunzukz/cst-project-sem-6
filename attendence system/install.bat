@echo off

rem Create the virtual environment
echo Creating virtual environment...
python -m venv .venv

rem Activate the virtual environment
echo Activating virtual environment...
call .venv\Scripts\activate.bat

rem Install requirements
echo Installing requirements...
pip install -r requirements.txt

echo Setup complete.
echo Run the start file to run the program
pause
