import { useState } from 'react'
import { api } from '../api/client'

export default function AdminDashboard() {
  const [from, setFrom] = useState('2026-01-01')
  const [to, setTo] = useState('2026-12-31')
  const [reportType, setReportType] = useState('orders')
  const [reportData, setReportData] = useState([])

  const loadReport = async () => {
    const { data } = await api.get(`/admin/reports/${reportType}`, { params: { from, to } })
    setReportData(data.data || [])
  }

  const exportReport = async (format) => {
    const response = await api.get(`/admin/reports/export/${reportType}`, {
      params: { from, to, format },
      responseType: 'blob',
    })

    const ext = format === 'excel' ? 'xlsx' : 'pdf'
    const blob = new Blob([response.data])
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `${reportType}-report.${ext}`
    link.click()
    URL.revokeObjectURL(url)
  }

  return (
    <section className="card">
      <h1>Administrator Dashboard</h1>
      <div className="filters">
        <input type="date" value={from} onChange={(e) => setFrom(e.target.value)} />
        <input type="date" value={to} onChange={(e) => setTo(e.target.value)} />
        <select value={reportType} onChange={(e) => setReportType(e.target.value)}>
          <option value="users">Registered Users</option>
          <option value="orders">Orders</option>
          <option value="activity">Activity Log</option>
        </select>
        <button className="btn" onClick={loadReport}>Load Report</button>
        <button className="btn ghost" onClick={() => exportReport('excel')}>Export Excel</button>
        <button className="btn ghost" onClick={() => exportReport('pdf')}>Export PDF</button>
      </div>
      <div className="report-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Created At</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            {reportData.map((row) => (
              <tr key={row.id}>
                <td>{row.id}</td>
                <td>{row.created_at}</td>
                <td><code>{JSON.stringify(row)}</code></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </section>
  )
}
