# RADIUS Admin Panel

Web-based RADIUS management system untuk ISP dan hotspot.

## 🚀 Quick Setup

### Requirements
- PHP 7.0+
- MySQL 5.0+
- Apache/Nginx
- mysqli extension

### Installation

1. **Clone Repository**
```bash
git clone https://github.com/username/radius-admin-panel.git
cd radius-admin-panel
```

2. **Setup Database**
```bash
# Start MySQL service
# Create database
mysql -u root -p
CREATE DATABASE radius;

# Import data
mysql -u root -p radius < contrib/db/mysql-daloradius.sql
```

3. **Configure Database**
Edit `library/daloradius.conf.php`:
```php
$configValues['CONFIG_DB_HOST'] = 'localhost';
$configValues['CONFIG_DB_USER'] = 'root';
$configValues['CONFIG_DB_PASS'] = 'your_password';
$configValues['CONFIG_DB_NAME'] = 'radius';
```

4. **Start Web Server**
- Start Apache
- Access: http://localhost/radius-admin-panel

5. **Login**
- Username: `administrator`
- Password: `radius`

## 📁 Project Structure

```
radius-admin-panel/
├── library/           # Core files
├── css/              # Stylesheets
├── images/           # Images & icons
├── lang/             # Language files
├── include/          # Include files
├── contrib/          # Additional tools
└── README.md         # This file
```

## 🔧 Development

### Setup Development Environment
1. Install VS Code
2. Install PHP extensions
3. Run `php setup_project.php` to check requirements

### Git Workflow
```bash
# Stage changes
git add .

# Commit
git commit -m "Update feature"

# Push
git push origin main
```

## 📞 Support

Untuk bantuan setup atau development, hubungi tim development.

## 📄 License

GPL v2 - Open Source
