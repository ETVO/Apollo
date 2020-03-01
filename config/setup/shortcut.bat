@echo off 
@echo. 
@echo.
@echo.

::Set the application-specific string vars 
SET AppDescription=Apolo
SET IconName=icon.ico
SET Shortcut_Name=Apolo.url
SET URL_PATH=localhost/apolo

::Set the common string vars 
SET WORKING_PATH=%~dp0
SET ICONDEST=c:\xampp\htdocs\%AppDescription%
SET LinkPath=%userprofile%\Desktop\%Shortcut_Name%

@echo. Copy Icon 
IF EXIST "%ICONDEST%" (GOTO _CopyIcon) 
mkdir "%ICONDEST%"
:_CopyIcon 
copy "%WORKING_PATH%%IconName%" "%ICONDEST%"

echo. 
echo. Create desktop shortcut... 
echo [InternetShortcut] > "%LinkPath%"
echo URL=%URL_PATH% >> "%LinkPath%"
echo IDList= >> "%LinkPath%"
echo IconFile=%ICONDEST%\%IconName% >> "%LinkPath%"
echo IconIndex=0 >> "%LinkPath%"
echo HotKey=0 >> "%LinkPath%"
echo. 
echo. 
echo. 
echo. 
echo.You should now have a shortcut to %AppDescription% on your desktop... 
echo. 
echo.

