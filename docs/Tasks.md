# Web-Based System Development Task

**Kindly Note:** Apply this task in your area selected as a GAP so as to start developing a mini system in the selected domain.

**Reminder:** The Web Based system should solve a real problem as guided in week 1-3 lecture, not a mere class exercise to earn marks.

## Tasks

### 1. Setup (10 marks)
- Create users and items tables
- Insert a sample user with a hashed password

### 2. Login (20 marks)
- Implement `login.php` with `password_verify()`
- Redirect to `post_item.php` on success

### 3. Post Item (20 marks)
- Create form to submit item name + description
- Insert into DB linked with logged-in user

### 4. View Items (15 marks)
- Display all items with username and time
- Show Edit/Delete links only for the logged-in user

### 5. Update Item (20 marks)
- Load selected item in form
- Save changes back to DB using UPDATE

### 6. Delete Item (15 marks)
- Implement item deletion with ownership check

## Grading Rubric

- **Database setup correct** - 10%
- **Secure login** (password_hash, password_verify) - 20%
- **Correct insert** (with user link) - 20%
- **Items displayed properly** (JOIN users) - 15%
- **Update works and restricted to owner** - 20%
- **Delete works and restricted to owner** - 15%