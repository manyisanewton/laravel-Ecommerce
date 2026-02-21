<table>
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th></tr></thead>
  <tbody>
  @foreach($data as $row)
    <tr>
      <td>{{ $row->id }}</td><td>{{ $row->name }}</td><td>{{ $row->email }}</td><td>{{ $row->role }}</td><td>{{ $row->status }}</td><td>{{ $row->created_at }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
