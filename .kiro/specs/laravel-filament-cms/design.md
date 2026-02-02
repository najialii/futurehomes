# Laravel Filament CMS Design Document

## Overview

The Laravel Filament CMS is a comprehensive content management system designed to manage all content for the Future Homes construction company website. The system provides a modern admin interface built with Filament v3, RESTful APIs for the React frontend, and robust content management capabilities including media handling, user permissions, and multilingual support.

The architecture follows Laravel best practices with a clear separation between the admin interface (Filament), API layer (Laravel API Resources), and data layer (Eloquent models). The system is designed to be scalable, maintainable, and user-friendly for content managers.

## Architecture

### System Architecture
The system follows a layered architecture pattern:

1. **Presentation Layer**: Filament Admin Panel + RESTful APIs
2. **Business Logic Layer**: Laravel Services and Controllers
3. **Data Access Layer**: Eloquent Models and Repositories
4. **Database Layer**: MySQL/PostgreSQL with migrations

### Technology Stack
- **Backend Framework**: Laravel 10.x
- **Admin Panel**: Filament v3
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **File Storage**: Laravel Storage (local/S3)
- **Image Processing**: Intervention Image
- **API Documentation**: Laravel API Resources
- **Authentication**: Laravel Sanctum
- **Caching**: Redis (optional)

### Directory Structure
```
backend/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   └── Pages/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   └── Resources/
│   ├── Models/
│   ├── Services/
│   └── Policies/
├── database/
│   ├── migrations/
│   └── seeders/
└── storage/
    └── app/public/
```

## Components and Interfaces

### Core Models

#### Company Model
- Stores company information (name, description, contact details)
- Handles logo and media attachments
- Provides settings configuration

#### Service Model
- Manages service offerings
- Supports rich text descriptions
- Handles service icons and ordering

#### Project Model
- Manages project portfolio
- Supports multiple images per project
- Includes categorization and status

#### Partner Model
- Stores partner information
- Handles logo uploads
- Supports ordering and status

#### Design Model
- Manages design gallery images
- Supports categorization
- Handles image metadata

#### Testimonial Model
- Stores client testimonials
- Supports ratings and approval workflow
- Handles client photos

#### Page Model
- Manages static page content
- Supports rich text editing
- Includes version history

#### ContactSubmission Model
- Stores contact form submissions
- Tracks processing status
- Supports spam filtering

### Filament Resources

Each model will have a corresponding Filament Resource with:
- List views with filtering and searching
- Create/Edit forms with validation
- Bulk actions for management
- Custom actions for specific workflows

### API Controllers

RESTful API controllers for each content type:
- `CompanyController` - Company information
- `ServiceController` - Service listings
- `ProjectController` - Project portfolio
- `PartnerController` - Partner listings
- `DesignController` - Design gallery
- `TestimonialController` - Client testimonials
- `PageController` - Static page content
- `ContactController` - Contact form handling

## Data Models

### Database Schema

#### companies table
```sql
- id (primary key)
- name (string)
- description (text)
- email (string)
- phone (string)
- address (text)
- logo_path (string, nullable)
- website_url (string, nullable)
- social_media (json)
- created_at, updated_at
```

#### services table
```sql
- id (primary key)
- title (string)
- description (text)
- icon_path (string, nullable)
- display_order (integer)
- is_active (boolean)
- created_at, updated_at
```

#### projects table
```sql
- id (primary key)
- name (string)
- description (text)
- service_id (foreign key to services table)
- status (enum: draft, published)
- display_order (integer)
- created_at, updated_at
```

#### project_images table
```sql
- id (primary key)
- project_id (foreign key)
- image_path (string)
- alt_text (string, nullable)
- display_order (integer)
- created_at, updated_at
```

#### partners table
```sql
- id (primary key)
- name (string)
- logo_path (string)
- website_url (string, nullable)
- display_order (integer)
- is_active (boolean)
- created_at, updated_at
```

#### designs table
```sql
- REMOVED: This table is no longer needed as projects are now organized by services
```

#### testimonials table
```sql
- id (primary key)
- client_name (string)
- client_photo_path (string, nullable)
- feedback (text)
- rating (integer, 1-5)
- status (enum: pending, approved, rejected)
- created_at, updated_at
```

#### pages table
```sql
- id (primary key)
- slug (string, unique)
- title (string)
- content (longtext)
- meta_description (string, nullable)
- is_published (boolean)
- created_at, updated_at
```

#### contact_submissions table
```sql
- id (primary key)
- name (string)
- email (string)
- message (text)
- status (enum: new, read, replied, archived)
- ip_address (string)
- user_agent (text)
- created_at, updated_at
```

### Relationships
- Project belongsTo Service
- Service hasMany Projects
- Project hasMany ProjectImages
- Company hasMany Services, Projects, Partners
- All models use soft deletes where appropriate
#
# Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property Reflection

After analyzing all acceptance criteria, several properties can be consolidated to eliminate redundancy:

- File upload properties (1.4, 2.4, 4.2, 6.3) can be combined into a single comprehensive file upload property
- API ordering properties (2.2, 4.3) can be combined into a general ordering property
- Validation properties (1.5, 4.4, 6.4) can be combined into input validation property
- Status filtering properties (4.5, 6.2) can be combined into status-based filtering property

### Core Properties

**Property 1: Data persistence consistency**
*For any* content type (company, service, project, partner, design, testimonial, page, contact submission), when data is created or updated through the CMS, the changes should be immediately reflected in both the database and API responses
**Validates: Requirements 1.2, 2.1, 3.1, 4.1, 5.1, 6.1, 7.1, 10.1**

**Property 2: File upload and URL generation**
*For any* file upload (logos, icons, images, photos), when a file is uploaded through the CMS, it should be stored in the file system and a valid public URL should be generated and accessible
**Validates: Requirements 1.4, 2.4, 4.2, 6.3**

**Property 3: API response format consistency**
*For any* API endpoint, when content is requested, the response should be valid JSON with consistent structure and include all required fields for that content type
**Validates: Requirements 1.3, 3.3, 5.2, 8.2**

**Property 4: Input validation enforcement**
*For any* input field with validation rules (email, phone, URL, rating), when invalid data is submitted, the system should reject the input and preserve the existing valid state
**Validates: Requirements 1.5, 4.4, 6.4, 9.5**

**Property 5: Content ordering preservation**
*For any* content type with display order (services, partners), when content is retrieved via API, it should be returned sorted by the display order field
**Validates: Requirements 2.2, 4.3**

**Property 6: Status-based filtering**
*For any* content with status fields (active/inactive, published/draft, approved/pending), when content is retrieved via public API, only items with active/published/approved status should be included
**Validates: Requirements 3.5, 4.5, 6.2**

**Property 7: Content deletion cleanup**
*For any* content item that is deleted, both the database record and any associated files should be completely removed from the system
**Validates: Requirements 2.3, 5.5**

**Property 8: Image processing consistency**
*For any* image upload, when an image is uploaded, both the original image and thumbnail versions should be created and stored
**Validates: Requirements 3.2**

**Property 9: HTML content sanitization**
*For any* rich text content, when HTML is submitted, it should be sanitized to remove potentially dangerous elements while preserving safe formatting
**Validates: Requirements 2.5**

**Property 10: Service-based project filtering**
*For any* service, when projects are filtered by service, only projects belonging to that specific service should be returned
**Validates: Requirements 5.3**

**Property 11: Permission-based access control**
*For any* user attempting to access restricted functionality, the system should enforce role-based permissions and deny access to unauthorized operations
**Validates: Requirements 9.2**

**Property 12: Version history maintenance**
*For any* page content that is edited, the system should maintain a complete history of all versions while serving the current published version via API
**Validates: Requirements 7.1, 7.2**

**Property 13: Audit logging completeness**
*For any* sensitive operation (user creation, content deletion, permission changes), the system should create audit log entries with complete details including user, timestamp, and action performed
**Validates: Requirements 9.4**

**Property 14: Contact submission status management**
*For any* contact form submission, when the status is changed (new, read, replied, archived), the change should be persisted and reflected in the admin interface
**Validates: Requirements 10.3**

**Property 15: Pagination metadata accuracy**
*For any* paginated API response, when pagination parameters are provided, the response should include accurate metadata (total count, current page, total pages) and the correct subset of results
**Validates: Requirements 8.4**

## Error Handling

### Validation Errors
- Input validation failures return structured error responses with field-specific messages
- File upload errors include specific reasons (file too large, invalid format, etc.)
- Database constraint violations are caught and converted to user-friendly messages

### API Error Responses
- Consistent error response format across all endpoints
- Appropriate HTTP status codes (400, 401, 403, 404, 422, 500)
- Error logging for debugging and monitoring

### File Handling Errors
- Graceful handling of file system errors
- Automatic cleanup of partial uploads on failure
- Fallback mechanisms for image processing failures

### Database Errors
- Transaction rollback on complex operations
- Connection retry logic for temporary failures
- Data integrity preservation during errors

## Testing Strategy

### Dual Testing Approach

The system will implement both unit testing and property-based testing to ensure comprehensive coverage:

**Unit Testing:**
- Specific examples that demonstrate correct behavior
- Integration points between Filament resources and API controllers
- Edge cases and error conditions
- Authentication and authorization workflows

**Property-Based Testing:**
- Universal properties that should hold across all inputs using PHPUnit with Pest framework
- Each property-based test will run a minimum of 100 iterations
- Property-based tests will use Faker for generating random test data
- Each property-based test will be tagged with comments referencing the design document property

**Property-Based Testing Library:**
- **Pest PHP** with **Faker** for generating random test data
- Custom generators for domain-specific data (valid emails, image files, HTML content)
- Property tests will be configured to run 100+ iterations each

**Test Tagging Format:**
Each property-based test must include a comment with this exact format:
`**Feature: laravel-filament-cms, Property {number}: {property_text}**`

**Testing Requirements:**
- Each correctness property must be implemented by a single property-based test
- Unit tests cover specific examples, edge cases, and integration points
- Property tests verify universal properties across all valid inputs
- Both test types are complementary and required for comprehensive coverage

### Test Categories

**Model Tests:**
- Data validation and relationships
- Soft delete functionality
- Scope and accessor methods

**API Tests:**
- Endpoint availability and response formats
- Authentication and authorization
- Pagination and filtering
- Error handling

**Filament Resource Tests:**
- CRUD operations through admin interface
- File upload functionality
- Permission enforcement
- Bulk operations

**Integration Tests:**
- End-to-end workflows
- File storage and retrieval
- Email notifications
- Cache invalidation

### Performance Testing
- API response time benchmarks
- File upload performance limits
- Database query optimization validation
- Memory usage during bulk operations