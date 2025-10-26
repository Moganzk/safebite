# SafeBite Tracker - API Testing Guide

**Author:** Johnson Siptiek Saruni  
**Project:** Food Safety Inspection System  
**Year:** 2025

---

## Using Browser/Postman/Thunder Client

### Base URL
```
http://localhost/SafeBite%20Tracker/backend/public/
```

---

## 🔐 Authentication Endpoints

### 1. Test API Root
```http
GET http://localhost/SafeBite%20Tracker/backend/public/
```

**Expected Response:**
```json
{
  "success": true,
  "message": "SafeBite Tracker API",
  "data": {
    "name": "SafeBite Tracker",
    "version": "1.0.0",
    "endpoints": { ... }
  }
}
```

---

### 2. Login
```http
POST http://localhost/SafeBite%20Tracker/backend/public/login
Content-Type: application/json

{
  "username": "john_inspector",
  "password": "password123"
}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "username": "john_inspector",
      "email": "john@safebite.com",
      "role": "inspector"
    }
  }
}
```

---

### 3. Check Authentication Status
```http
GET http://localhost/SafeBite%20Tracker/backend/public/check
```

**Expected Response (Logged In):**
```json
{
  "success": true,
  "data": {
    "authenticated": true,
    "user": {
      "id": 1,
      "username": "john_inspector",
      "role": "inspector"
    }
  }
}
```

---

### 4. Get Current User Info
```http
GET http://localhost/SafeBite%20Tracker/backend/public/me
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "username": "john_inspector",
      "email": "john@safebite.com",
      "role": "inspector",
      "created_at": "2025-10-06 10:00:00"
    }
  }
}
```

---

### 5. Logout
```http
POST http://localhost/SafeBite%20Tracker/backend/public/logout
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

---

## 🏪 Vendor Endpoints (CRUD Operations)

### 1. Get All Vendors
```http
GET http://localhost/SafeBite%20Tracker/backend/public/vendors
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Mama Njeri Kitchen",
      "description": "Popular street food vendor...",
      "location": "Nairobi CBD, Tom Mboya Street",
      "phone": "+254712345678",
      "vendor_type": "street_food",
      "user_id": 1,
      "username": "john_inspector",
      "email": "john@safebite.com",
      "created_at": "2025-10-06 10:00:00",
      "updated_at": "2025-10-06 10:00:00"
    }
  ]
}
```

---

### 2. Get Single Vendor
```http
GET http://localhost/SafeBite%20Tracker/backend/public/vendors/1
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Mama Njeri Kitchen",
    "description": "Popular street food vendor...",
    "location": "Nairobi CBD, Tom Mboya Street",
    "phone": "+254712345678",
    "vendor_type": "street_food",
    "user_id": 1,
    "username": "john_inspector",
    "email": "john@safebite.com",
    "created_at": "2025-10-06 10:00:00",
    "updated_at": "2025-10-06 10:00:00"
  }
}
```

---

### 3. Create Vendor (Must be logged in)
```http
POST http://localhost/SafeBite%20Tracker/backend/public/vendors
Content-Type: application/json

{
  "name": "Test Restaurant",
  "description": "A great place for authentic Kenyan cuisine with excellent hygiene standards",
  "location": "Westlands, Nairobi",
  "phone": "+254700123456",
  "vendor_type": "restaurant"
}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Record created successfully",
  "data": {
    "id": 4,
    "name": "Test Restaurant",
    "description": "A great place for authentic...",
    "location": "Westlands, Nairobi",
    "phone": "+254700123456",
    "vendor_type": "restaurant",
    "user_id": 1,
    "username": "john_inspector",
    "email": "john@safebite.com",
    "created_at": "2025-10-06 11:30:00",
    "updated_at": "2025-10-06 11:30:00"
  }
}
```

---

### 4. Update Vendor (Must be owner)
```http
PUT http://localhost/SafeBite%20Tracker/backend/public/vendors/4
Content-Type: application/json

{
  "name": "Updated Restaurant Name",
  "description": "Updated description with more details about the establishment",
  "location": "New Location, Nairobi",
  "phone": "+254700999999",
  "vendor_type": "restaurant"
}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Record updated successfully",
  "data": {
    "id": 4,
    "name": "Updated Restaurant Name",
    "description": "Updated description...",
    "location": "New Location, Nairobi",
    "phone": "+254700999999",
    "vendor_type": "restaurant",
    "user_id": 1,
    "username": "john_inspector",
    "email": "john@safebite.com",
    "created_at": "2025-10-06 11:30:00",
    "updated_at": "2025-10-06 12:00:00"
  }
}
```

---

### 5. Delete Vendor (Must be owner)
```http
DELETE http://localhost/SafeBite%20Tracker/backend/public/vendors/4
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Record deleted successfully"
}
```

---

### 6. Get My Vendors Only
```http
GET http://localhost/SafeBite%20Tracker/backend/public/vendors?my=true
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    // Only vendors created by logged-in user
  ]
}
```

---

### 7. Search Vendors
```http
GET http://localhost/SafeBite%20Tracker/backend/public/vendors?q=kitchen
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    // Vendors matching "kitchen" in name, description, or location
  ]
}
```

---

## ❌ Error Responses

### Unauthorized (Not Logged In)
```json
{
  "success": false,
  "message": "You must be logged in to add a vendor"
}
```

### Forbidden (Not Owner)
```json
{
  "success": false,
  "message": "You can only edit vendors you created"
}
```

### Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": ["Vendor name is required"],
    "description": ["Description must be at least 10 characters"]
  }
}
```

### Not Found
```json
{
  "success": false,
  "message": "Vendor not found"
}
```

---

## 🧪 Testing Workflow

### Complete Test Sequence:

1. **Test API is working**
   ```
   GET /
   ```

2. **Login**
   ```
   POST /login
   Body: { "username": "john_inspector", "password": "password123" }
   ```

3. **Verify login**
   ```
   GET /check
   ```

4. **View all vendors**
   ```
   GET /vendors
   ```

5. **Create vendor**
   ```
   POST /vendors
   Body: { "name": "...", "description": "..." }
   ```

6. **View created vendor**
   ```
   GET /vendors/{id}
   ```

7. **Update vendor**
   ```
   PUT /vendors/{id}
   Body: { "name": "...", "description": "..." }
   ```

8. **Delete vendor**
   ```
   DELETE /vendors/{id}
   ```

9. **Logout**
   ```
   POST /logout
   ```

---

## 📋 Vendor Types

Valid values for `vendor_type`:
- `restaurant`
- `street_food`
- `cafe`
- `catering`
- `other`

---

## 🔑 Sample Credentials

| Username | Password | Role |
|----------|----------|------|
| john_inspector | password123 | inspector |
| jane_supervisor | password123 | supervisor |
| admin_user | password123 | admin |

---

## 💡 Tips for Testing

1. **Use Postman or Thunder Client** for easier API testing
2. **Enable cookies/sessions** in your HTTP client
3. **Test ownership** by creating vendors with different users
4. **Test validation** by sending incomplete data
5. **Check error handling** by sending invalid IDs

---

## 🐛 Common Issues

### Issue: "Connection refused"
- Make sure Apache is running in XAMPP
- Verify URL is correct with proper encoding

### Issue: "Session not persisting"
- Enable "Send cookies" in your HTTP client
- Use the same client for all requests in a session

### Issue: "Database error"
- Check MySQL is running
- Verify database exists and schema is imported

---

**Ready to test!** 🚀
