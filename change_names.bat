@echo off
for /f tokens^=* %%i in ('where .:*')do (
for /f "usebackq tokens=3 delims=," %%a in ("C:\Users\Administrator\Desktop\simo\%%~nxi") do (
	rename "%%~nxi" "%%a"
	)

)

