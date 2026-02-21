import { createContext, useContext, useEffect, useMemo, useState } from 'react'
import { api } from '../api/client'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [token, setToken] = useState(localStorage.getItem('token'))
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    if (!token) return
    api.get('/auth/me')
      .then(({ data }) => setUser(data))
      .catch(() => {
        localStorage.removeItem('token')
        setToken(null)
        setUser(null)
      })
  }, [token])

  const login = async (payload) => {
    setLoading(true)
    try {
      const { data } = await api.post('/auth/login', payload)
      localStorage.setItem('token', data.token)
      setToken(data.token)
      setUser(data.user)
      return data
    } finally {
      setLoading(false)
    }
  }

  const register = async (payload) => {
    setLoading(true)
    try {
      const { data } = await api.post('/auth/register', payload)
      localStorage.setItem('token', data.token)
      setToken(data.token)
      setUser(data.user)
      return data
    } finally {
      setLoading(false)
    }
  }

  const logout = async () => {
    try {
      await api.post('/auth/logout')
    } catch {
      // no-op
    }
    localStorage.removeItem('token')
    setToken(null)
    setUser(null)
  }

  const value = useMemo(() => ({ user, token, loading, login, register, logout }), [user, token, loading])
  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}

export const useAuth = () => useContext(AuthContext)
