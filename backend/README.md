**future improvements**

**backend folder structure suggestion**

backend/
├── config/
│   ├── database.php
│   └── constants.php
├── controllers/
│   ├── AuthController.php
│   ├── VendorController.php
│   ├── InspectionController.php
│   └── ReportController.php
├── models/
│   ├── User.php
│   ├── Vendor.php
│   ├── Inspection.php
│   └── InspectionFinding.php
├── middleware/
│   ├── AuthMiddleware.php
│   ├── ValidationMiddleware.php
│   └── CorsMiddleware.php
├── utils/
│   ├── Response.php
│   ├── Validator.php
│   ├── PasswordHelper.php
│   └── FileUploader.php
├── routes/
│   ├── api.php
│   └── web.php
├── uploads/
│   ├── photos/
│   └── documents/
└── public/
    ├── index.php
    └── .htaccess




