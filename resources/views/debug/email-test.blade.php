<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Testing Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .card { margin-bottom: 20px; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Email Testing Tool</h1>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Current Email Configuration</h5>
            </div>
            <div class="card-body">
                <div id="config-display">
                    <p>Loading configuration...</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Send Test Email</h5>
            </div>
            <div class="card-body">
                <form id="email-test-form">
                    <div class="mb-3">
                        <label for="email-type" class="form-label">Email Type</label>
                        <select class="form-select" id="email-type" name="type">
                            <option value="raw">Raw Text Email</option>
                            <option value="welcome">Welcome Email</option>
                            <option value="order">Order Confirmation</option>
                            <option value="status">Order Status Update</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Send Test Email</button>
                </form>
            </div>
        </div>
        
        <div class="card" id="result-card" style="display: none;">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Test Results</h5>
            </div>
            <div class="card-body">
                <div id="result-display"></div>
            </div>
        </div>
    </div>
    
    <script>
        // Fetch current configuration
        fetch('/debug/email?type=raw')
            .then(response => response.json())
            .then(data => {
                const configHtml = `
                    <table class="table table-striped">
                        <tr>
                            <th>Mail Driver</th>
                            <td>${data.config.driver || 'Not set'}</td>
                        </tr>
                        <tr>
                            <th>SMTP Host</th>
                            <td>${data.config.host || 'Not set'}</td>
                        </tr>
                        <tr>
                            <th>SMTP Port</th>
                            <td>${data.config.port || 'Not set'}</td>
                        </tr>
                        <tr>
                            <th>Encryption</th>
                            <td>${data.config.encryption || 'Not set'}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>${data.config.username || 'Not set'}</td>
                        </tr>
                        <tr>
                            <th>From Address</th>
                            <td>${data.config.from_address || 'Not set'}</td>
                        </tr>
                        <tr>
                            <th>From Name</th>
                            <td>${data.config.from_name || 'Not set'}</td>
                        </tr>
                    </table>
                `;
                document.getElementById('config-display').innerHTML = configHtml;
            })
            .catch(error => {
                document.getElementById('config-display').innerHTML = `<div class="alert alert-danger">Error loading configuration: ${error.message}</div>`;
            });
        
        // Handle form submission
        document.getElementById('email-test-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const type = document.getElementById('email-type').value;
            const resultCard = document.getElementById('result-card');
            const resultDisplay = document.getElementById('result-display');
            
            resultDisplay.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            resultCard.style.display = 'block';
            
            fetch(`/debug/email?type=${type}`)
                .then(response => response.json())
                .then(data => {
                    let resultHtml = '';
                    
                    if (data.success) {
                        resultHtml += `<div class="alert alert-success">${data.message}</div>`;
                        if (data.warning) {
                            resultHtml += `<div class="alert alert-warning">${data.warning}</div>`;
                        }
                        resultHtml += `<p><strong>Email sent to:</strong> ${data.recipient}</p>`;
                    } else {
                        resultHtml += `<div class="alert alert-danger">${data.message}</div>`;
                        resultHtml += `<p><strong>Error:</strong> ${data.error}</p>`;
                        resultHtml += `<pre>${data.trace}</pre>`;
                    }
                    
                    resultDisplay.innerHTML = resultHtml;
                })
                .catch(error => {
                    resultDisplay.innerHTML = `<div class="alert alert-danger">Request failed: ${error.message}</div>`;
                });
        });
    </script>
</body>
</html>