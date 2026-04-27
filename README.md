# Multi-Tenant E-Commerce API (Laravel, DDD, TDD)

## 🚀 Overview

This project is a multi-tenant e-commerce backend built with Laravel, focusing on clean architecture, scalability, and test-driven development.

It demonstrates how to design a domain-based multi-tenant system where each store operates in isolation while sharing the same codebase. The application follows Domain-Driven Design (DDD) principles and is fully covered by feature tests.

---

## Architecture & Design

### Domain-Driven Design (DDD)

The project is structured to separate concerns and keep business logic maintainable:

* **Actions** – Encapsulate and resuable business logic
* It lets to resua piece of logic in different situations (commands, jobs, controllers)
* **DTOs** – Data transfer objects using Spatie Laravel Data package
* An immutable object which each field has own type and helps to transfer data 
structural way.
* **ViewModels** – Consistent API responses
* It is a place where all necessary data of a specific view are produced and formatted.
* **Custom Exceptions** – Clean error handling
* It helps to return typed errors which helps to decide what types of response should be returned.

---

### Multi-Tenancy Strategy

A domain-based tenancy approach is implemented:

* Store resolution based on request domain
* Session isolation per tenant to prevent data leakage
* Ownership validation via middleware and authorization gates

Key components:

* `EntityStoreOwnershipChecker` middleware
- It is used in store owner panel
* `DomainStoreResolverAction`
- It is used in public shop pages to identify request domain
* Session prefixing per store domain
- Using `SessionAction` which is a wrapper class to centeralize session separation for tenants.

---

## 🧪 Testing Strategy (TDD)

The project follows a **Test-Driven Development (TDD)** approach:

* Feature tests cover all critical flows
* Tests validate tenant isolation and ownership rules
* API behavior is verified before implementation

Covered scenarios include:

* Product creation & updating
* File & image uploads
* Store ownership enforcement
* Store data isolation and leakage
* Cart operations
* Shop product listing & access control

---

## 📦 Core Features

### Product Management

* Create & update products (Upsert pattern)
* Product variants with dynamic attributes
* SEO fields support
* Soft delete functionality

---

### File & Image Handling

* File upload system with storage capacity handling
* Public file access via `public_id`
* Product image uploads

---

### Multi-Tenant Store System

* Domain-based store resolution
* Tenant-isolated sessions
* Ownership-based authorization

---

### Shopping Cart

* Session-based cart per tenant
* Add & update cart items

---

### Shop API

* Product listing & search
* Single product view
* Secure route model binding for tenant access

---

## 🔐 Security & Authorization

* Store ownership enforced via policies and middleware
* Prevents cross-tenant data access
* Controlled resource access using route model binding
* Using sanctum for authentication layer allows using different frontend frameworks like `NextJs` 
---

## ⚙️ Tech Stack

* Laravel 12
* PHP
* MySQL
* Laravel Sanctum (API Authentication)
* Spatie Laravel Data (DTOs)
* Nginx
* Docker (Laravel Sail)

---

## 📁 Project Structure

The project follows a modular structure aligned with DDD:

* Each `Domain` has same schema:
* `Actions/` – Business logic
* `DTOs/` – Data transfer objects and data validation
* `ViewModels/` – API response formatting
* `Models/` – Data layer
* `Exceptions/` Custom error types
* `Enums/` - they are often used as `factory class` or `value objects`

---

## 💡 Key Engineering Decisions

* **Upsert Pattern**: Simplifies product create/update logic
* **Domain-Based Tenancy**: Cleaner and scalable tenant separation
* **Session Isolation**: Prevents cross-tenant data leakage
* **FileUploadBuilder**: Extracted reusable file handling logic
* **Middleware-Based Authorization**: Centralized access control
* **Using public_id(uuid) in critical resources**: helps to prevent indexing attack
---

## 🧭 What This Project Demonstrates

* Designing scalable multi-tenant systems
* Applying Domain-Driven Design in Laravel
* Writing testable and maintainable backend code
* Strong understanding of API architecture and data isolation
* Test-Driven Development in real-world scenarios

---
