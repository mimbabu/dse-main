# Dhaka Stock Exchange (DSE)

DSE is the largest stock exchange in Bangladesh. We are making this project to help you to get information about the stock market. You can analysis the stock market with the help of this tool.

## File Structure

There are two main folders in this project. The backend folder contains the code for the backend. Which is made with Laravel.The frontend folder contains the code for the frontend. Which is made with React (Next.js).

    .
    ├── backend                     # Laravel Backend
    ├── frontend                    # React (Next.js) Frontend
    ├── Other files                 # Other files
    └── README.md

## QuickStart

-   For Backend

    1. Open terminal in /backend folder
    2. Run `composer install` to install all the dependencies
    3. Copy `.env.example` file to `.env` on the root folder. You can type `copy .env.example .env` on Windows or `cp .env.example .env` on linux/Mac
    4. Create A MySQL Database
    5. Open your .env file and change the database name (`DB_DATABASE`) to whatever you have, username (`DB_USERNAME`) and password (`DB_PASSWORD`) field correspond to your configuration.

        > By default, the username is **root** and you can leave the password field empty. **(This is for Xampp)**
        
        > By default, the username is **root** and password is also **root**. **(This is for Lamp)**

    6. Open terminal and Run `php artisan key:generate` to generate the key for the application.
    7. Run `php artisan migrate` to create the tables in the database.
    8. Run `npm install` to install the dependencies for the login panel.
    9. Run `npm run dev` to run laravel mix.
    10. Run `php artisan serve` to start the Development Server in [localhost:8000](http://localhost:8000).

-   For Frontend
    1. Open terminal in /frontend folder
    2. Copy `.env.example` file to `.env.local` on the root folder. You can type `copy .env.example .env.local` on Windows or `cp .env.example .env.local` on linux/Mac
    3. Run `npm install` to install the dependencies for the frontend.
    4. Run `npm run dev` to run the frontend in [localhost:3000](http://localhost:3000).
