# Agri-Connect - Visual Guide

## 🗂️ Project Structure

```
AgriConnect/
│
├── 📁 assets/                    # Static assets
│   ├── 📁 images/               # SVG graphics
│   │   ├── favicon.svg          # Browser tab icon
│   │   ├── hero-bg.svg          # Landing page background
│   │   ├── icons.svg            # Icon sprite
│   │   ├── logo.svg             # Main logo
│   │   ├── pattern.svg          # Background pattern
│   │   └── product-placeholder.svg  # Product images
│   └── styles.css               # Main stylesheet (500+ lines)
│
├── 📁 database/                  # Database files
│   └── schema.sql               # Database schema
│
├── 📄 Core Files
│   ├── index.php                # Landing page ⭐
│   ├── config.php               # Database config
│   ├── auth.php                 # Authentication helpers
│   ├── login.php                # Login handler
│   ├── signup.php               # Registration handler
│   └── logout.php               # Logout handler
│
├── 📄 User Pages
│   ├── dashboard.php            # User dashboard
│   ├── profile.php              # User profile
│   └── conversations.php        # Message list
│
├── 📄 Product Pages
│   ├── products.php             # Product marketplace
│   ├── product.php              # Product details
│   └── seller_products.php      # Seller management
│
├── 📄 Communication
│   └── chat.php                 # Chat interface
│
├── 📄 Error Pages
│   └── 404.php                  # Not found page
│
└── 📄 Documentation
    ├── README.md                # Main documentation
    ├── SETUP.md                 # Quick setup guide
    ├── FEATURES.md              # Features list
    └── ENHANCEMENT_SUMMARY.md   # Enhancement details
```

## 🎨 Page Layouts

### Landing Page (index.php)
```
┌─────────────────────────────────────┐
│  Header (Sticky Navigation)         │
├─────────────────────────────────────┤
│  Hero Section                        │
│  - Large headline                    │
│  - CTA buttons                       │
│  - Background graphics               │
├─────────────────────────────────────┤
│  Features Section (6 cards)         │
│  [🔒] [✓] [🚚] [💬] [📊] [🌍]      │
├─────────────────────────────────────┤
│  Statistics Section                  │
│  [10K+] [50K+] [98%] [24/7]        │
├─────────────────────────────────────┤
│  About Section                       │
│  - Mission, Vision, Values           │
├─────────────────────────────────────┤
│  Join Section                        │
│  - Buyer benefits | Seller benefits │
├─────────────────────────────────────┤
│  Footer                              │
└─────────────────────────────────────┘
```

### Product Marketplace (products.php)
```
┌─────────────────────────────────────┐
│  Header (Navigation)                 │
├─────────────────────────────────────┤
│  Page Header (Gradient)              │
│  "Premium Product Marketplace"       │
├─────────────────────────────────────┤
│  Product Grid                        │
│  ┌────┐ ┌────┐ ┌────┐              │
│  │IMG │ │IMG │ │IMG │              │
│  │    │ │    │ │    │              │
│  │$$$│ │$$$│ │$$$│              │
│  └────┘ └────┘ └────┘              │
│  ┌────┐ ┌────┐ ┌────┐              │
│  │IMG │ │IMG │ │IMG │              │
│  └────┘ └────┘ └────┘              │
└─────────────────────────────────────┘
```

### Product Details (product.php)
```
┌─────────────────────────────────────┐
│  Header (Navigation)                 │
├─────────────────────────────────────┤
│  Large Product Image                 │
│  [Premium] [In Stock] [Verified]    │
├─────────────────────────────────────┤
│  Product Title              $999.99  │
├─────────────────────────────────────┤
│  Description Section                 │
│  (Detailed product info)             │
├─────────────────────────────────────┤
│  Seller Information Card             │
│  👤 Seller Name                      │
│  [4.8⭐] [500+] [98%]               │
├─────────────────────────────────────┤
│  [💬 Contact Seller Now]            │
├─────────────────────────────────────┤
│  Trust Indicators                    │
│  [✓Quality] [✓Secure] [✓Fast]      │
└─────────────────────────────────────┘
```

### Dashboard (dashboard.php)
```
┌─────────────────────────────────────┐
│  Header (Navigation)                 │
├─────────────────────────────────────┤
│  Welcome Header (Gradient)           │
│  "Welcome, John Doe"                 │
│  "🌾 Premium Seller Dashboard"      │
├─────────────────────────────────────┤
│  Quick Actions                       │
│  ┌──────────┐ ┌──────────┐         │
│  │📊 Browse│ │💬 Messages│         │
│  │ Products │ │          │         │
│  └──────────┘ └──────────┘         │
│  ┌──────────┐                       │
│  │🌽 My    │                       │
│  │ Products │                       │
│  └──────────┘                       │
└─────────────────────────────────────┘
```

### Chat Interface (chat.php)
```
┌─────────────────────────────────────┐
│  Header (Navigation)                 │
├─────────────────────────────────────┤
│  Page Header                         │
│  "💬 Live Conversation"             │
├─────────────────────────────────────┤
│  Message Area (Scrollable)           │
│  ┌─────────────────┐                │
│  │ Seller: Hello   │ (left)         │
│  └─────────────────┘                │
│          ┌─────────────────┐        │
│          │ You: Hi there   │ (right)│
│          └─────────────────┘        │
├─────────────────────────────────────┤
│  [Type message...] [📤 Send]       │
└─────────────────────────────────────┘
```

## 🎨 Color Usage Guide

### Primary Colors
```
🟢 Primary (#2d5016)
   - Headers
   - Main text
   - Primary buttons

🟢 Secondary (#6b8e23)
   - Secondary buttons
   - Accents
   - Gradients

🟢 Accent (#8fbc4b)
   - Highlights
   - Borders
   - Active states

🟡 Gold (#d4af37)
   - Premium elements
   - CTA buttons
   - Special badges
```

### Usage Examples
```css
/* Headers */
background: linear-gradient(135deg, var(--primary), var(--secondary));

/* Buttons */
background: var(--gold);

/* Cards */
border-left: 4px solid var(--accent);

/* Badges */
background: var(--accent);
```

## 🎯 Component Library

### Badges
```html
<span class="badge badge-success">In Stock</span>
<span class="badge badge-warning">Limited</span>
<span class="badge badge-info">Verified</span>
```

### Cards
```html
<div class="card">
  <div class="card-image"></div>
  <div class="card-content">
    <h3>Title</h3>
    <p>Description</p>
    <a href="#">Action</a>
  </div>
</div>
```

### Buttons
```html
<!-- Primary -->
<button type="submit">Submit</button>

<!-- Secondary -->
<a href="#" class="button-secondary">Action</a>

<!-- Gold CTA -->
<button style="background: var(--gold);">Get Started</button>
```

## 📱 Responsive Breakpoints

```
Mobile:  320px - 768px
  - Single column
  - Stacked navigation
  - Full-width cards

Tablet:  768px - 1366px
  - 2 columns
  - Horizontal navigation
  - Medium cards

Desktop: 1366px+
  - 3-4 columns
  - Full navigation
  - Large cards
```

## 🎭 Animation Examples

### Hover Effects
```css
/* Cards */
.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 50px rgba(45,80,22,0.25);
}

/* Buttons */
button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 25px rgba(45,80,22,0.3);
}
```

### Scroll Animations
```javascript
// Elements fade in on scroll
observer.observe(element);
```

## 🔧 Customization Quick Reference

### Change Logo
Replace: `assets/images/logo.svg`

### Change Colors
Edit: `assets/styles.css` → `:root` variables

### Add New Page
1. Copy existing page structure
2. Include `auth.php` and `config.php`
3. Add navigation link
4. Style with existing classes

### Add New Feature
1. Create PHP file
2. Use existing components
3. Follow naming conventions
4. Add to navigation

## 📊 Database Schema Visual

```
┌─────────────┐
│   users     │
├─────────────┤
│ id          │──┐
│ email       │  │
│ password    │  │
│ full_name   │  │
│ role        │  │
│ created_at  │  │
└─────────────┘  │
                 │
┌─────────────┐  │
│  products   │  │
├─────────────┤  │
│ id          │  │
│ seller_id   │←─┘
│ title       │
│ description │
│ price       │
│ created_at  │
└─────────────┘
       │
       │
┌──────────────┐
│conversations │
├──────────────┤
│ id           │
│ buyer_id     │←─┐
│ seller_id    │←─┤
│ product_id   │  │
│ created_at   │  │
└──────────────┘  │
       │          │
       │          │
┌──────────────┐  │
│  messages    │  │
├──────────────┤  │
│ id           │  │
│ conversation │←─┘
│ sender_id    │
│ message      │
│ created_at   │
└──────────────┘
```

## 🎉 Key Features Visual Map

```
Landing Page
    ↓
[Sign Up] → Registration → Dashboard
    ↓
Dashboard → [Browse Products] → Marketplace
    ↓
Product Details → [Contact Seller] → Chat
    ↓
Conversation History ← → Active Chats
```

---

**This visual guide helps you understand the structure and navigate the Agri-Connect system! 🌾**
