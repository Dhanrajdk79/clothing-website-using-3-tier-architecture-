# clothing-website-using-3-tier-architecture-
The goal of this project is to provide a basic online platform where users can search for clothing items and view details such as name and price.   As the Cloud Engineer on this project, I was responsible for designing and deploying the entire infrastructure on AWS. The application is hosted on an Amazon EC2 instance, RDS as its database service. 

##Architecture 
Morya-Fashion-Hub/
│
├── index.html          # Main entry point (loads shop.php via iframe)

├── style.css           # Basic styling for products & layout

├── shop.php            # Product listing + Add-to-cart (Session-based)

├── order.php           # View cart + Place order

├── schema.sql          # SQL file to create database & sample products

##Steps for make architecture 

Got it ✅ — here’s a **step-by-step guide** to launch a **3-Tier Architecture** on AWS manually using **VPC, EC2, and RDS** (no auto-scaling).

This is the most common setup:

* **Tier 1 – Presentation Layer:** Public Subnet → EC2 (Web Server)
* **Tier 2 – Application Layer:** Private Subnet → EC2 (App Server)
* **Tier 3 – Database Layer:** Private Subnet → RDS (Database)

---

## 🚀 **Step-by-Step Setup**

### **Step 1: Create VPC & Subnets**

1. Go to **VPC Console → Create VPC**

   * Name: `3tier-vpc`
   * CIDR Block: `10.0.0.0/16`
2. Create **Subnets**:

   * **Public Subnet**  → Name: `public-subnet`
   * **Private Subnet (App)**  → Name: `private-app-subnet`
   * **Private Subnet (DB)**  → Name: `private-db-subnet`
3. Create **Internet Gateway (IGW)**

   * Attach it to `3tier-vpc`
4. Update **Route Tables**:

   * Public Route Table → Add Route → `0.0.0.0/0` → Target: IGW
   * Associate Public Route Table with `public-subnet`
   * Private Route Table → No direct internet access 

---

### **Step 2: Launch Public EC2 (Web Server)**

1. Go to **EC2 Console → Launch Instance**
2. AMI: Amazon Linux 2
3. Instance type: `t2.micro` (free-tier)
4. Network settings:

   * Select VPC: `3tier-vpc`
   * Subnet: `public-subnet`
   * Auto-assign Public IP: ENABLE
5. Security Group:

   * Allow HTTP (80) from `0.0.0.0/0`
   * Allow SSH (22) from your IP
6. Launch → Download Key Pair
---

### **Step 3: Launch Private EC2 (App Server)**

1. Go to **EC2 Console → Launch Instance**
2. Same AMI (Amazon Linux 2)
3. Instance type: `t2.micro`
4. Network settings:

   * VPC: `3tier-vpc`
   * Subnet: `private-app-subnet`
   * Auto-assign Public IP: DISABLE
5. Security Group:

   * Allow SSH (22) from Public EC2 Security Group 
   * Allow Custom TCP  from Public EC2 
6. Launch → Connect via SSH through **Bastion/Jump Host** (your Public EC2)
7. Install Application Server (Node.js, PHP, etc.)

---

### **Step 4: Create Database (RDS)**

1. Go to **RDS Console → Create Database**
2. Engine: MySQL (or PostgreSQL)
3. DB instance class: `db.t3.micro`
4. VPC: `3tier-vpc`
5. Subnet group: Select `private-db-subnet`
6. Security Group:

   * Allow MySQL/Aurora (3306) **only from App Server Security Group**
7. Disable Public Access
8. Create Database


* From there, SSH into Private EC2
* Check DB connection from Private EC2:
