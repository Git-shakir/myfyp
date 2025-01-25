<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Livestock Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Livestock ID</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Birth Date</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Weight (kg)</th>
                <th>Manager Name</th>
                <th>Manager Phone</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($animalsData as $item)
                <tr>
                    <td>{{ $item['animalid'] ?? 'N/A' }}</td>
                    <td>{{ $item['species'] ?? 'N/A' }}</td>
                    <td>{{ $item['breed'] ?? 'N/A' }}</td>
                    <td>{{ $item['bdate'] ?? 'N/A' }}</td>
                    <td>{{ $item['age'] ?? 'N/A' }}</td>
                    <td>{{ $item['sex'] ?? 'N/A' }}</td>
                    <td>{{ $item['weight'] ?? 'N/A' }}</td>
                    <td>{{ $item['mname'] ?? 'N/A' }}</td>
                    <td>{{ $item['mphone'] ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
