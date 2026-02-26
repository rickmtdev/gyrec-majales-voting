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
const SQL_CONFIG_TABLE = "majales_config";

// User login credentials
const USER_PASSWORD = "userpassword";
const USER_SALT = ""; // Appended to USER_PASSWORD during hashing
const USER_COOKIE_NAME = "majales_user_auth";

// Admin login credentials
const ADMIN_PASSWORD = "adminpassword";
const ADMIN_SALT = ""; // Appended to USER_PASSWORD during hashing
const ADMIN_COOKIE_NAME = "majales_admin_auth";

// HTML / CSS / JS
const PAGE_TITLE = "Majáles";

// Other
const VOTING_RATE_LIMIT_SECONDS = null; // Minimal time gap between vote submissions from a single IP
// End of setup