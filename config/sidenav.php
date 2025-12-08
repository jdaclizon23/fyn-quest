<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Side Navigation Menu Configuration
     |--------------------------------------------------------------------------
     |
     | This configuration file defines the side navigation menu structure for
     | different user roles in the IntelliTrack OJT Management System.
     |
     | Structure:
     | ----------
     | The configuration is organized by user role (student, supervisor, instructor, admin).
     | Each role contains an array of menu items that can be either:
     |
     | 1. Simple Menu Item (without children):
     |    - "label": Display text for the menu item
     |    - "icon": Icon identifier (e.g., "home", "users", "settings")
     |    - "route": Route path for navigation (e.g., "/instructor/dashboard")
     |
     | 2. Parent Menu Item (with children):
     |    - "label": Display text for the parent menu item
     |    - "icon": Icon identifier for the parent
     |    - "children": Array of child menu items, each with label, icon, and route
     |
     | Usage:
     | ------
     | Access this configuration using Laravel's config() helper:
     |     $studentMenu = config('sidenav.student');
     |     $adminMenu = config('sidenav.admin');
     |
     | Adding New Menu Items:
     | ----------------------
     | To add a new menu item, simply add a new array element to the appropriate
     | role's array. For parent items with children, include a "children" key.
     |
     | Example - Simple Menu Item:
     |     [
     |         "label" => "Dashboard",
     |         "icon"  => "home",
     |         "route" => "/dashboard",
     |     ],
     |
     | Example - Parent Menu Item:
     |     [
     |         "label"    => "Settings",
     |         "icon"     => "settings",
     |         "children" => [
     |             [
     |                 "label" => "General",
     |                 "icon"  => "sliders",
     |                 "route" => "/settings/general",
     |             ],
     |         ],
     |     ],
     |
     | User Roles:
     | ----------
     | - student: Menu items for students (OJT trainees)
     | - supervisor: Menu items for company supervisors
     | - instructor: Menu items for academic instructors
     | - admin: Menu items for system administrators
     |
     */
    'student'    => [
        [
            "label" => "Dashboard",
            "icon"  => "layout-dashboard",
            "route" => "/student/dashboard",
        ],
        [
            "label"    => "OJT",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "My Placement",
                    "icon"  => "briefcase",
                    "route" => "/student/ojt/placement",
                ],
                [
                    "label" => "Time Clock",
                    "icon"  => "calendar-check",
                    "route" => "/student/attendance",
                ],
                [
                    "label" => "Tasks & Activities",
                    "icon"  => "clipboard-list",
                    "route" => "/student/tasks",
                ],
                [
                    "label" => "Evaluation",
                    "icon"  => "star",
                    "route" => "/student/evaluations",
                ],
            ],
        ],
        [
            "label"    => "Documents",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Requirements",
                    "icon"  => "file-text",
                    "route" => "/student/requirements",
                ],
                [
                    "label" => "Reports",
                    "icon"  => "bar-chart",
                    "route" => "/student/reports",
                ],
            ],
        ],
        [
            "label"    => "Communication",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Announcements",
                    "icon"  => "megaphone",
                    "route" => "/student/announcements",
                ],
                [
                    "label" => "Messages",
                    "icon"  => "message-square",
                    "route" => "/student/messages",
                ],
            ],
        ],
    ],
    'supervisor' => [
        [
            "label" => "Dashboard",
            "icon"  => "layout-dashboard",
            "route" => "/supervisor/dashboard",
        ],
        [
            "label"    => "Students",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Student List",
                    "icon"  => "users",
                    "route" => "/supervisor/students",
                ],
                [
                    "label" => "Attendance Logs",
                    "icon"  => "calendar-check",
                    "route" => "/supervisor/students/attendance",
                ],
                [
                    "label" => "Timesheets",
                    "icon"  => "clock",
                    "route" => "/supervisor/students/timesheets",
                ],
                [
                    "label" => "Performance Review",
                    "icon"  => "star",
                    "route" => "/supervisor/students/performance",
                ],
            ],
        ],
        [
            "label"    => "Tasks & Activities",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "All Tasks",
                    "icon"  => "clipboard-list",
                    "route" => "/supervisor/tasks",
                ],
                [
                    "label" => "Assign Task",
                    "icon"  => "plus-circle",
                    "route" => "/supervisor/tasks/assign",
                ],
                [
                    "label" => "Submissions",
                    "icon"  => "inbox",
                    "route" => "/supervisor/tasks/submissions",
                ],
            ],
        ],
        [
            "label"    => "OJT Management",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "OJT Schedules",
                    "icon"  => "calendar",
                    "route" => "/supervisor/ojt/schedules",
                ],
                [
                    "label" => "Progress Tracking",
                    "icon"  => "bar-chart",
                    "route" => "/supervisor/ojt/progress",
                ],
            ],
        ],
        [
            "label"    => "Communication",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Announcements",
                    "icon"  => "megaphone",
                    "route" => "/supervisor/announcements",
                ],
                [
                    "label" => "Messages",
                    "icon"  => "message-square",
                    "route" => "/supervisor/messages",
                ],
            ],
        ],
    ],
    'instructor' => [
        [
            "label" => "Dashboard",
            "icon"  => "layout-dashboard",
            "route" => "/instructor",
        ],
        [
            "label"    => "Students",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "OJT List",
                    "icon"  => "users",
                    "route" => "/instructor/ojt",
                ],
                [
                    "label" => "Student List",
                    "icon"  => "users",
                    "route" => "/instructor/students",
                ],
                [
                    "label" => "Attendance Review",
                    "icon"  => "calendar-check",
                    "route" => "/instructor/students/attendance",
                ],
                [
                    "label" => "Timesheets",
                    "icon"  => "clock",
                    "route" => "/instructor/students/timesheets",
                ],
                [
                    "label" => "Performance",
                    "icon"  => "star",
                    "route" => "/instructor/students/performance",
                ],
            ],
        ],
        [
            "label"    => "Student Activities",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "View Tasks",
                    "icon"  => "clipboard-list",
                    "route" => "/instructor/tasks",
                ],
                [
                    "label" => "Submissions",
                    "icon"  => "inbox",
                    "route" => "/instructor/tasks/submissions",
                ],
            ],
        ],
        [
            "label"    => "OJT Management",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Placements", // company placements
                    "icon"  => "briefcase",
                    "route" => "/instructor/ojt/placements",
                ],
                [
                    "label" => "Schedules",
                    "icon"  => "calendar",
                    "route" => "/instructor/ojt/schedules",
                ],
                [
                    "label" => "Progress Tracking",
                    "icon"  => "bar-chart",
                    "route" => "/instructor/ojt/progress",
                ],
            ],
        ],
        [
            "label"    => "Documents",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Requirements",
                    "icon"  => "file-text",
                    "route" => "/instructor/requirements",
                ],
                [
                    "label" => "Student Reports",
                    "icon"  => "file-chart",
                    "route" => "/instructor/reports/students",
                ],
                [
                    "label" => "Generate Reports",
                    "icon"  => "printer",
                    "route" => "/instructor/reports/generate",
                ],
            ],
        ],
        [
            "label"    => "Communication",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Announcements",
                    "icon"  => "megaphone",
                    "route" => "/instructor/announcements",
                ],
                [
                    "label" => "Messages",
                    "icon"  => "message-square",
                    "route" => "/instructor/messages",
                ],
            ],
        ],
    ],
    'admin'      => [
        [
            "label" => "Dashboard",
            "icon"  => "layout-dashboard",
            "route" => "/admin/dashboard",
        ],
        [
            "label"    => "User Management",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Users",
                    "icon"  => "users",
                    "route" => "/admin/users",
                ],
                [
                    "label" => "Instructors",
                    "icon"  => "user-2",
                    "route" => "/admin/instructors",
                ],
                [
                    "label" => "Supervisors",
                    "icon"  => "briefcase",
                    "route" => "/admin/supervisors",
                ],
                [
                    "label" => "Students",
                    "icon"  => "graduation-cap",
                    "route" => "/admin/students",
                ],
            ],
        ],
        [
            "label"    => "OJT Management",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Programs & Courses",
                    "icon"  => "book-open",
                    "route" => "/admin/programs",
                ],
                [
                    "label" => "Companies & Partners",
                    "icon"  => "building",
                    "route" => "/admin/companies",
                ],
                [
                    "label" => "Placements",
                    "icon"  => "map",
                    "route" => "/admin/placements",
                ],
                [
                    "label" => "Schedules",
                    "icon"  => "calendar",
                    "route" => "/admin/schedules",
                ],
                [
                    "label" => "Timesheets",
                    "icon"  => "clock",
                    "route" => "/admin/timesheets",
                ],
                [
                    "label" => "Attendance Logs",
                    "icon"  => "calendar-check",
                    "route" => "/admin/attendance",
                ],
            ],
        ],
        [
            "label"    => "Tasks & Activities",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Task Templates",
                    "icon"  => "files",
                    "route" => "/admin/task-templates",
                ],
                [
                    "label" => "Supervisor Tasks",
                    "icon"  => "clipboard-list",
                    "route" => "/admin/supervisor-tasks",
                ],
                [
                    "label" => "Student Submissions",
                    "icon"  => "inbox",
                    "route" => "/admin/submissions",
                ],
            ],
        ],
        [
            "label"    => "Documents",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "Requirements",
                    "icon"  => "file-text",
                    "route" => "/admin/requirements",
                ],
                [
                    "label" => "Evaluation Forms",
                    "icon"  => "clipboard-check",
                    "route" => "/admin/evaluation-forms",
                ],
                [
                    "label" => "Report Generator",
                    "icon"  => "printer",
                    "route" => "/admin/reports",
                ],
            ],
        ],
        [
            "label"    => "System Settings",
            "icon"     => "grid",
            "children" => [
                [
                    "label" => "General Settings",
                    "icon"  => "settings",
                    "route" => "/admin/settings",
                ],
                [
                    "label" => "Audit Logs",
                    "icon"  => "list",
                    "route" => "/admin/audit-logs",
                ],
                [
                    "label" => "Backup & Restore",
                    "icon"  => "database",
                    "route" => "/admin/backup",
                ],
            ],
        ],
    ],
];
