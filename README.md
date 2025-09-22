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

