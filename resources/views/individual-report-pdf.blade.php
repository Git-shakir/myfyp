<!DOCTYPE html>
<html>

<head>
    <title>Livestock Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
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

        h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/Header Borang.png') }}" alt="Header Image">
    </div>

    <h1>Livestock Report</h1>

    <h2>Livestock Details</h2>
    <table>
        <tr>
            <th>Livestock ID</th>
            <td>{{ $animal['animalid'] }}</td>
        </tr>
        <tr>
            <th>Species</th>
            <td>{{ $animal['species'] }}</td>
        </tr>
        <tr>
            <th>Breed</th>
            <td>{{ $animal['breed'] }}</td>
        </tr>
        <tr>
            <th>Age</th>
            <td>{{ $animal['age'] }}</td>
        </tr>
        <tr>
            <th>Sex</th>
            <td>{{ $animal['sex'] }}</td>
        </tr>
        <tr>
            <th>Manager Name</th>
            <td>{{ $animal['mname'] }}</td>
        </tr>
        <tr>
            <th>Manager Phone</th>
            <td>{{ $animal['mphone'] }}</td>
        </tr>
    </table>

    <h2 class="section-title">Checkup History</h2>
    @php
        $limitedCheckupHistory = $checkupHistory;
    @endphp
    @if (!empty($checkupHistory))
        <table>
            <thead>
                <tr>
                    <th>Examined At</th>
                    @foreach ($checkupHistory as $checkup)
                        <th>{{ $checkup['examined_at'] ?? 'N/A' }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Temperature</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['temperature'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Weight</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['weight'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>General Appearance</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['genApp'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Mucous Membrane</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['mucous'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Integument</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['integument'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Nervous</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['nervous'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Musculoskeletal</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['musculoskeletal'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Eyes</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['eyes'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Ears</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['ears'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Gastrointestinal</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['gastrointestinal'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Respiratory</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['respiratory'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Cardiovascular</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['cardiovascular'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Reproductive</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['reproductive'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Urinary</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['urinary'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Mammary Gland</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['mGland'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Lymphatic</th>
                    @foreach ($checkupHistory as $checkup)
                        <td>{{ $checkup['lymphatic'] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    @else
        <p>No checkup history available for this livestock.</p>
    @endif
</body>

</html>
