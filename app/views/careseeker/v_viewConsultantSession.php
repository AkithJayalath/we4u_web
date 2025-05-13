<?php
$required_styles = [
    'careseeker/viewConsultantSession',
];
echo loadCSS($required_styles);
?>


<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
   
    <div class="session-info">
        <div class="session-info-heading">
            <p>Consultant Session</p>
        </div>
        <?php
         $consultantPic = !empty($data['session']->consultant_pic)
                            ? URLROOT . '/public/images/profile_imgs/' . $data['session']->consultant_pic
                            : URLROOT . '/public/images/def_profile_pic2.jpg';

                        $elderPic = !empty( $data['session']->elder_pic)
                            ? URLROOT . '/public/images/profile_imgs/' . $data['session']->elder_pic
                            : URLROOT . '/public/images/def_profile_pic2.jpg';
                            ?>

       
        <div class="session-info-header">
            <div class="session-info-header-left">
                <div class="session-info-header-left-left">
                    <div class="session-info-circle image1"><img src="<?php echo  $consultantPic?>" alt="Profile" /></div>
                    <div class="session-info-circle image1"><img src="<?php echo $elderPic?>" alt="Profile" /></div>
                </div>
                <div class="session-info-header-left-right">
                    <div class="session-info-personal-info-profile">
                        <div class="session-info-personal-info-details">
                            <h2>Request ID #<?php echo $data['session']->request_id; ?></h2>
                            <h2><?php echo $data['session']->consultant_name; ?></h2>
                            <span class="tag <?php echo $data['session']->status; ?>"><?php echo $data['session']->status; ?></span>
                          
                        </div>
                        <div class="session-info-personal-info-details">
                            <h2>Profile ID #<?php echo $data['session']->elder_id; ?></h2>
                            <h2><?php echo $data['session']->elder_name; ?></h2>
                            <span class="tag completed"><?php echo $data['session']->relationship_to_careseeker; ?></span>
                        </div>
                    </div>
                </div>
                <div class="session-info-buttons">
                <button class="session-send-button" id="open-chat-btn" data-session-id="<?php echo $data['session_id']; ?>" data-user-id="<?php echo $_SESSION['user_id']; ?>">
                        <i class="fas fa-comments"></i> Chat
                    </button>
                </div>
            </div>
        </div>

       
        <div class="session-files-container">
           
            <div class="session-files-tabs">
                <button class="tab-link active" onclick="openTab(event, 'your-files')">Your Files</button>
                <button class="tab-link" onclick="openTab(event, 'consultant-files')">Consultant Files</button>
            </div>

         
            <div id="your-files" class="tab-content active">
               
                <div class="file-upload-section">
                    <h3>Upload a File or Share a Link</h3>
                    <form action="<?php echo URLROOT; ?>/careseeker/uploadSessionFile" method="POST" enctype="multipart/form-data" class="upload-form" id="upload-form">
                        <input type="hidden" name="session_id" value="<?php echo $data['session_id']; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="file_type">File Type:</label>
                                <select name="file_type" id="file_type" required onchange="toggleInputs()">
                                    <option value="lab_report">Lab Report</option>
                                    <option value="prescription">Prescription</option>
                                    <option value="instruction">Instruction</option>
                                    <option value="image">Image</option>
                                    <option value="video">Video</option>
                                    <option value="link">Link</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group" id="file_input">
                                <label for="file">Select File:</label>
                                <div class="file-input-wrapper">
                                    <div class="custom-file-input">
                                        <input type="file" name="file" id="file">
                                        <div class="custom-file-btn">
                                            <i class="fas fa-file"></i> <span id="file-label">Choose a file</span>
                                        </div>
                                    </div>
                                    <div class="file-preview" ></div>
                                </div>
                            </div>
                            
                            <div class="form-group" id="link_input" style="display:none;">
                                <label for="link">Enter URL:</label>
                                <input type="url" name="link" id="link" placeholder="https://example.com">
                                <div class="link-preview" ></div>
                            </div>
                           
                        </div>
                        
                        <div class="upload-btn-container">
                            <button type="submit" class="upload-btn" id="upload-btn">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>

            
                <div class="your-files-display">
                    <h3>Your Uploaded Files</h3>
                    
                 
                    <div class="file-category-tabs">
                        <button class="category-tab active" onclick="filterCategory('all-yours')">All</button>
                        <button class="category-tab" onclick="filterCategory('lab_report-yours')">Lab Reports</button>
                        <button class="category-tab" onclick="filterCategory('prescription-yours')">Prescriptions</button>
                        <button class="category-tab" onclick="filterCategory('instruction-yours')">Instructions</button>
                        <button class="category-tab" onclick="filterCategory('image-yours')">Images</button>
                        <button class="category-tab" onclick="filterCategory('video-yours')">Videos</button>
                        <button class="category-tab" onclick="filterCategory('link-yours')">Links</button>
                        <button class="category-tab" onclick="filterCategory('other-yours')">Other</button>
                    </div>
                    
                   
                    <div class="files-list">
                        <?php if (isset($data['your_files']) && !empty($data['your_files'])) : ?>
                            <?php foreach ($data['your_files'] as $file) : ?>
                                <div class="file-item category-<?php echo $file->file_type; ?>-yours">
                                    <div class="file-icon">
                                        <?php if ($file->file_type == 'link') : ?>
                                            <i class="fas fa-link"></i>
                                        <?php elseif ($file->file_type == 'image') : ?>
                                            <i class="fas fa-image"></i>
                                        <?php elseif ($file->file_type == 'video') : ?>
                                            <i class="fas fa-video"></i>
                                        <?php elseif ($file->file_type == 'lab_report') : ?>
                                            <i class="fas fa-file-medical"></i>
                                        <?php elseif ($file->file_type == 'prescription') : ?>
                                            <i class="fas fa-prescription"></i>
                                        <?php elseif ($file->file_type == 'instruction') : ?>
                                            <i class="fas fa-clipboard-list"></i>
                                        <?php else : ?>
                                            <i class="fas fa-file"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="file-details">
                                        <p class="file-name"><?php echo basename($file->file_value); ?></p>
                                        <p class="file-date">Uploaded: <?php echo date('M d, Y', strtotime($file->uploaded_at)); ?></p>
                                    </div>
                                    <div class="file-actions">
                                        <?php if ($file->file_type == 'link') : ?>
                                            <a href="<?php echo $file->file_value; ?>" target="_blank" class="view-btn">
                                                <i class="fas fa-external-link-alt"></i> Open
                                            </a>
                                        <?php else : ?>
                                            <a href="<?php echo URLROOT . '/' . $file->file_value; ?>" target="_blank" class="view-btn">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="<?php echo URLROOT . '/' . $file->file_value; ?>" download class="download-btn">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo URLROOT; ?>/careseeker/deleteSessionFile/<?php echo $file->file_id; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this file?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no-files-message">
                                <p>You haven't uploaded any files yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div id="consultant-files" class="tab-content">
                <div class="consultant-files-display">
                    <h3>Files Shared by <?php echo $data['session']->consultant_name; ?></h3>
                    
                   
                    <div class="file-category-tabs">
                        <button class="category-tab active" onclick="filterCategory('all-consultant')">All</button>
                        <button class="category-tab" onclick="filterCategory('lab-report-consultant')">Lab Reports</button>
                        <button class="category-tab" onclick="filterCategory('prescription-consultant')">Prescriptions</button>
                        <button class="category-tab" onclick="filterCategory('instruction-consultant')">Instructions</button>
                        <button class="category-tab" onclick="filterCategory('image-consultant')">Images</button>
                        <button class="category-tab" onclick="filterCategory('video-consultant')">Videos</button>
                        <button class="category-tab" onclick="filterCategory('link-consultant')">Links</button>
                        <button class="category-tab" onclick="filterCategory('other-consultant')">Other</button>
                    </div>
                    
                    
                    <div class="files-list">
                        <?php if (isset($data['consultant_files']) && !empty($data['consultant_files'])) : ?>
                            <?php foreach ($data['consultant_files'] as $file) : ?>
                                <div class="file-item category-<?php echo $file->file_type; ?>-consultant">
                                    <div class="file-icon">
                                        <?php if ($file->file_type == 'link') : ?>
                                            <i class="fas fa-link"></i>
                                        <?php elseif ($file->file_type == 'image') : ?>
                                            <i class="fas fa-image"></i>
                                        <?php elseif ($file->file_type == 'video') : ?>
                                            <i class="fas fa-video"></i>
                                        <?php elseif ($file->file_type == 'lab_report') : ?>
                                            <i class="fas fa-file-medical"></i>
                                        <?php elseif ($file->file_type == 'prescription') : ?>
                                            <i class="fas fa-prescription"></i>
                                        <?php elseif ($file->file_type == 'instruction') : ?>
                                            <i class="fas fa-clipboard-list"></i>
                                        <?php else : ?>
                                            <i class="fas fa-file"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="file-details">
                                        <p class="file-name"><?php echo basename($file->file_value); ?></p>
                                        <p class="file-date">Uploaded: <?php echo date('M d, Y', strtotime($file->uploaded_at)); ?></p>
                                    </div>
                                    <div class="file-actions">
                                        <?php if ($file->file_type == 'link') : ?>
                                            <a href="<?php echo $file->file_value; ?>" target="_blank" class="view-btn">
                                                <i class="fas fa-external-link-alt"></i> Open
                                            </a>
                                        <?php else : ?>
                                            <a href="<?php echo URLROOT . '/' . $file->file_value; ?>" target="_blank" class="view-btn">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="<?php echo URLROOT . '/' . $file->file_value; ?>" download class="download-btn">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no-files-message">
                                <p>The consultant hasn't shared any files yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div id="chat-popup-container" class="hidden"></div>
</page-body-container>


<script>

function toggleInputs() {
    const fileType = document.getElementById('file_type').value;
    document.getElementById('file_input').style.display = (fileType === 'link') ? 'none' : 'block';
    document.getElementById('link_input').style.display = (fileType === 'link') ? 'block' : 'none';
    
   
    const formRow = document.querySelector('.form-row');
    if (fileType === 'link') {
        formRow.style.alignItems = 'flex-start';
    } else {
        formRow.style.alignItems = 'center';
    }
}


function openTab(evt, tabName) {
    
    var tabcontent = document.getElementsByClassName("tab-content");
    for (var i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }

  
    var tablinks = document.getElementsByClassName("tab-link");
    for (var i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

   
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}


function filterCategory(category) {
    
    var fileItems = document.getElementsByClassName("file-item");
    
  
    for (var i = 0; i < fileItems.length; i++) {
        fileItems[i].style.display = "none";
    }
    
    
    if (category.startsWith('all-')) {
        const tabSuffix = category.split('-')[1]; 
        for (var i = 0; i < fileItems.length; i++) {
            if (fileItems[i].className.includes('-' + tabSuffix)) {
                fileItems[i].style.display = "flex";
            }
        }
    } else {
        
        var categoryFiles = document.getElementsByClassName('category-' + category);
        for (var i = 0; i < categoryFiles.length; i++) {
            categoryFiles[i].style.display = "flex";
        }
    }
    
    
    var categoryTabs = document.getElementsByClassName("category-tab");
    for (var i = 0; i < categoryTabs.length; i++) {
        categoryTabs[i].classList.remove("active");
    }
    
  
    event.currentTarget.classList.add("active");
}


document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileLabel = document.getElementById('file-label');
    const filePreview = document.querySelector('.file-preview');
    const linkPreview = document.querySelector('.link-preview');
    const uploadForm = document.getElementById('upload-form');
    
    
    filePreview.textContent = '';
    linkPreview.textContent = '';
    
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            fileLabel.textContent = fileName.length > 20 ? fileName.substring(0, 17) + '...' : fileName;
            filePreview.textContent = fileName;
            filePreview.style.color = '#2a6496';
        } else {
            fileLabel.textContent = 'Choose a file';
            filePreview.textContent = '';
        }
    });
    
    
    uploadForm.addEventListener('submit', function(e) {
        const fileType = document.getElementById('file_type').value;
        
        if (fileType !== 'link') {
           
            if (fileInput.files.length === 0) {
                e.preventDefault(); 
                filePreview.textContent = 'No file selected';
                filePreview.style.color = '#d9534f'; 
            }
        } else {
            if (fileType !== 'link') {
    
    if (fileInput.files.length === 0) {
        e.preventDefault(); 
        filePreview.textContent = 'No file selected';
        filePreview.style.color = '#d9534f'; 
    }
} else {
   
    const linkInput = document.getElementById('link_input');
    if (!linkInput.value) {
        e.preventDefault();
       
        linkPreview.textContent = 'No link provided';
        linkPreview.style.color = '#d9534f'; 
    }
}
        }
    });
    
    
    toggleInputs();
});


document.addEventListener('DOMContentLoaded', function() {
   
    if (typeof initRatingStars === 'function') {
        initRatingStars();
    }
});
</script>
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/js/chatPopup.js"></script>

<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>