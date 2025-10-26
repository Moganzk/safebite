# 🚀 SafeBite Tracker - Quick Reference Card

**Author:** Johnson Siptiek Saruni | **Year:** 2025

---

## ⚡ Super Quick Start (2 Minutes)

### Step 1: Start XAMPP
- Apache: ✅ Start
- MySQL: ✅ Start

### Step 2: Setup Database
```sql
1. Go to: http://localhost/phpmyadmin
2. Click "New"
3. Database name: safebite
4. Click "Create"
5. Click "Import"
6. Choose: C:\xampp\htdocs\SafeBite Tracker\database\schema.sql
7. Click "Go"
```

### Step 3: Test
```
1. Go to: http://localhost/SafeBite%20Tracker/frontend/
2. Click "Get Started"
3. Login: john_inspector / password123
4. Test all features!
```

---

## 🔑 Login Credentials

| Username | Password |
|----------|----------|
| john_inspector | password123 |
| jane_supervisor | password123 |
| admin_user | password123 |

---

## 🌐 Important URLs

| Page | URL |
|------|-----|
| 🏠 Home | `http://localhost/SafeBite%20Tracker/frontend/` |
| 🔐 Login | `http://localhost/SafeBite%20Tracker/frontend/login.php` |
| 👀 View Vendors | `http://localhost/SafeBite%20Tracker/frontend/view_vendors.php` |
| ➕ Add Vendor | `http://localhost/SafeBite%20Tracker/frontend/post_vendor.php` |
| 🔧 API | `http://localhost/SafeBite%20Tracker/backend/public/` |
| 💾 Database | `http://localhost/phpmyadmin` |

---

## ✅ Assignment Checklist

| Req | Task | File | Marks |
|-----|------|------|-------|
| 1 | Database setup | schema.sql | 10 |
| 2 | Login system | login.php | 20 |
| 3 | Add vendor | post_vendor.php | 20 |
| 4 | View vendors | view_vendors.php | 15 |
| 5 | Edit vendor | edit_vendor.php | 20 |
| 6 | Delete vendor | view_vendors.php | 15 |
| **Total** | | | **100** |

---

## 🎯 Demo Steps (5 Minutes)

### 1️⃣ Show Database (1 min)
- Open phpMyAdmin
- Show `users` table with hashed passwords
- Show `vendors` table with user_id FK

### 2️⃣ Login (1 min)
- Go to login.php
- Login: john_inspector / password123
- Show redirect to vendor list

### 3️⃣ Add Vendor (1 min)
- Click "Add New Vendor"
- Fill form and submit
- Show new vendor in list

### 4️⃣ View All (1 min)
- Point out username (JOIN query)
- Point out timestamp
- Point out Edit/Delete on YOUR vendors only

### 5️⃣ Edit & Delete (1 min)
- Edit your vendor (works ✅)
- Try to edit another user's vendor (fails ❌)
- Delete your vendor (works ✅)

---

## 🔐 Security Features

✅ Password hashing (`password_hash()`)  
✅ Password verification (`password_verify()`)  
✅ SQL injection prevention (PDO prepared statements)  
✅ XSS protection (input sanitization)  
✅ Session management  
✅ Ownership validation  

---

## 🐛 Common Issues & Fixes

| Problem | Fix |
|---------|-----|
| "Connection error" | Start Apache & MySQL in XAMPP |
| "Database not found" | Import schema.sql in phpMyAdmin |
| "Can't edit vendor" | Only owner can edit (expected behavior) |
| "Login fails" | Check database was imported correctly |
| "Blank page" | Check PHP errors in Apache error log |

---

## 📁 Project Structure

```
SafeBite Tracker/
├── backend/
│   ├── config/          # Database & constants
│   ├── controllers/     # Auth & Vendor logic
│   ├── models/          # User & Vendor models
│   ├── middleware/      # Auth & CORS
│   ├── utils/           # Helpers
│   └── public/          # API entry point
├── database/
│   └── schema.sql       # Database setup
├── frontend/
│   ├── login.php        # Login page
│   ├── post_vendor.php  # Add vendor
│   ├── view_vendors.php # List vendors
│   └── edit_vendor.php  # Edit vendor
└── docs/
    ├── README.md
    ├── SETUP.md
    └── PROJECT_SUMMARY.md
```

---

## 💡 Key Code Snippets

### Password Hashing
```php
password_hash($password, PASSWORD_DEFAULT);
password_verify($password, $hash);
```

### JOIN Query
```php
SELECT v.*, u.username 
FROM vendors v
LEFT JOIN users u ON v.user_id = u.id
```

### Ownership Check
```php
if (!$vendorModel->isOwner($id, $_SESSION['user_id'])) {
    Response::forbidden('Not authorized');
}
```

---

## 📊 Grading Rubric

- **10%** - Database setup correct ✅
- **20%** - Secure login (password_hash/verify) ✅
- **20%** - Correct insert with user link ✅
- **15%** - Items displayed with JOIN ✅
- **20%** - Update works & restricted ✅
- **15%** - Delete works & restricted ✅

---

## 🎓 What You've Built

✅ Complete CRUD application  
✅ Secure authentication system  
✅ Role-based access control  
✅ RESTful API backend  
✅ Modern responsive frontend  
✅ Real-world problem solver  

---

## 🚀 Ready to Go!

**Status:** ✅ Complete  
**Grade Target:** 100/100  
**Time to Setup:** 2 minutes  
**Time to Demo:** 5 minutes  

---

## 📞 Need Help?

**Read these files in order:**
1. `docs/SETUP.md` - How to install
2. `README.md` - Project overview
3. `docs/PROJECT_SUMMARY.md` - Complete details
4. `docs/API_TESTING.md` - API testing

---

**🎉 Your project is READY! Good luck!** 🚀
