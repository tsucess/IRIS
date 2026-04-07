# 📖 User Guide — CommDevSys

Welcome to the **Community Development System (CommDevSys)**. This guide covers all features available to different user roles and explains how to use the platform effectively.

---

## Table of Contents
- [Roles & Permissions](#roles--permissions)
- [Getting Started](#getting-started)
- [Dashboard & Analytics](#dashboard--analytics)
- [Managing Your Profile](#managing-your-profile)
- [Extended Resident Profile](#extended-resident-profile)
- [ID Card](#id-card)
- [Projects](#projects)
- [Tasks](#tasks)
- [Streets & Zones (Admin)](#streets--zones-admin)
- [User Management (Admin)](#user-management-admin)
- [Exporting Data (Admin)](#exporting-data-admin)
- [Resident Search (Admin)](#resident-search-admin)
- [Account Security](#account-security)

---

## Roles & Permissions

| Role | Dashboard | Projects/Tasks | Streets | User Management | Exports |
|---|---|---|---|---|---|
| **Superadmin** | ✅ Full | ✅ Full | ✅ Full | ✅ Full | ✅ Full |
| **Admin** | ✅ Full | ✅ Full | ✅ Full | ✅ Full | ✅ Full |
| **Project Manager** | ✅ View | ✅ Full | ❌ View only | ❌ | ❌ |
| **User** | ✅ View | ✅ Full | ❌ View only | ❌ | ❌ |
| **Author** | ✅ View | ✅ View | ❌ View only | ❌ | ❌ |

> Roles are assigned by an Administrator. Contact your system administrator to request a role upgrade.

---

## Getting Started

### 1. Register an Account
1. Navigate to `http://your-domain.com/register`
2. Enter your **First Name**, **Last Name**, **Email**, and **Password**
3. Click **Register**
4. Check your email for a **verification code**
5. Enter the code on the verification page to activate your account

### 2. Log In
1. Navigate to `http://your-domain.com/login`
2. Enter your email and password
3. Click **Login** — you will be redirected to the dashboard

### 3. First-Time Setup
After logging in for the first time:
1. Visit **Profile → Edit Profile** to add your phone number and assign yourself to a street
2. Visit **Profile → Extended Profile** to fill in your demographic details (this populates your ID card and analytics)

---

## Dashboard & Analytics

The dashboard (`/dashboard`) provides a real-time overview of community statistics.

### Summary Cards
- **Total Residents** — Count of all registered users
- **Total Projects** — Count of community projects
- **Latest Residents** — Last 10 registered users

### Demographic Charts
| Chart | Data Shown |
|---|---|
| Gender Distribution | Male / Female / Other breakdown |
| Marital Status | Single / Married / Divorced / Widowed |
| Ethnicity Distribution | Grouped by ethnicity |
| Religion Distribution | Grouped by religion |
| Education Levels | None / Primary / Secondary / Tertiary / Vocational |
| Employment Status | Employed / Unemployed / Self-Employed / Retired |
| Income Brackets | Low / Middle / High |
| Infrastructure Access | Electricity, clean water, sanitation access rates |
| Population Growth | Monthly new resident registrations trend |
| Population per Zone | Resident count grouped by geographic zone |

### Zone Filtering
Use the **Zone** dropdown at the top of the dashboard to filter all statistics to a specific zone (e.g., "Zone A"). Select "All Zones" to view the entire community.

> ℹ️ Dashboard data is cached for **5 minutes** for performance. If you just added data, wait up to 5 minutes for the charts to refresh.

---

## Managing Your Profile

### Edit Basic Profile
1. Click your name in the top navigation → **Profile** → **Edit Profile**
2. Update any of:
   - First Name, Last Name
   - Email address (requires re-verification if changed)
   - Phone Number
   - Street assignment
   - Profile Photo (JPEG/PNG/GIF, max 2MB)
3. Click **Save**

> ⚠️ Changing your email address will require you to verify the new email before logging in again.

---

## Extended Resident Profile

The extended profile captures your detailed demographic information used for community analytics and your resident ID card.

### Edit Extended Profile
1. Click **Profile → Extended Profile**
2. Fill in the available fields (all are optional):

**Personal Details:**
- Middle Name, Gender, Date of Birth, Place of Birth
- Marital Status, Number of Children
- Ethnicity, Religion

**Contact & Address:**
- Home address, City, State, Postal Code
- Alternative phone and email

**Education & Employment:**
- Education Level, Employment Status, Occupation, Income Bracket

**Health:**
- Disability status, Blood Group

**Civic Information:**
- Voter registration, Taxpayer status
- Civic participation, Volunteer activities

**Household:**
- Household Size
- Access to Electricity, Clean Water, Sanitation, Internet

**Emergency Contact:**
- Name, Phone, Relationship

3. Click **Update Profile**

---

## ID Card

Every registered user can view and download a digital resident ID card.

### View Your ID Card
1. Go to **Profile → View ID Card**
2. Your ID card displays:
   - Full name, ID Number, Gender, Date of Birth
   - Phone number, Street address
   - Profile photo
   - QR code linking to your community profile

### Download as PDF
1. On the ID Card page, click **Download PDF**
2. A PDF file named `idcard_<ID_NUMBER>.pdf` will download

> ℹ️ If your extended profile is incomplete, some fields on the ID card may be blank. Complete your extended profile for a full ID card.

---

## Projects

Projects represent community development initiatives (e.g., road rehabilitation, water supply, electrification).

### View Projects
- Navigate to **Projects** in the navigation menu
- Projects are listed with title, status badge, assigned street, and user count
- Use pagination to browse through all projects

### Create a Project (Authenticated Users)
1. Click **New Project**
2. Fill in:
   - **Title** (required)
   - **Description** (optional)
   - **Start Date** and **End Date**
   - **Status**: Pending / In Progress / Completed / Cancelled
   - **Street** (optional, links the project to a geographic location)
   - **Team Members** — Select users to assign to this project
3. Click **Create Project**

### Edit a Project
1. From the project list, click the **Edit** button on any project card
2. Modify the fields and click **Update Project**

### Delete a Project
- Click **Delete** on a project card
- Confirm the deletion in the confirmation dialog
- Deleted projects are soft-deleted (recoverable by administrators)

### Project Detail View
Click a project title to view:
- Full project details (description, dates, budget, actual cost)
- Assigned team members (with option to add/remove)
- All tasks for this project

---

## Tasks

Tasks are sub-items of a project representing specific work items.

### View Tasks
- Open a project → tasks are listed in the project detail view
- Each task shows title, status badge, due date, and assigned users

### Create a Task
1. Inside a project's detail view, click **Add Task**
2. Fill in:
   - **Title** (required)
   - **Description** (optional)
   - **Status**: Pending / In Progress / Completed / Cancelled
   - **Due Date** (optional)
   - **Assign To** — select one or more users
3. Click **Save Task**

> Tasks are created and updated via AJAX — no page reload required.

### Edit a Task
1. Click the **Edit** icon next to any task
2. Modify fields in the modal dialog
3. Click **Save Changes**

### Delete a Task
- Click the **Delete** icon on a task row
- Confirm deletion in the dialog

---

## Streets & Zones (Admin)

Streets represent named roads or areas within your community, grouped into geographic zones.

### View Streets
- Navigate to **Streets** in the navigation menu (Admin only)
- Each street shows its name, zone, resident count, and project count

### Create a Street
1. Click **Add Street**
2. Enter:
   - **Name** (must be unique)
   - **Zone** (e.g., "Zone A", "Zone B", "North District")
   - **Description** (optional)
3. Click **Save**

### Edit / Delete a Street
- Use the **Edit** or **Delete** buttons on any street row

---

## User Management (Admin)

Administrators can manage all user accounts.

### View All Users
1. Navigate to **Admin → Users**
2. Users are listed with name, email, role, and assigned street

### Create a New User
1. Click **Add User**
2. Fill in all required fields — a random password is auto-generated for convenience
3. Assign a role and street
4. Optionally upload a profile photo
5. Click **Create User**
6. The system generates a unique `COMM-` ID number automatically

### Edit a User
1. Click **Edit** next to any user
2. Update fields (leave password blank to keep existing password)
3. Click **Update User**

### Edit a User's Extended Profile (Admin)
1. On the user list, click **Extended Profile**
2. Fill or update their demographic data
3. Click **Update**

### View a User's ID Card (Admin)
1. Click **ID Card** next to any user
2. View or **Download PDF** of their resident card

### Delete a User
- Click **Delete** → confirm
- The user is soft-deleted; their data is retained in the database

---

## Exporting Data (Admin)

### Export Resident Data to Excel
1. Navigate to **Exports** (available in the navigation for authenticated users)
2. Apply any combination of filters:
   - Gender, Marital Status, Indigene Status, Disability
   - Income Bracket, Education Level, Religion, Ethnicity
   - Employment Status, Age Range
3. Click **Preview** to see the filtered result
4. Click **Download Excel** to export as `.xlsx`

> The export is rate-limited to **10 downloads per minute** per user.

---

## Resident Search (Admin)

### Search for Residents
1. Navigate to **Admin → Search Users**
2. Enter a name, email, ID number, or any demographic keyword
3. Results are returned instantly via AJAX
4. Click **View Profile** to open a full resident summary

---

## Account Security

### Change Password
1. Go to **Profile → Edit Profile**
2. Scroll to the **Password** section
3. Enter your current password and a new password (minimum 8 characters)
4. Click **Update Password**

### Delete Account
1. Go to **Profile → Edit Profile**
2. Scroll to **Delete Account**
3. Enter your current password to confirm
4. Click **Delete Account**

> ⚠️ Account deletion is **irreversible for your session** but data is soft-deleted. Contact an administrator to restore a deleted account.

### Session Security
- Always click **Logout** when using shared computers
- Your session expires after a period of inactivity
- API tokens (for developer integrations) are revoked automatically on new logins

---

## Tips & Best Practices

| Tip | Details |
|---|---|
| Complete your extended profile | More demographic data = richer analytics and complete ID cards |
| Use zone-based filtering | Narrow the dashboard to your zone for focused insights |
| Assign projects to streets | Enables zone-based project analytics |
| Assign tasks to team members | Improves accountability and task tracking |
| Export filtered reports | Use multiple filters together for targeted demographic reports |
| Verify your email | Unverified accounts cannot access the dashboard or projects |

---

*For technical issues, contact your system administrator or email **support@commdevsys.com**.*
