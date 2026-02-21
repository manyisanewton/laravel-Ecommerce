import { useEffect, useMemo, useState } from 'react'
import { api } from '../api/client'

export default function ProductsPage() {
  const [products, setProducts] = useState([])
  const [cart, setCart] = useState([])
  const [provider, setProvider] = useState('mpesa')
  const [phoneNumber, setPhoneNumber] = useState('')
  const [feedback, setFeedback] = useState('')

  useEffect(() => {
    api.get('/products').then(({ data }) => setProducts(data.data || []))
  }, [])

  const total = useMemo(
    () => cart.reduce((sum, item) => sum + (Number(item.price) * item.quantity), 0),
    [cart],
  )

  const addToCart = (product) => {
    setCart((prev) => {
      const idx = prev.findIndex((item) => item.id === product.id)
      if (idx >= 0) {
        const copy = [...prev]
        copy[idx].quantity += 1
        return copy
      }
      return [...prev, { ...product, quantity: 1 }]
    })
  }

  const checkout = async () => {
    setFeedback('')
    const payload = {
      provider,
      phone_number: phoneNumber,
      items: cart.map((item) => ({ product_id: item.id, quantity: item.quantity })),
    }

    try {
      const { data } = await api.post('/checkout', payload)
      setFeedback(`Order ${data.order.reference} created. Payment status: ${data.payment.status}`)
      setCart([])
    } catch (err) {
      setFeedback(err.response?.data?.message || 'Checkout failed. Login is required.')
    }
  }

  return (
    <section className="layout-grid">
      <div>
        <h1>Products</h1>
        <div className="product-grid">
          {products.map((product) => (
            <article key={product.id} className="card product-card">
              <h3>{product.name}</h3>
              <p>{product.description || 'No description provided.'}</p>
              <strong>KES {Number(product.price).toLocaleString()}</strong>
              <button className="btn" onClick={() => addToCart(product)}>Add to Cart</button>
            </article>
          ))}
        </div>
      </div>
      <aside className="card cart-panel">
        <h2>Cart</h2>
        {cart.length === 0 && <p>No items selected</p>}
        {cart.map((item) => (
          <p key={item.id}>{item.name} x {item.quantity}</p>
        ))}
        <p className="total">Total: KES {total.toLocaleString()}</p>
        <select value={provider} onChange={(e) => setProvider(e.target.value)}>
          <option value="mpesa">M-PESA</option>
          <option value="flutterwave">Flutterwave</option>
          <option value="pesapal">PesaPal/DPO</option>
        </select>
        <input placeholder="Phone number (M-PESA)" value={phoneNumber} onChange={(e) => setPhoneNumber(e.target.value)} />
        <button className="btn" disabled={cart.length === 0} onClick={checkout}>Checkout</button>
        {feedback && <p className="small-note">{feedback}</p>}
      </aside>
    </section>
  )
}
