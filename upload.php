<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and process the form data

    // Validate image upload
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_size = $image['size'];
        $image_tmp = $image['tmp_name'];
        $image_error = $image['error'];

        // Check for file size (not more than 2MB)
        $max_file_size = 2 * 1024 * 1024; // 2MB in bytes
        if ($image_size > $max_file_size) {
            $errors[] = 'Image file size should not exceed 2MB.';
        }

        // Perform other image file validations if needed

        // Move the uploaded image to a desired location
        $image_destination = 'uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_destination);
    }

    // Validate and process 10th-grade marksheet upload
    if (isset($_FILES['marksheet_10'])) {
        // Perform validations and move the file
    }

    // Validate and process 12th-grade marksheet upload
    if (isset($_FILES['marksheet_12'])) {
        // Perform validations and move the file
    }

    // Validate and process Aadhaar card upload
    if (isset($_FILES['aadhaar_card'])) {
        // Perform validations and move the file
    }

    // Perform other input validations for school names, passing years, etc.

    // Process other form fields

    // If there are no errors, proceed with saving the data to the database or performing other operations
    if (empty($errors)) {
        // Save data to the database or perform other operations

        // Redirect to a welcome page or display a success message
        header('Location: welcome.php');
        exit();
    }
}
?>

<!-- Include the form HTML code here -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Documents!</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .form-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
        }

        h3 {
            color: #0056b3;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Documents!</h2>
        <form action="process_form.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Upload Image:</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <h3>10th Grade Details</h3>
            <div class="form-group">
                <label>Upload 10th Marksheet:</label>
                <input type="file" name="marksheet_10[]" multiple accept=".pdf, .doc, .docx">
            </div>
            <div class="form-group">
                <label>School Name:</label>
                <input type="text" name="school_name_10[]">
            </div>
            <div class="form-group">
                <label>Passing Year:</label>
                <input type="text" name="passing_year_10[]">
            </div>

            <h3>12th Grade Details</h3>
            <div class="form-group">
                <label>Upload 12th Marksheet:</label>
                <input type="file" name="marksheet_12[]" multiple accept=".pdf, .doc, .docx">
            </div>
            <div class="form-group">
                <label>School Name:</label>
                <input type="text" name="school_name_12[]">
            </div>
            <div class="form-group">
                <label>Passing Year:</label>
                <input type="text" name="passing_year_12[]">
            </div>

            <div class="form-group">
                <label>Upload Adhaar Card:</label>
                <input type="file" name="adhaar_card" accept=".pdf, ..doc, .docx">
</div>
<div class="form-group">
            <label>Highest Qualification:</label>
            <select name="highest_qualification">
                <option value="">Select</option>
                <option value="Undergraduate">Undergraduate</option>
                <option value="Graduate">Graduate</option>
                <option value="Postgraduate">Postgraduate</option>
            </select>
        </div>
        <div class="form-group">
            <label>Other Qualification Details:</label>
            <textarea name="qualification_details" rows="5"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" class="btn-primary" value="Submit">
        </div>
    </form>
</div>
    </body>
    </html>
