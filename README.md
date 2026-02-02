# Future Homes CMS

A complete Content Management System built with Laravel Filament and React for Future Homes construction company.

## 🏗️ Architecture

- **Backend**: Laravel 11 + Filament v5 Admin Panel
- **Frontend**: React + Vite + Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage with CORS support

## ✨ Features

### Admin Panel (Filament)
- 🏢 **Company Management** - Company information and settings
- 🛠️ **Services Management** - Construction services with descriptions
- 🏗️ **Projects Management** - Project portfolio with image galleries
- 🤝 **Partners Management** - Business partners with logos
- 💬 **Testimonials Management** - Customer reviews and feedback
- 📊 **Statistics Management** - Company stats and achievements
- 🎨 **Designs Management** - Design portfolio with categories
- 📄 **Pages Management** - Static pages with version control
- 📧 **Contact Submissions** - Contact form submissions management
- 👥 **User Management** - Admin users with role-based permissions
- 📋 **Audit Logging** - Complete activity tracking

### Frontend (React)
- 🏠 **Homepage** - Hero section, services overview, stats, testimonials
- 🛠️ **Services Page** - Detailed services with project filtering
- 🏗️ **Projects Page** - Complete project portfolio with image galleries
- 🎨 **Designs Page** - Design showcase with category filtering
- ℹ️ **About Page** - Company information and history
- 📞 **Contact Page** - Contact form with validation
- 📱 **Responsive Design** - Mobile-first approach
- 🌐 **RTL Support** - Full Arabic language support
- ⚡ **Performance Optimized** - Lazy loading, image optimization

## 🚀 Deployment Instructions

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Web server (Apache/Nginx)

### Production Deployment

1. **Clone the repository**:
   ```bash
   git clone https://github.com/najialii/futurehomes.git
   cd futurehomes
   git checkout laravel-cms
   ```

2. **Build the frontend**:
   ```bash
   cd futurehomes
   npm install
   npm run build
   cd ..
   ```

3. **Setup the backend**:
   ```bash
   cd backend
   composer install --optimize-autoloader --no-dev
   cp .env.production .env
   # Edit .env with your database credentials
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Web Server Configuration**:

   **For Apache (.htaccess)**:
   ```apache
   # Frontend (React) - Point domain root to futurehomes/dist
   DocumentRoot /path/to/your/project/futurehomes/dist
   
   # Backend (Laravel) - Create alias for /admin and /api
   Alias /admin /path/to/your/project/backend/public
   Alias /api /path/to/your/project/backend/public
   ```

   **For Nginx**:
   ```nginx
   server {
       listen 80;
       server_name futurehomessa.com;
       
       # Frontend (React)
       location / {
           root /path/to/your/project/futurehomes/dist;
           try_files $uri $uri/ /index.html;
       }
       
       # Backend (Laravel)
       location ~ ^/(admin|api) {
           root /path/to/your/project/backend/public;
           try_files $uri $uri/ /index.php?$query_string;
           
           location ~ \.php$ {
               fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
               fastcgi_index index.php;
               fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
               include fastcgi_params;
           }
       }
   }
   ```

5. **Set Permissions**:
   ```bash
   chmod -R 755 backend/storage
   chmod -R 755 backend/bootstrap/cache
   chown -R www-data:www-data backend/storage
   chown -R www-data:www-data backend/bootstrap/cache
   ```

### 🔐 Admin Access

- **URL**: https://futurehomessa.com/admin
- **Email**: admin@futurehomes.com
- **Password**: admin123

### 🌐 API Endpoints

Base URL: `https://futurehomessa.com/api`

- `GET /services` - All services
- `GET /projects` - All projects
- `GET /services/{id}/projects` - Projects by service
- `GET /partners` - All partners
- `GET /testimonials` - All testimonials
- `GET /designs` - All designs
- `GET /designs/category/{category}` - Designs by category
- `GET /stats` - Company statistics
- `GET /pages` - Static pages
- `POST /contact` - Contact form submission

## 🛠️ Development

### Local Development Setup

1. **Backend**:
   ```bash
   cd backend
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   php artisan serve
   ```

2. **Frontend**:
   ```bash
   cd futurehomes
   npm install
   npm run dev
   ```

### 📁 Project Structure

```
├── backend/                 # Laravel Filament CMS
│   ├── app/
│   │   ├── Filament/       # Admin panel resources
│   │   ├── Http/           # Controllers and API
│   │   ├── Models/         # Eloquent models
│   │   └── ...
│   ├── database/           # Migrations and seeders
│   └── public/             # Laravel public directory
├── futurehomes/            # React frontend
│   ├── src/                # React components
│   ├── public/             # Static assets
│   └── dist/               # Built frontend (after npm run build)
└── README.md
```

## 🔧 Configuration

### Environment Variables

Update these in your production `.env` file:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://futurehomessa.com

DB_HOST=your_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
```

## 📞 Support

For deployment assistance or issues, contact the development team.

---

**Built with ❤️ for Future Homes Construction Company**