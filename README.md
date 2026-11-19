<p align="center">
  <img src="assets/teqen_banner.jpg" alt="Teqen – IT Ticket Management System" width="640">
</p>

<h3 align="center">From Phone Calls to Tracked IT Tickets</h3>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white" alt="HTML5">
  <img src="https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white" alt="CSS3">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black" alt="JavaScript">
  <img src="https://img.shields.io/badge/Chart.js-FF6384?logo=chartdotjs&logoColor=white" alt="Chart.js">
  <img src="https://img.shields.io/badge/PHPMailer-Email-2C3E50" alt="PHPMailer">
  <img src="https://img.shields.io/badge/Arabic%20%7C%20English-Bilingual-1B5E8C" alt="Bilingual">
</p>

Teqen (تِقَن) is a web-based IT ticket management system built during my IT internship at **Al Muzaylif General Hospital** (Mar–Aug 2025).

Before Teqen, hospital staff reported IT problems by phone, and requests were often missed when a call went unanswered. Teqen gives every department one place to submit an issue, and gives the IT team one dashboard to receive, track and close it.

## The Problem

- IT requests came in by **phone calls**, with no record of who asked for what.
- Calls were missed when the IT team was busy on another job, so **requests got lost**.
- There was no way to see **which departments had the most issues**, or how fast problems were solved.

## Features

**For hospital staff**
- **Submit a ticket** with a title, description, urgency level (low, medium, high) and a photo of the problem
- **Track my tickets** and see their status: New → In Progress → Resolved
- **Chat with IT** inside each ticket, with unread message indicators
- **Help page** with answers to common questions

**For the IT team**
- **Live dashboard** of open tickets that checks for new ones every 10 seconds and plays an **alert sound**
- **Take a ticket** (marks it In Progress with the employee's name), then **mark it resolved**
- **Reports page** with charts of tickets by department and the most common issues, filtered by year, month and department
- **Archive** of resolved tickets with search and filters

**For everyone**
- **Arabic and English** interface, switchable at any time
- **Secure accounts:** hashed passwords and password reset by email
- **Two roles:** normal staff and IT staff, each with their own pages

## Screenshots

*Screens from the running system (shown here in Arabic; the interface can be switched to English). User names are hidden.*

### Sign up and account access

<table>
  <tr>
    <td align="center"><img src="assets/screenshots/01-registration.png" alt="Registration page" width="380"><br><sub>Registration</sub></td>
    <td align="center"><img src="assets/screenshots/02-login.png" alt="Login page" width="380"><br><sub>Login</sub></td>
  </tr>
  <tr>
    <td align="center"><img src="assets/screenshots/03-forgot-password.png" alt="Forgot password page" width="380"><br><sub>Forgot password</sub></td>
    <td align="center"><img src="assets/screenshots/04-reset-password.png" alt="Reset password page" width="380"><br><sub>Reset password</sub></td>
  </tr>
</table>

### IT team

<table>
  <tr>
    <td align="center"><img src="assets/screenshots/05-current-reports.png" alt="Current tickets dashboard for IT staff" width="380"><br><sub>Current tickets</sub></td>
    <td align="center"><img src="assets/screenshots/06-report-details.png" alt="Ticket details page for IT staff" width="380"><br><sub>Ticket details</sub></td>
  </tr>
  <tr>
    <td align="center"><img src="assets/screenshots/07-chat.png" alt="Chat between IT staff and the employee inside a ticket" width="380"><br><sub>Chat inside a ticket</sub></td>
    <td align="center"><img src="assets/screenshots/08-archive.png" alt="Archive of resolved tickets with search and filters" width="380"><br><sub>Archive</sub></td>
  </tr>
  <tr>
    <td align="center"><img src="assets/screenshots/09-reports-dashboard.png" alt="Reports page with pie and bar charts" width="380"><br><sub>Reports and charts</sub></td>
    <td align="center"><img src="assets/screenshots/10-account-settings.png" alt="Account settings for IT staff" width="380"><br><sub>Account settings</sub></td>
  </tr>
</table>

### Hospital staff

<table>
  <tr>
    <td align="center"><img src="assets/screenshots/11-new-report.png" alt="New ticket form" width="380"><br><sub>New ticket</sub></td>
    <td align="center"><img src="assets/screenshots/12-my-reports.png" alt="My tickets page with status and chat notifications" width="380"><br><sub>My tickets</sub></td>
  </tr>
  <tr>
    <td align="center" colspan="2"><img src="assets/screenshots/13-user-settings.png" alt="User settings and help page" width="380"><br><sub>Settings and help</sub></td>
  </tr>
</table>

## How It Works

1. A staff member signs up with their department and submits a ticket.
2. The IT dashboard shows it instantly with a sound alert.
3. An IT employee takes the ticket, and can message the staff member to troubleshoot remotely.
4. When it is fixed, the ticket is marked resolved and moves to the archive.
5. The reports page turns all tickets into charts, so the team can see where problems happen most.

## Project Structure

```
Teqen/
├─ index.php              Login and sign up
├─ forgot_password.php    Send a password reset link by email
├─ reset_password.php     Set a new password
│
├─ new_report_form.php    Staff: submit a ticket
├─ submit_report.php      Staff: save the ticket and photo
├─ userreports.php        Staff: my tickets
├─ user_details.php       Staff: ticket details and chat
├─ usersettings.php       Staff: account settings
│
├─ Home.php               IT: live dashboard of open tickets
├─ fetch_reports.php      IT: counts open tickets for the live alert
├─ Details.php            IT: ticket details, take / resolve, chat
├─ Reports.php            IT: charts and statistics
├─ Archive.php            IT: resolved tickets
├─ settings.php           IT: account settings
│
├─ send_message.php       Saves chat messages
├─ lang.php               Arabic and English text
├─ db.php                 Database connection
├─ config.example.php     Settings template (copy to config.php)
├─ database.sql           Database tables
├─ PHPMailer.php, SMTP.php, Exception.php   Email library
├─ style.css, Estyle.css  Styles
├─ alert.mp3              New ticket sound
├─ logo.png
└─ assets/                README images
```

## Author

**Ghadi Alrashdi** · [LinkedIn](https://www.linkedin.com/in/ghadi-alrashdi-431a67276) · [GitHub](https://github.com/Ghadi213)

Built during an IT internship at Al Muzaylif General Hospital (2025).
