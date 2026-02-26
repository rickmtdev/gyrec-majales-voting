<?php
// Example env constants
// Adjust the following constants based on your setup
// DB credentials
const SQL_HOST = "localhost";
const SQL_USERNAME = "user";
const SQL_PASSWORD = "password";
const SQL_DBNAME = "db";

// SQL table setup
const SQL_TABLE = "majales_votes";

// User login credentials
const USER_PASSWORD = "userpassword";
const USER_SALT = ""; // Appended to USER_PASSWORD during hashing
const USER_COOKIE_NAME = "majales_user_auth";

// Admin login credentials
const ADMIN_PASSWORD = "adminpassword";
const ADMIN_SALT = ""; // Appended to USER_PASSWORD during hashing
const ADMIN_COOKIE_NAME = "majales_admin_auth";

const PAGE_TITLE = "Majáles";
// End of setup