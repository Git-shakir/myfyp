<!DOCTYPE html>
<html>
<head>
    <title>Livestock Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 100%;
            max-height: 150px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-top: 5px;
        }
        h1 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/Header Borang.png') }}" alt="Header Image">
    </div>

    <h1>Livestock Report</h1>

    <table>
        <thead>
            <tr>
                <th>Livestock ID</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Manager Name</th>
                <th>Manager Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($animalsData as $animal)
                <tr>
                    <td>{{ $animal['animalid'] ?? 'N/A' }}</td>
                    <td>{{ $animal['species'] ?? 'N/A' }}</td>
                    <td>{{ $animal['breed'] ?? 'N/A' }}</td>
                    <td>{{ $animal['age'] ?? 'N/A' }}</td>
                    <td>{{ $animal['sex'] ?? 'N/A' }}</td>
                    <td>{{ $animal['mname'] ?? 'N/A' }}</td>
                    <td>{{ $animal['mphone'] ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
