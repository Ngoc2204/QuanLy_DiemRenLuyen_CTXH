$ip = "192.168.5.251"
$port = "8000"
$url = "http://$ip`:$port/api/v1/activities"

Write-Host "Testing API: $url"
try {
    $response = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 5
    Write-Host "✓ Success! Status: $($response.StatusCode)"
    Write-Host "Content: $($response.Content | Select-String 'success|message' -Context 0,1)"
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)"
}
