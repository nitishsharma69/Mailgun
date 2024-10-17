<!DOCTYPE html>
<html>
<head>
    <title>Upload Contacts</title>
</head>
<body>
    <h1>Upload Contacts File</h1>
    
    <form action="/mail-campaign" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload and Send Emails</button>
    </form>

</body>
</html>
