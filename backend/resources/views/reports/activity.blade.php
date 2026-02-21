<table>
  <thead><tr><th>ID</th><th>Action</th><th>Method</th><th>Endpoint</th><th>IP</th><th>Created</th></tr></thead>
  <tbody>
  @foreach($data as $row)
    <tr>
      <td>{{ $row->id }}</td><td>{{ $row->action }}</td><td>{{ $row->method }}</td><td>{{ $row->endpoint }}</td><td>{{ $row->ip_address }}</td><td>{{ $row->created_at }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
