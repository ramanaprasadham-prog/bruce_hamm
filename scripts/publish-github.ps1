$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$gh = 'C:\Program Files\GitHub CLI\gh.exe'
$git = 'C:\Program Files\Git\bin\git.exe'

Set-Location $projectRoot

& $gh auth status | Out-Null

$repoName = 'path_of_raga'
$owner = (& $gh api user -q .login)

Write-Host "Publishing to GitHub Pages as $owner/$repoName ..."

if (-not (git remote get-url origin 2>$null)) {
  & $gh repo create $repoName --public --source=. --remote=origin --push
} else {
  & $git push -u origin main
}

& $gh api "repos/$owner/$repoName/pages" -f build_type=workflow | Out-Null

Write-Host ""
Write-Host "GitHub Actions is deploying the site."
Write-Host "Live URL (in ~1 minute): https://$owner.github.io/$repoName/"
