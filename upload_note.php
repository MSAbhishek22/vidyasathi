<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$message = '';
$message_type = '';
$subjects = ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'Computer Science', 'Electronics', 'Mechanical', 'Civil Engineering', 'Electrical Engineering', 'Other'];
$semesters = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $subject = $_POST['subject'];
  $semester = $_POST['semester'];
  $category = $_POST['category'];
  $description = trim($_POST['description']);
  $uploader_id = $_SESSION['user_id'];
  $uploader_name = $_SESSION['user_name'] ?? 'Unknown User';

  // Validate input
  if (empty($title) || empty($subject) || empty($category)) {
    $message = "All fields are required";
    $message_type = "error";
  } else {
    // File handling
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $fileTmpPath = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // File type validation
    $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png'];

    if (!in_array($fileExtension, $allowedTypes)) {
      $message = "Invalid file type. Allowed types: " . implode(', ', $allowedTypes);
      $message_type = "error";
    } elseif ($fileSize > 10 * 1024 * 1024) { // 10MB max
      $message = "File size too large. Maximum size: 10MB";
      $message_type = "error";
    } else {
      // Generate unique filename to prevent overwriting
      $uniqueFileName = uniqid() . '_' . $fileName;
      $uploadDir = "uploads/" . $category . "/";

      // Create directory if it doesn't exist
      if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $uploadPath = $uploadDir . $uniqueFileName;

      if (move_uploaded_file($fileTmpPath, $uploadPath)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO uploads (uploader_id, uploader_name, title, subject, semester, category, description, file_path, file_size, file_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssss", $uploader_id, $uploader_name, $title, $subject, $semester, $category, $description, $uploadPath, $fileSize, $fileType);

        if ($stmt->execute()) {
          $message = "File uploaded successfully! Your file will be available after approval by a moderator.";
          $message_type = "success";
        } else {
          $message = "Error uploading to database: " . $conn->error;
          $message_type = "error";
        }
      } else {
        $message = "Error moving uploaded file. Please try again.";
        $message_type = "error";
      }
    }
  }
}

// Set page title and include header
$page_title = 'Upload Academic Resources';
include 'header.php';
?>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">Upload Academic Resources</h4>
        </div>
        <div class="card-body">
          <?php if (!empty($message)): ?>
            <div
              class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show"
              role="alert">
              <?php echo $message; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <form method="post" enctype="multipart/form-data" id="uploadForm">
            <div class="row mb-3">
              <div class="col-md-12">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title"
                  placeholder="E.g., Data Structures Notes, Physics PYQ" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                <select class="form-select" id="subject" name="subject" required>
                  <option value="">Select Subject</option>
                  <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                <select class="form-select" id="semester" name="semester" required>
                  <option value="">Select Semester</option>
                  <?php foreach ($semesters as $sem): ?>
                    <option value="<?php echo $sem; ?>"><?php echo $sem; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-12">
                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" id="category" name="category" required>
                  <option value="">Select Category</option>
                  <option value="Notes">Notes</option>
                  <option value="PYQ">Previous Year Questions</option>
                  <option value="Assignment">Assignment</option>
                  <option value="Book">Book/Reference Material</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                  placeholder="Briefly describe what this resource contains"></textarea>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-12">
                <label for="file" class="form-label">Upload File <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="file" name="file" required onchange="previewFile()">
                <div class="form-text">Allowed file types: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG (Max 10MB)
                </div>

                <div id="filePreview" class="mt-3 d-none">
                  <div class="card">
                    <div class="card-header">
                      File Preview
                    </div>
                    <div class="card-body">
                      <div id="previewContent" class="text-center">
                        <!-- Preview will be shown here -->
                      </div>
                      <div id="fileInfo" class="mt-2 small text-muted">
                        <!-- File info will be shown here -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Upload Resource</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function previewFile() {
    const preview = document.getElementById('previewContent');
    const fileInfo = document.getElementById('fileInfo');
    const filePreviewContainer = document.getElementById('filePreview');
    const file = document.getElementById('file').files[0];

    if (!file) {
      filePreviewContainer.classList.add('d-none');
      return;
    }

    filePreviewContainer.classList.remove('d-none');
    fileInfo.innerHTML = `<strong>Name:</strong> ${file.name}<br><strong>Size:</strong> ${Math.round(file.size / 1024)} KB<br><strong>Type:</strong> ${file.type}`;

    // Check file type for preview
    if (file.type.match('image.*')) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" style="max-height: 200px;" />`;
      }
      reader.readAsDataURL(file);
    } else if (file.type === 'application/pdf') {
      preview.innerHTML = `<div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-file-pdf text-danger fa-3x"></i>
                                <p class="mt-2 mb-0">PDF Document</p>
                             </div>`;
    } else if (file.type.includes('word') || file.type.includes('document')) {
      preview.innerHTML = `<div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-file-word text-primary fa-3x"></i>
                                <p class="mt-2 mb-0">Word Document</p>
                             </div>`;
    } else if (file.type.includes('powerpoint') || file.type.includes('presentation')) {
      preview.innerHTML = `<div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-file-powerpoint text-warning fa-3x"></i>
                                <p class="mt-2 mb-0">PowerPoint Presentation</p>
                             </div>`;
    } else {
      preview.innerHTML = `<div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-file-alt fa-3x"></i>
                                <p class="mt-2 mb-0">Document</p>
                             </div>`;
    }
  }
</script>

<?php include 'footer.php'; ?>