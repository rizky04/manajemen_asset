<div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f5f5f5;">
                <th>Company ID</th>
                <th>Name</th>
                <th>Department ID</th>
                <th>Position</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
            <tr>
                <td>{{ $row[0] }}</td>
                <td>{{ $row[1] }}</td>
                <td>{{ $row[2] }}</td>
                <td>{{ $row[3] }}</td>
                <td>{{ $row[4] }}</td>
                <td>{{ $row[5] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button
        onclick="window.confirmImport()"
        style="margin-top:10px; background:#16a34a; color:white; padding:6px 12px; border-radius:6px;">
        Confirm Import
    </button>

    <script>
    window.confirmImport = function() {
        fetch("{{ route('employee.import.confirm') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            window.location.reload();
        });
    }
    </script>

    </div>
