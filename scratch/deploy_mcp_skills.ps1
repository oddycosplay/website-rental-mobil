$sourceDir = "c:\laragon\www\rental_project\scratch\ext-apps\plugins\mcp-apps\skills"
$targetDirs = @(
    "C:\Users\oddycosplay\.gemini\config\skills",
    "C:\Users\oddycosplay\.gemini\antigravity-ide\skills"
)

$skillsToDeploy = @(
    "add-app-to-server",
    "convert-web-app",
    "create-mcp-app",
    "migrate-oai-app"
)

# 1. Copy folders
foreach ($targetDir in $targetDirs) {
    if (-not (Test-Path $targetDir)) {
        Write-Host "Creating target directory: $targetDir"
        New-Item -ItemType Directory -Force -Path $targetDir | Out-Null
    }
    
    foreach ($skill in $skillsToDeploy) {
        $srcSkillPath = Join-Path $sourceDir $skill
        $dstSkillPath = Join-Path $targetDir $skill
        
        Write-Host "Copying $srcSkillPath to $dstSkillPath..."
        if (Test-Path $dstSkillPath) {
            Remove-Item -Recurse -Force $dstSkillPath
        }
        Copy-Item -Recurse -Force $srcSkillPath $dstSkillPath
    }
}

# 2. Update manifest files
foreach ($targetDir in $targetDirs) {
    $manifestPath = Join-Path $targetDir ".antigravity-install-manifest.json"
    if (Test-Path $manifestPath) {
        Write-Host "Updating manifest: $manifestPath"
        $manifestJson = Get-Content -Raw -Path $manifestPath | ConvertFrom-Json
        
        # Safely convert JSON array to a List
        $entries = [System.Collections.Generic.List[string]]::new()
        if ($manifestJson.entries) {
            foreach ($entry in $manifestJson.entries) {
                [void]$entries.Add($entry)
            }
        }
        
        $updated = $false
        foreach ($skill in $skillsToDeploy) {
            if (-not $entries.Contains($skill)) {
                [void]$entries.Add($skill)
                $updated = $true
            }
        }
        
        # Force rewrite manifest to ensure clean execution and correct updatedAt format using Invariant Culture
        $sortedEntries = $entries | Sort-Object
        $manifestJson.entries = $sortedEntries
        $manifestJson.updatedAt = [System.DateTime]::UtcNow.ToString("yyyy-MM-ddTHH\:mm\:ss.fffZ", [System.Globalization.CultureInfo]::InvariantCulture)
        
        # Format JSON beautifully
        $newJsonContent = ConvertTo-Json -InputObject $manifestJson -Depth 100
        
        Set-Content -Path $manifestPath -Value $newJsonContent -Encoding utf8
        Write-Host "Manifest successfully updated."
    } else {
        Write-Warning "Manifest file not found at $manifestPath"
    }
}

Write-Host "All tasks completed successfully!"
