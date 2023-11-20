<!DOCTYPE html>
<html lang="en">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converter BPJS</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: space-between;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 45%;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        input {
            padding: 10px;
            margin-bottom: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        p {
            margin-top: 20px;
            font-size: 14px;
            color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Converter BPJS-KS</h2>

        @if(session('message'))
            <p style="color: green;">{{ session('message') }}</p>
        @endif

        <form action="{{ url('/convert-to-txt') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="excelFile">Upload Excel File:</label>
            <input type="file" name="excelFile" id="excelFile" accept=".xls, .xlsx" required>
            <button type="submit" id="convertBPJSKS">Convert BPJS-KS to TXT</button>
        </form>
    </div>

    <div class="container">
        <h2>Converter BPJS-TK</h2>
    
        @if(session('message_bpjstk'))
            <p style="color: green;">{{ session('message_bpjstk') }}</p>
        @endif
    
        {{-- <form id="conversionForm" action="{{ url('/convert-bpjs-tk-to-txt') }}" method="POST" enctype="multipart/form-data" onsubmit="submitForm()">
            @csrf
            <label for="excelFileBPJSTK">Upload Excel File:</label>
            <input type="file" name="excelFile" id="excelFile" accept=".xls, .xlsx" required>
            <button type="submit" id="convertBPJSTK">Convert BPJS-TK to TXT</button>
        </form> --}}

        <form action="{{ url('/convert-bpjs-tk-to-txt') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="excelFileBPJSTK">Upload Excel File:</label>
            <input type="file" name="excelFile" id="excelFile" accept=".xls, .xlsx" required>
            <button type="submit" id="convertBPJSTK">Convert BPJS-TK to TXT</button>
        </form>
    </div>
    
{{-- <script>
    $(document).ready(function() {
        $('#convertBPJSKS').click(function() {
            logButtonClick('BPJS-KS');
        });
    
        $('#convertBPJSTK').click(function() {
            logButtonClick('BPJS-TK');
        });
    
        function logButtonClick(type) {
            $.ajax({
                type: 'POST',
                url: '/log-button-click',
                data: { buttonType: type },
                success: function(response) {
                    console.log('Button click logged successfully.');
                },
                error: function(error) {
                    console.error('Error logging button click:', error);
                }
            });
        }
    });
    </script> --}}
        <script>
            function submitForm() {
                // Disable the submit button to prevent multiple submissions
                document.getElementById('convertBPJSTK').disabled = true;
        
                // Submit the form using AJAX
                var form = document.getElementById('conversionForm');
                var formData = new FormData(form);
        
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.blob())
                .then(blob => {
                    // Create a download link for the generated file
                    var downloadLink = document.createElement('a');
                    var objectUrl = URL.createObjectURL(blob);
                    downloadLink.href = objectUrl;
                    downloadLink.download = 'output.txt';
        
                    // Trigger a click on the link to initiate the download
                    downloadLink.click();
        
                    // Enable the submit button and reset the form
                    document.getElementById('convertBPJSTK').disabled = false;
                    form.reset();
                })
                .catch(error => {
                    console.error('Conversion error:', error);
                    // Handle errors if necessary
                });
        
                // Prevent the default form submission
                return false;
            }
        </script>
        
</html>
