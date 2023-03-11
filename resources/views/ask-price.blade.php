<!DOCTYPE html>
<html>
<head>
    <title>Ask for Price</title>
</head>
<body>
    <h2>Ask for Price</h2>
    <p><strong>Name:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Phone:</strong> {{ $phone }}</p>
    <p><strong>Company:</strong> {{ $company ?? '' }}</p>
    <p><strong>Product:</strong> {{ $product }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $inquiry ?? '' }}</p>
</body>
</html>
