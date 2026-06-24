$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$toolsDir = Join-Path $projectRoot '.tools'
$nodeDir = Join-Path $toolsDir 'node'
$nodeZip = Join-Path $toolsDir 'node.zip'
$nodeVersion = 'v22.14.0'
$nodeUrl = "https://nodejs.org/dist/$nodeVersion/node-$nodeVersion-win-x64.zip"

if (-not (Test-Path (Join-Path $nodeDir 'node.exe'))) {
  Write-Host "Downloading portable Node.js $nodeVersion..."
  New-Item -ItemType Directory -Path $toolsDir -Force | Out-Null
  Invoke-WebRequest -Uri $nodeUrl -OutFile $nodeZip
  Expand-Archive -Path $nodeZip -DestinationPath $toolsDir -Force
  Rename-Item -Path (Join-Path $toolsDir "node-$nodeVersion-win-x64") -NewName 'node' -Force
  Remove-Item $nodeZip -Force
}

$npm = Join-Path $nodeDir 'npm.cmd'
$node = Join-Path $nodeDir 'node.exe'

$env:Path = "$nodeDir;$env:Path"

Set-Location $projectRoot

if (-not (Test-Path (Join-Path $projectRoot 'node_modules\vitest'))) {
  Write-Host 'Installing test dependencies...'
  & $npm install
}

Write-Host 'Running tests...'
& $npm test
