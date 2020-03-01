;NSIS Modern User Interface
;Header Bitmap Example Script
;Written by Joost Verburg

;--------------------------------
;Include Modern UI

  !include "MUI2.nsh"

;--------------------------------
;General

  ;Name and file
  Name "Apolo"
  OutFile "setup_apolo.exe"
  Unicode True

  ;Default installation folder
  InstallDir " C:\"
  
  ;Get installation folder from registry if available
  InstallDirRegKey HKCU "Software\Apolo" ""

  ;Request application privileges for Windows Vista
  RequestExecutionLevel user

;--------------------------------
;Interface Configuration

  !define MUI_HEADERIMAGE
  !define MUI_HEADERIMAGE_BITMAP "${NSISDIR}\Contrib\Graphics\Header\nsis3-grey.bmp" ; optional
  !define MUI_ABORTWARNING

;--------------------------------
;Pages

  !insertmacro MUI_PAGE_DIRECTORY
  !insertmacro MUI_PAGE_INSTFILES
  
  !insertmacro MUI_UNPAGE_CONFIRM
  !insertmacro MUI_UNPAGE_INSTFILES
  
;--------------------------------
;Languages
 
  !insertmacro MUI_LANGUAGE "PortugueseBR"

;--------------------------------
;Installer Sections

Section "Xampp" Xampp

  SetOutPath "$INSTDIR\xampp"
  
  File /r "xampp\*"

  ExecWait "$INSTDIR\setup_xampp.bat"

  CreateShortCut "$INSTDIR\xampp\xampp_start.lnk" "$INSTDIR\xampp\xampp_start.exe"

  ; Start menu entries
  CopyFiles "$INSTDIR\xampp\xampp_start.lnk" "$SMPROGRAMS\Startup\"
  Delete "$INSTDIR\xampp\xampp_start.lnk"
  
  ;Store installation folder
  WriteRegStr HKCU "Software\Xampp" "" $INSTDIR
  
  ;Create uninstaller
  WriteUninstaller "$INSTDIR\xampp\UninstallApolo.exe"

SectionEnd

Section "Apolo" Apolo

  SetOutPath "$INSTDIR\xampp\htdocs\apolo"
  
  File /r "apolo\*"

  ExecWait "$INSTDIR\xampp\htdocs\apolo\config\setup\initial_set.bat"
  
  ;Store installation folder
  WriteRegStr HKCU "Software\Apolo" "" $INSTDIR

SectionEnd

;--------------------------------
;Uninstaller Section

Section "Uninstall"

  Delete "$INSTDIR\UninstallApolo.exe"

  RMDir "$INSTDIR\xampp"

  DeleteRegKey /ifempty HKCU "Software\Modern UI Test"

SectionEnd

