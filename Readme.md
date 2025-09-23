# Clothing Website using 3-Tier Architecture

A simple clothing store web app where users can browse/search items, view details, add to cart, and place orders. The infrastructure is deployed using a 3-tier architecture on AWS: using VPC, EC2, and RDS.

---

## Table of Contents

- [Architecture](#architecture)  
- [Tech Stack](#tech-stack)  
- [Folder Structure](#folder-structure)  
- [Setup Instructions](#setup-instructions)  
  - [Prerequisites](#prerequisites)  
  - [AWS Infrastructure](#aws-infrastructure)  
  - [Deploying Application](#deploying-application)  
- [Usage](#usage)  
- [Screenshot](#screenshot)  
- [Contributing](#contributing)  
- [License](#license)

---

## Architecture

Here’s the high-level 3-tier architecture of this project:

      Internet
         ↓
 +-----------------+
 |   Public Subnet → Web Server (EC2)   |
 +-----------------+
         ↓             ← HTTP traffic
 +-----------------+
 | Private Subnet → App Server (EC2)    |
 +-----------------+
         ↓             ← Application traffic
 +-----------------+
 | Private Subnet → RDS (MySQL)          |
 +-----------------+

- **Presentation Layer**: Public EC2 instance hosting HTML/CSS/Front-end (index.html, shop.php) accessible over the internet.  
- **Application Layer**: Private EC2 (if used) handling business logic / form handling / processing.  
- **Database Layer**: RDS MySQL instance in private subnet; only accessible from the App layer.

Other components:  
- VPC with 3 subnets: public, private application, private database.  
- Security groups to restrict access: only necessary ports, minimal exposure.  
- No public access for RDS; private instances have no public IP.  
- NAT gateway or other mechanism if private instances need outbound internet (for updates) — (if configured).

---

## Tech Stack

| Component        | Technology                     |
|------------------|----------------------------------|
| Front-end        | HTML, CSS                     |
| Back-end         | PHP                            |
| Database         | MySQL (RDS)                   |
| Hosting / Infra  | AWS (VPC, EC2, RDS)            |
| Local development| VS Code                        |

---

## Folder Structure

/
├── Images/
│ └── (screenshots, product images)
├── index.html # main page / homepage
├── shop.php # product listing + add to cart
├── order.php # view cart & place order
├── style.css # styles for pages
├── sql-setup/ # schema + sample data for DB
│ └── schema.sql
└── README.md # this file

---

## Setup Instructions

### Prerequisites

- AWS account  
- IAM user with sufficient permissions (EC2, RDS, VPC, Security Groups)  
- SSH key pair for EC2  
- MySQL client (optional, for local access/checks)  

### AWS Infrastructure (3-Tier Setup)

1. **Create VPC**  
   - Name: e.g. `clothing-vpc`  
   - CIDR block: `10.0.0.0/16`

2. **Create Subnets**  
   - Public Subnet (e.g. `10.0.1.0/24`) for Web Tier  
   - Private Subnet for App Tier (if separate) (e.g. `10.0.2.0/24`)  
   - Private Subnet for Database Tier (e.g. `10.0.3.0/24`)

3. **Internet Gateway & Route Tables**  
   - Attach an Internet Gateway (IGW) to the VPC  
   - Public Route Table → route `0.0.0.0/0` to IGW, associated with Public Subnet  
   - Private Route Table(s) → no public routes (unless using NAT for outbound)

4. **Security Groups**  
   - Web EC2 SG: allow HTTP (80) from anywhere; SSH (22) from your IP  
   - App EC2 SG: allow traffic only from Web SG; SSH from Web (if using bastion)  
   - RDS SG: only allow DB port (e.g. 3306) from App SG

5. **EC2 Instances**  
   - Launch Web EC2 in Public Subnet, assign public IP, install web server (Apache / PHP)  
   - If using separate App instance, place in Private Subnet  

6. **RDS Instance**  
   - MySQL (or other) in private DB subnet  
   - Disable Public Accessibility  
   - Use subnet group covering DB subnet(s)

7. **(Optional) NAT Gateway**  
   - For private instances to access internet (updates etc.)  
   - Deployed in public subnet; configured in private route tables

