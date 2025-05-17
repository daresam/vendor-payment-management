
# Vendor Payment Management

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Architecture](#architecture)
- [Installation](#installation)

## Introduction

This project is a microservices plus webpage application built using Laravel. It is designed for managing
corporates, their vendors, creation of invoices against one or more vendors, tracking of a vendor's
invoice and finally marking an invoice as paid. The application leverages various Laravel packages and tools such as Laravel Sanctum, and RabbitMQ for secure communication, performance optimization, and asynchronous processing.



## Features

### Microservice Application:
- User authentication with Laravel Sanctum.
- Corporate management.
- Vendor management.
- Invoice management.
- API Gateway for routing and security.
- Frontend for client side.
- Postman Collection.
- Architecture Design.

## Technologies Used

- **Laravel Framework**: A PHP framework for building web applications.
- **Laravel Sanctum**: Provides token-based authentication for API security.
- **Laravel Sail**: For bootstrappi g the.
- **RabbitMQ**: Message broker for asynchronous task handling.
- **Docker**: Containerization for consistent development environments.

## Architecture for the microservices

This application follows a microservices architecture, with separate services for managing corporates, vendors, invoices, and user authentication. Each service is developed as an independent Laravel application, with its own database and logic, communicating through a central API Gateway.

![image](https://res.cloudinary.com/dpojtzqgd/image/upload/v1747486915/Vendor_Payment_Management_g6gc7m.jpg)


## Installation

To start the microservice application:

1. Unzip the folder **vendor-payment-management.zip**.
2. Navigate to each of the following directories(api-gateway, corporate, vendor and invoice )
3. Run `./vendor/bin/sail up -d` to start the containers for each services.
4. Run `./vendor/bin/sail artisan migrate` to run migration for each services
5. Run `./vendor/bin/sail artisan db:seed` to run migration for each services
6. Navigate to Start rabbitmq by running `docker compose up`.
7. Test the application using Postman, and connect to the database using your preferred tool (ensure the correct database port is used).

## To start the frontend application:
1. Navigate to the **frontend** directory.
3. Run `composer dev` to start the app.


## Automation Test

1. Navigate to each of the following directories(corporate, vendor and invoice )
2. Corporate Service: Run `./vendor/bin/sail artisan test --filter=CorporateTest`

3. Vendor Service: Run `./vendor/bin/sail artisan test --filter=VendorTest`

4. Invoice Service: Run `./vendor/bin/sail artisan test --filter=InvoiceTest`

### Prerequisites

- Docker and Docker Compose
- PHP 8.x
- Composer
