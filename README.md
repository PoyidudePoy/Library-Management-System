# Library-Management-System
Hello! welcome the system called LMS Project (Library Management System project), a project that were made by me and my classmate a year ago for a capstone project.

Library Management System

This project is a Library Management System with both User and Admin panels. The system is specifically designed to help librarians manage students’ library activities without using a paper-based check-in and check-out process.

In addition to basic logging, the system includes multiple features such as book borrowing, computer seat management, and barcode-based student identification.

Barcode Feature

When a student registers, the system generates a unique barcode (PNG format) that represents the student’s Student ID. This barcode is scanned when the student enters the library.
The barcode functionality is implemented using the Picqer PHP Barcode Generator Library.

USER PANEL

(Please ignore the PHP file naming 😅)

homepagefinall.php
Acts as index.php. Displays library announcements, computer availability, and the names of school librarians.

userfinal.php
Displays student information and the list of borrowed books.

book.php
Allows students to borrow books by searching for the book name.

computer.php
Shows available library computers.
Students can occupy a computer seat, which changes the label color to orange, and they can also unoccupy the seat when finished.

logout.php
Logs the student out of the system.

ADMIN PANEL

homepage.php
Admin dashboard where announcements can be edited. Displays occupied computers and remaining available units.

user.php
Stores admin information and allows admins to manage student accounts (view, edit, and delete).

borrow_list.php
Displays all borrowed books, including book names and borrow dates.
Admins can mark books as returned to confirm successful returns.

computer.php
Shows the status of all computers currently in use.
Admins can forcibly unoccupy a computer seat (similar to remotely locking a computer).

logbook.php
Acts as the digital library logbook.
Students scan their barcode upon entry, and their information is recorded automatically.
A book history feature is included but unfinished due to time constraints.

FLAWS / LIMITATIONS

No super admin role

UI needs significant improvement

Some features are unfinished

USAGE / INSTALLATION

Download the repository

Install XAMPP (PHP version 8.2.12)
https://www.apachefriends.org/download.html

Enable the GD extension:

Go to xampp/php/php.ini

Find:

;extension=gd


Remove the semicolon:

extension=gd


Save the file

Extract the repository and place the project folder inside:

xampp/htdocs


Open the XAMPP Control Panel and start:
-Apache
-MySQL

ACCESSING THE SYSTEM
User Panel

URL: http://localhost/lms_project/loginNOW.php


To create a student account:

createacc.php

Sample account: go to loginNOW.php
Email: user@gmail.com
Password: user123


Admin Panel
URL: http://localhost/lms_project/admin/login.php


To create an admin account:
register.php


Sample account: go to login.php
Email: admin@gmail.com
Password: admin123



Thank you!






