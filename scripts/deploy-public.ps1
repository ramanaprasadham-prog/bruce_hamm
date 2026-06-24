$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$nodeDir = Join-Path $projectRoot '.tools\node'
$env:Path = "$nodeDir;$env:Path"

& (Join-Path $PSScriptRoot 'prepare-deploy.ps1')

$deployDir = Join-Path $projectRoot 'deploy'
$domain = 'path-of-raga.surge.sh'

Write-Host "Publishing to https://$domain ..."
Push-Location $deployDir
try {
  & npx --yes surge . $domain
} finally {
  Pop-Location
}
