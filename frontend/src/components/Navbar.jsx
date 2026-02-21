import { Link } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'

export default function Navbar() {
  const { user, logout } = useAuth()

  return (
    <header className="topbar">
      <div className="brand">
        <span className="logo" />
        <strong>Symatech Store</strong>
      </div>
      <nav className="nav-links">
        <Link to="/">Products</Link>
        {!user && <Link to="/login">Login</Link>}
        {!user && <Link to="/register">Register</Link>}
        {user?.role === 'admin' && <Link to="/admin">Admin</Link>}
        {user && <button className="btn ghost" onClick={logout}>Logout</button>}
      </nav>
    </header>
  )
}
