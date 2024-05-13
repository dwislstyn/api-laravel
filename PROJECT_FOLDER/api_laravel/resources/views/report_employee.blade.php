<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
</style>
<body>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="text-align: center;">Employee ID</th>
                <th style="text-align: center;">NIK</th>
                <th style="text-align: center;">Name</th>
                <th style="text-align: center;">Is Active</th>
                <th style="text-align: center;">Age</th>
                <th style="text-align: center;">School Name</th>
                <th style="text-align: center;">Level</th>
                <th style="text-align: center;">Family Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_pdf as $listData)
            <tr>
                <td>{{ $listData->employee_id }}</td>
                <td>{{ $listData->nik }}</td>
                <td>{{ $listData->name }}</td>
                <td>{{ $listData->is_active }}</td>
                <td>{{ $listData->age }}</td>
                <td>{{ $listData->school_name }}</td>
                <td>{{ $listData->level }}</td>
                <td>{{ $listData->family_data }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>