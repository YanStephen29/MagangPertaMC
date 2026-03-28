# Sistem Request Management PT Pertamina MC

## Overview
Sistem ini adalah aplikasi web untuk mengelola data request project PT Pertamina Maintenance & Construction. Sistem ini memiliki workflow yang kompleks sesuai dengan proses bisnis perusahaan.

## Alur Sistem (Workflow)

### 1. Pembuatan Project (Event)
- Setiap project memiliki **nomor I/O** yang unik
- Project dapat memiliki multiple request items (barang/jasa)

### 2. Request Items
Setiap request item dalam project akan melalui tahapan berikut:

#### A. Informasi Dasar Request
- **Description**: Deskripsi barang/jasa yang direquest
- **Quantity & Unit**: Jumlah dan satuan
- **Bidang Request Type**:
  - Material Request
  - Service Request  
  - Facility Request
  - Asset Request

#### B. Dokumen Request
- **Document Number**: Nomor dokumen sesuai bidang pilihan
- **Document Date**: Tanggal masuknya dokumen

#### C. Workflow Stages (3 Tahapan Wajib)
1. **Project to EPC**: Project team ke EPC
2. **PMO to EPC**: PMO ke EPC  
3. **EPC to Procurement**: EPC ke Procurement

*Setiap tahapan harus diisi tanggalnya untuk tracking progress*

#### D. Request Type (Setelah Workflow)
Setelah melalui 3 tahapan, akan masuk ke type request:
- **SPS** (Surat Permintaan Spesifikasi)
- **PO** (Purchase Order)
- **PCM** (Procurement Contract Management)

Setiap type request memiliki:
- Nomor dokumen otomatis berdasarkan type
- Tanggal keluar dokumen

#### E. Vendor/Toko Information
- **Nama Toko/Vendor**: Supplier barang/jasa
- **Harga Satuan**: Price per unit
- **Total Harga**: Otomatis calculate dari qty x harga satuan
- **Payment PO/PCM**: Pembayaran number (integer)
- **Status Pembayaran**: 
  - Pending
  - Paid
  - Partial
  - Cancelled

## Database Structure

### Tabel Utama

1. **events**
   - `no_I/O` (PK): Nomor I/O project
   - `title`: Judul project

2. **request_items** 
   - `id` (PK): Auto increment
   - `event_no_io` (FK): Link ke events table
   - `description`: Deskripsi item
   - `quantity`, `unit`: Jumlah dan satuan
   - `bidang_req_type`: ENUM (material_req, service_req, facility_req, aset_req)
   - `document_number`, `document_date`: Info dokumen
   - `project_to_epc_date`: Tanggal tahap 1
   - `pmo_to_epc_date`: Tanggal tahap 2  
   - `epc_to_procurement_date`: Tanggal tahap 3
   - `request_type`: ENUM (SPS, PO, PCM)
   - `request_type_doc_number`, `request_type_doc_date`: Dokumen type request
   - `vendor_name`: Nama vendor/toko
   - `price`, `total_price`: Harga satuan dan total
   - `payment_po_pcm`: Nomor pembayaran
   - `payment_status`: Status pembayaran
   - `status`: Status item (draft, in_progress, completed, cancelled)
   - `remarks`: Catatan

3. **workflow_stages**
   - Master data untuk tahapan workflow

4. **request_types**  
   - Master data untuk jenis request type

5. **vendors**
   - Master data vendor/toko

### Tabel Legacy (Masih Digunakan)
- **bidangs**: Data bidang/departemen
- **tools**: Sistem lama (akan digantikan request_items)
- **documents**: Data dokumen

## Fitur Sistem

### 1. Dashboard Events
- Daftar semua project (events)
- Quick stats dan overview
- Search dan filter

### 2. Request Items Management
- **List View**: Tabel lengkap dengan filter
  - Search by description, No I/O, document, vendor
  - Filter by status, bidang request, request type
  - Progress workflow dalam bentuk percentage bar
  
- **Create Form**: Form lengkap untuk input request item baru
  - Auto calculate total price (qty x price)
  - Dropdown untuk semua pilihan
  - Validation lengkap
  
- **Detail View**: Tampilan lengkap informasi request item
  - Progress workflow visual dengan stage indicators
  - Form update workflow stage
  - Informasi vendor dan pricing
  - Actions (edit, delete, view event)

### 3. Workflow Management
- Visual progress tracking (percentage bar)
- Update individual workflow stages
- Timeline view untuk setiap tahapan

### 4. Integration dengan Event
- Link langsung create request item dari event detail
- View all request items per event
- Backward compatibility dengan tools lama

## Menu Navigation
- **Home**: Dashboard events
- **Request Items**: Management request items baru  
- **Daftar Bidang**: Management bidang/departemen

## Status Tracking

### Request Item Status
- **Draft**: Baru dibuat, belum diproses
- **In Progress**: Sedang dalam proses workflow
- **Completed**: Selesai sampai vendor
- **Cancelled**: Dibatalkan

### Payment Status
- **Pending**: Belum bayar
- **Paid**: Lunas
- **Partial**: Bayar sebagian
- **Cancelled**: Dibatalkan

### Workflow Progress
Progress dihitung otomatis berdasarkan tahapan yang sudah completed:
- 0%: Belum ada tahapan
- 33%: 1 tahapan completed  
- 67%: 2 tahapan completed
- 100%: Semua tahapan completed

## Technology Stack
- **Backend**: Laravel 11, PHP 8+
- **Database**: MySQL
- **Frontend**: Bootstrap 5, FontAwesome icons
- **Features**: Responsive design, AJAX interactions

## File Struktur Penting

### Controllers
- `RequestItemController`: CRUD request items
- `EventController`: Management events  
- `BidangController`: Management bidangs

### Models
- `RequestItem`: Model utama request items
- `Event`: Model projects
- `Vendor`: Model vendors/toko
- `WorkflowStage`, `RequestType`: Master data models

### Views
- `request-items/`: Views untuk request items management
- `events/`: Views untuk events management
- `layouts/management.blade.php`: Layout utama

### Migrations
- `create_request_items_table`: Tabel utama request items
- `add_columns_to_request_items_table`: Kolom lengkap request items
- `create_workflow_stages_table`: Master workflow stages
- `create_request_types_table`: Master request types  
- `create_vendors_table`: Master vendors

Sistem ini dirancang untuk menggantikan sistem tools lama dengan workflow yang lebih lengkap dan sesuai dengan proses bisnis PT Pertamina MC.
