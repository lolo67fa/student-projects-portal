# Student Projects Portal | بوابة مشاريع الطلاب

A web-based portal where students upload their academic projects, teachers and admins review and approve them, and visitors can browse, download, and comment on the published work.

Built with **PHP** and **MySQL** — no frameworks, no dependencies. Runs on any Apache + PHP + MySQL stack (XAMPP, WAMP, LAMP).

منصة ويب يرفع فيها الطالب مشروعه الأكاديمي، ويراجعه المعلم أو المشرف ويعتمده، ويقدر الزوار يتصفحون المشاريع المعتمدة ويحملونها ويعلقون عليها.

---

## Features

### For students
- Register an account and sign in
- Upload a project with a title, description, and attached file (PDF / ZIP / RAR)
- Track the review status of each submission: `pending` → `approved` / `rejected`

### For teachers & admins
- Dashboard with an overview of the system
- Review submitted projects and approve or reject them
- Manage users — change roles (`student` / `teacher` / `admin`) and activate or deactivate accounts
- Moderate comments
- Generate quick reports

### For visitors
- Browse approved projects
- View project details and download attached files
- Leave a comment on a project

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural, no framework) |
| Database | MySQL / MariaDB |
| Frontend | HTML, CSS, vanilla JavaScript |
| Server | Apache (XAMPP / WAMP / LAMP) |

---

## Database Schema

The database is `student_projects_db` (UTF-8, `utf8mb4`) and contains three tables:

### `users`
| Column | Type | Notes |
|---|---|---|
| `id` | INT | Primary key, auto increment |
| `name` | VARCHAR(100) | |
| `email` | VARCHAR(100) | Unique |
| `password` | VARCHAR(255) | Stored hashed |
| `role` | ENUM | `student` · `teacher` · `admin` — defaults to `student` |
| `status` | ENUM | `active` · `inactive` — defaults to `active` |
| `created_at` | TIMESTAMP | |

### `projects`
| Column | Type | Notes |
|---|---|---|
| `id` | INT | Primary key |
| `user_id` | INT | Foreign key → `users(id)`, cascade on delete |
| `title` | VARCHAR(255) | |
| `description` | TEXT | |
| `file_path` | VARCHAR(255) | Path to the uploaded file |
| `status` | ENUM | `pending` · `approved` · `rejected` — defaults to `pending` |
| `created_at` | TIMESTAMP | |

### `comments`
| Column | Type | Notes |
|---|---|---|
| `id` | INT | Primary key |
| `project_id` | INT | Foreign key → `projects(id)`, cascade on delete |
| `name` | VARCHAR(100) | Commenter name |
| `email` | VARCHAR(150) | Commenter email |
| `comment` | TEXT | |
| `created_at` | TIMESTAMP | |

---

## Project Structure

```
student-projects-portal/
│
├── config.php                  # Database connection settings
├── functions.php               # Shared helper functions
├── topbar.php                  # Shared navigation bar
│
├── index.php                   # Login page / entry point
├── register.php                # Registration form
├── process_login.php           # Handles login submission
├── process_register.php        # Handles registration submission
├── reset_password.php          # Password reset
├── logout.php                  # Ends the session
│
├── home.php                    # Public listing of projects
├── view_project.php            # Single project page
├── upload_project.php          # Project submission form
├── download.php                # Serves project file downloads
│
├── add_comment.php             # Adds a comment to a project
├── delete_comment.php          # Removes a comment
│
├── dashboard.php               # Admin / teacher dashboard
├── manage_projects.php         # Approve or reject submissions
├── manage_users.php            # Manage accounts and roles
├── manage_comments.php         # Comment moderation
├── quick_reports.php           # Summary reports
│
├── install_create_admin.php    # One-time setup: creates the first admin
├── student_projects_db.sql     # Database schema
│
├── style.css                   # Stylesheet
└── script.js                   # Client-side scripts
```

---

## Getting Started

### Requirements
- PHP 7.4 or newer
- MySQL 5.7 / MariaDB 10.x
- Apache (XAMPP is the easiest way to get all three)

### Installation

1. **Clone the repository into your web root**

   ```bash
   git clone https://github.com/lolo67fa/student-projects-portal.git
   ```

   Place it inside `htdocs/` (XAMPP) or `www/` (WAMP).

2. **Create the database**

   Open phpMyAdmin, or run from the terminal:

   ```bash
   mysql -u root -p < student_projects_db.sql
   ```

3. **Configure the connection**

   Open `config.php` and set your database credentials:

   ```php
   $host = 'localhost';
   $user = 'root';
   $pass = '';
   $db   = 'student_projects_db';
   ```

4. **Create the uploads folder**

   ```bash
   mkdir uploads
   ```

   Make sure the web server has write permission to it.

5. **Create the first admin account**

   Visit `http://localhost/student-projects-portal/install_create_admin.php` in your browser.

   > ⚠️ **Delete this file immediately after running it.** Leaving it accessible lets anyone create an admin account.

6. **Open the portal**

   ```
   http://localhost/student-projects-portal/
   ```

---

## User Roles

| Role | Permissions |
|---|---|
| **Student** | Register, upload projects, view own submissions and their status |
| **Teacher** | Review projects, approve or reject them, moderate comments |
| **Admin** | Everything a teacher can do, plus manage users, roles, account status, and reports |

---

## Security Notes

Before deploying this anywhere public:

- **Keep `config.php` out of version control.** Add it to `.gitignore` and commit a `config.example.php` with placeholder values instead.
- **Delete `install_create_admin.php`** once the first admin exists.
- **Keep uploaded files out of the repository.** Add `uploads/` to `.gitignore` — user submissions shouldn't live in Git history.
- **Validate uploads** — restrict allowed file extensions and set a maximum file size.
- **Use prepared statements** for every database query to prevent SQL injection.
- **Escape all output** with `htmlspecialchars()` to prevent XSS, especially in comments.

A suggested `.gitignore`:

```gitignore
config.php
uploads/
*.log
.DS_Store
```

---

## Roadmap

- [ ] Email notifications when a project is approved or rejected
- [ ] Search and filter projects by title, department, or year
- [ ] Pagination on the project listing
- [ ] Categories and tags for projects
- [ ] Arabic / English language toggle
- [ ] Responsive mobile layout

---

## Author

**Ghala Alshreef**

- GitHub: [@lolo67fa](https://github.com/lolo67fa)
- LinkedIn: [ghala-a-670a62380](https://linkedin.com/in/ghala-a-670a62380)


---

## License

Released under the MIT License. Free to use and modify.
