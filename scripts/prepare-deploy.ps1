$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$deployDir = Join-Path $projectRoot 'deploy'

if (Test-Path $deployDir) {
  Remove-Item $deployDir -Recurse -Force
}

New-Item -ItemType Directory -Path $deployDir | Out-Null

$items = @(
  'index.html',
  'biography.html',
  'gallery.html',
  'music.html',
  'videos.html',
  'events.html',
  'lessons.html',
  'contact.html',
  'css',
  'js',
  'images',
  'audio',
  'netlify.toml'
)

foreach ($item in $items) {
  $source = Join-Path $projectRoot $item
  if (-not (Test-Path $source)) {
    throw "Missing deploy asset: $item"
  }
  Copy-Item $source -Destination $deployDir -Recurse -Force
}

Write-Host "Deploy bundle ready at $deployDir"
