<style>
    /* Core Styles */
    body {
        font-family: 'Roboto', sans-serif;
        background-image: linear-gradient(to bottom, rgba(30, 144, 255, 0.8), rgba(255, 255, 255, 0.8));
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        color: #333;
    }

    /* Sidebar Styling */
    .sidebar {
        background-color: rgba(30, 144, 255, 0.9);
        color: white;
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding: 20px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.5s forwards;
    }

    @keyframes slideIn {
        from {
            left: -250px;
        }
        to {
            left: 0;
        }
    }

    .sidebar-header {
        padding: 0 20px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h2 {
        font-size: 1.5rem;
        margin: 0;
        padding: 10px 0;
        color: white;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .menu-item:hover {
        background-color: #005cbf;
        transform: scale(1.05);
    }

    .menu-item.active {
        background-color: #005cbf;
        border-left: 4px solid white;
    }

    .menu-item i {
        width: 20px;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    /* Main Content Area */
    .main-content {
        margin-left: 250px;
        padding: 20px;
        animation: fadeInUp 0.5s forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Table Styles */
    .table-container {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    .table th {
        background-color: #1e90ff;
        color: white;
    }

    .table tr:nth-child(even) {
        background-color: rgba(240, 248, 255, 0.8);
    }

    /* Buttons */
    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #1e90ff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #005cbf;
        transform: scale(1.05);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-250px);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }
    }

    .logout-btn {
        width: 100%;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        font-family: 'Roboto', sans-serif;
        text-align: left;
    }

    .logout-btn:hover {
        background-color: #005cbf;
        transform: scale(1.05);
    }

    .logout-btn i {
        width: 20px;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .menu-item form {
        margin: 0;
        width: 100%;
    }
</style>