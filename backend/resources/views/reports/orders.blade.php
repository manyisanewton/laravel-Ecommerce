<table>
  <thead><tr><th>ID</th><th>Ref</th><th>Status</th><th>Provider</th><th>Payment</th><th>Total</th><th>Created</th></tr></thead>
  <tbody>
  @foreach($data as $row)
    <tr>
      <td>{{ $row->id }}</td><td>{{ $row->reference }}</td><td>{{ $row->status }}</td><td>{{ $row->payment_provider }}</td><td>{{ $row->payment_status }}</td><td>{{ $row->total_amount }}</td><td>{{ $row->created_at }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
