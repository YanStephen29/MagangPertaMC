# Web Management Data Perta MC

A comprehensive Laravel-based web application for managing project data, tools, documents, and request workflows at Perta MC. This system streamlines the process of handling project tools, document assignments, and approval workflows.

## 🚀 Features

### Core Functionality
- **Project Management**: Create and manage projects with unique I/O numbers
- **Tool Management**: Add, edit, and track tools with detailed specifications
- **Document Assignment**: Bulk and individual assignment of tools to documents
- **Request Workflow**: Multi-stage approval process with status tracking
- **User Authentication**: Secure login system with role-based access
- **Search & Filter**: Advanced filtering by document, status, and keywords

### Key Modules
1. **Projects Module**
   - Project creation with title and I/O number
   - Project-specific tool management
   - Comprehensive project overview

2. **Tools Management**
   - Detailed tool specifications (description, quantity, unit, GL code)
   - Delivery date tracking
   - Bulk operations support
   - Advanced search and filtering

3. **Document Management**
   - Document creation and assignment
   - Multi-stage workflow tracking
   - Status monitoring and updates
   - Progress percentage calculation

4. **Request System**
   - Request creation and processing
   - Status tracking (pending, approved, rejected, etc.)
   - Approval workflow management
   - Request history and audit trail

5. **Bidang (GL Code) Management**
   - GL code categorization
   - Financial tracking integration

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 11.x
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Testing**: Pest PHP
- **Code Quality**: PHP CS Fixer (Pint)

### Frontend
- **CSS Framework**: Tailwind CSS
- **Build Tool**: Vite
- **JavaScript**: Vanilla JS with modern ES6+ features
- **Icons**: Heroicons (SVG)

### Development Tools
- **Package Manager**: Composer (PHP), NPM (JavaScript)
- **Version Control**: Git
- **Local Development**: Laragon/XAMPP compatible

## 📋 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Web server (Apache/Nginx)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/YanStephen29/MagangPertaMC.git
   cd webManageDataPertaMC
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   - Create a MySQL database
   - Update `.env` file with database credentials:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 📖 Usage

### Getting Started
1. Register a new account or login with existing credentials
2. Create your first project with a unique I/O number
3. Add tools to your project with detailed specifications
4. Create documents and assign tools to them
5. Manage the approval workflow through the request system

### Key Workflows

#### Tool Management
- Navigate to a project to view all associated tools
- Use the "Add Request" button to create new tool entries
- Use bulk assignment to assign multiple tools to documents
- Filter and search tools using the advanced search functionality

#### Document Assignment
- **Individual Assignment**: Click "Assign Document" on unassigned tools
- **Bulk Assignment**: 
  1. Click "Assign to Document" to enter bulk mode
  2. Select multiple tools using checkboxes
  3. Choose a document from the dropdown
  4. Confirm the bulk assignment

#### Request Processing
- Documents progress through multiple stages (Tahapan)
- Each stage has specific requirements and approvals
- Status colors indicate current progress:
  - Gray: Unprocessed
  - Blue: In Progress
  - Green: Approved
  - Red: Rejected

### User Interface Features
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Real-time Updates**: Status changes reflect immediately
- **Intuitive Navigation**: Clear breadcrumbs and navigation paths
- **Advanced Filtering**: Multi-criteria search and filter options
- **Bulk Operations**: Efficient handling of multiple items

## 🗄️ Database Schema

### Main Tables
- `projects`: Store project information and I/O numbers
- `tools`: Tool specifications and quantities
- `documents`: Document management and assignments
- `requests`: Request workflow and approvals
- `tahapans`: Workflow stages and progress tracking
- `bidangs`: GL code categories and financial tracking
- `users`: User authentication and profiles

### Key Relationships
- Projects → Tools (One-to-Many)
- Tools → Documents (Many-to-One)
- Documents → Requests (One-to-One)
- Documents → Tahapans (One-to-Many)
- Tools → Bidangs (Many-to-One)

## 🛣️ API Endpoints

### Main Routes
- `GET /projects` - List all projects
- `GET /projects/{project}/tools` - Project-specific tools
- `POST /projects/{project}/tools` - Create new tool
- `PATCH /projects/{project}/tools/bulk-assign` - Bulk tool assignment
- `GET /documents` - Document management
- `GET /bidangs` - GL code management

## 💻 Development

### Code Style
The project follows Laravel best practices and PSR standards:
- Use PHP CS Fixer (Pint) for code formatting: `./vendor/bin/pint`
- Follow Laravel naming conventions
- Write descriptive commit messages

### Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run tests with coverage
php artisan test --coverage
```

### Contributing
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/new-feature`
3. Make your changes and commit: `git commit -am 'Add new feature'`
4. Push to the branch: `git push origin feature/new-feature`
5. Submit a pull request

## 📁 Project Structure

```
app/
├── Http/Controllers/     # Application controllers
├── Models/              # Eloquent models
├── Providers/           # Service providers
└── View/Components/     # Blade components

database/
├── migrations/          # Database migrations
├── seeders/            # Database seeders
└── factories/          # Model factories

resources/
├── views/              # Blade templates
├── css/                # Stylesheets
└── js/                 # JavaScript files

routes/
├── web.php             # Web routes
├── auth.php            # Authentication routes
└── console.php         # Console commands
```

## ⚙️ Configuration

### Key Configuration Files
- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/auth.php` - Authentication settings
- `tailwind.config.js` - Tailwind CSS configuration
- `vite.config.js` - Vite build configuration

### Environment Variables
```env
APP_NAME="Web Management Data Perta MC"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webmanage_pertamc
DB_USERNAME=root
DB_PASSWORD=
```

## 🔧 Troubleshooting

### Common Issues

1. **Migration Errors**
   ```bash
   php artisan migrate:refresh --seed
   ```

2. **Asset Build Issues**
   ```bash
   npm run build
   php artisan optimize:clear
   ```

3. **Permission Issues**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

4. **Cache Issues**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

## 📊 Project Statistics

This project includes:
- **188+ files** in the initial commit
- **Comprehensive test suite** with Pest PHP
- **Responsive design** supporting all device sizes
- **Multi-language support ready** (ID/EN)
- **Advanced security features** with Laravel Breeze

## 🎯 Future Enhancements

- [ ] API documentation with Swagger/OpenAPI
- [ ] Real-time notifications with WebSockets
- [ ] Advanced reporting and analytics
- [ ] Mobile app integration
- [ ] Enhanced file upload and management
- [ ] Multi-tenant support

## 📄 License

This project is developed for Perta MC internal use. All rights reserved.

## 🤝 Support

For support and questions regarding this project, please contact the development team or create an issue in the repository.

## 📈 Changelog

### Version 1.0.0 (October 2025)
- ✅ Initial release with core functionality
- ✅ Project and tool management
- ✅ Document assignment system
- ✅ Request workflow implementation
- ✅ User authentication and authorization
- ✅ Responsive web interface
- ✅ Database optimization and cleanup
- ✅ Git repository setup with proper branching

---

**Developed with ❤️ for Perta MC** | **Powered by Laravel 11.x**
