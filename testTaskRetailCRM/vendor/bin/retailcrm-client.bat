@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../retailcrm/api-client-php/bin/retailcrm-client
php "%BIN_TARGET%" %*
