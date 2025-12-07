cd ..
powershell -Command "Invoke-WebRequest -Uri 'https://windows.php.net/downloads/releases/php-8.5.0-Win32-vs17-x64.zip' -OutFile 'tmp\php.zip'"
powershell -Command "(New-Object Net.WebClient).DownloadFile('https://nodejs.org/dist/v24.11.1/node-v24.11.1-win-x64.zip', 'tmp\node.zip')"
powershell -Command "(New-Object Net.WebClient).DownloadFile('https://github.com/google/brotli/releases/download/v1.2.0/brotli-x64-windows-static.zip', 'tmp\brotli.zip')"
powershell -Command "(New-Object Net.WebClient).DownloadFile('https://github.com/yt-dlp/yt-dlp-nightly-builds/releases/download/2025.12.05.232956/yt-dlp.exe', 'bin\yt-dlp.exe')"
powershell -Command "(New-Object Net.WebClient).DownloadFile('https://github.com/BtbN/FFmpeg-Builds/releases/download/autobuild-2025-12-06-12-54/ffmpeg-N-122015-g6a14a93af5-win64-gpl.zip', 'tmp\ffmpeg.zip')"

tar -xf tmp/php.zip -C "bin/php"
tar -xf tmp/node.zip --strip-components=1 -C "bin/nodejs"
tar -xf tmp/ffmpeg.zip --strip-components=2 -C "bin" "*/bin/ffmpeg.exe"
tar -xf tmp/brotli.zip -C "bin" brotli.exe

del /f /q "tmp\*"