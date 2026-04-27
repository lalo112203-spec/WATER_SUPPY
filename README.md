# 🚰 Water Billing & Management System

A premium, modern, and efficient Water Billing Management System built with **Laravel 12**, **Livewire**, and **Flux**. Designed for local water districts to manage consumers, billing cycles, and announcements with a stunning UI.

## ✨ Features

- **📊 Dynamic Dashboard**: Real-time stats, revenue charts, and consumption trends.
- **👥 Customer Management**: Easy tracking of consumers with unique **Account Numbers**.
- **🧾 Automated Billing**: Generate bills based on meter readings with support for commercial and regular rates.
- **💬 Messaging System**: Built-in chat for consumer-admin communication and global announcements.
- **🔐 Secure Authentication**: Multi-role support (Admin/Consumer) with password recovery and registration codes.
- **🗑️ Data Recovery**: Soft-delete system with a "Trash" area to restore accidental deletions.
- **🌓 Adaptive UI**: Premium design with support for Light, Dark, and Custom background themes.

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (or your preferred DB)

### Installation

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd water_system
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**:
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

5. **Build Assets**:
   ```bash
   npm run build
   ```

6. **Run Locally**:
   ```bash
   php artisan serve
   ```

## 🛠️ Configuration

System-wide settings like water rates and alert thresholds can be managed through the **Settings** panel by authorized administrators.

- **Regular Rate**: Base charge and consumption thresholds for household users.
- **Commercial Rate**: Adjusted rates for business entities.
- **Additional Charges**: Manage global fees like "Environmental Fees" easily.

## 📱 Mobile Companion

The system includes a Flutter-based mobile portal (`/app_portal`) for consumers to check their bills and announcements on the go.

## 📄 License

This project is licensed under the MIT License.