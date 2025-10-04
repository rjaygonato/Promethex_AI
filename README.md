# Simple Product Catalog API

A RESTful API built with PHP and MySQL to manage a simple product catalog.

## Features

- **GET /products** → Returns JSON list of products (id, name, price)
- **POST /products** → Accepts JSON `{ "name": "Product Name", "price": 100 }` and adds a product
- Input validation: no empty names, price must be numeric
- Products stored in MySQL

## Setup Instructions

1. Clone the repository or unzip the files.
2. Import `schema.sql` into MySQL:
