import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'

export default function LoginPage() {
  const { login, loading } = useAuth()
  const navigate = useNavigate()
  const [form, setForm] = useState({ email: '', password: '' })
  const [error, setError] = useState('')

  const submit = async (event) => {
    event.preventDefault()
    setError('')
    try {
      const data = await login(form)
      navigate(data.user.role === 'admin' ? '/admin' : '/')
    } catch (err) {
      setError(err.response?.data?.message || 'Login failed')
    }
  }

  return (
    <section className="card auth-card">
      <h1>Welcome back</h1>
      <form onSubmit={submit} className="grid-gap">
        <input placeholder="Email" type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} required />
        <input placeholder="Password" type="password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} required />
        {error && <p className="error">{error}</p>}
        <button className="btn" disabled={loading}>{loading ? 'Signing in...' : 'Login'}</button>
      </form>
    </section>
  )
}
