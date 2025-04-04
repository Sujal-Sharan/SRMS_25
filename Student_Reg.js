document.getElementById('studentForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const requiredFields = [
        'uploadPhoto', 'studentName', 'fatherName', 'motherName', 'address',
        'Gender', 'pincode', 'state', 'district', 'City', 'phone',
        'registrationNo', 'email', 'dob', 'bloodGroup', 'disabilities'
    ];

    let allFilled = true;

    requiredFields.forEach((id) => {
        const field = document.getElementById(id);
        const value = field?.value.trim();

        if (
            value === '' ||
            value === 'Select' ||
            value === 'Select Gender' ||
            value === 'Select State' ||
            value === 'Select Blood Group'
        ) {
            allFilled = false;
        }
    });

    showPopup(allFilled);
});

// Function to show custom popup
function showPopup(success) {
    let popup = document.getElementById("popupMessage");
    if (!popup) {
        popup = document.createElement("div");
        popup.id = "popupMessage";
        popup.style.position = "fixed";
        popup.style.top = "50%";
        popup.style.left = "50%";
        popup.style.transform = "translate(-50%, -50%)";
        popup.style.padding = "20px 30px";
        popup.style.borderRadius = "10px";
        popup.style.fontSize = "18px";
        popup.style.textAlign = "center";
        popup.style.zIndex = "1001";
        popup.style.boxShadow = "0 0 15px rgba(0,0,0,0.2)";
        document.body.appendChild(popup);
    }

    if (success) {
        popup.innerHTML = "✅ Student registration successful!";
        popup.style.backgroundColor = "#d4edda";
        popup.style.color = "#155724";
    } else {
        popup.innerHTML = "⚠️ Please fill in all the required fields properly!";
        popup.style.backgroundColor = "#f8d7da";
        popup.style.color = "#721c24";
    }

    popup.style.display = "block";

    setTimeout(() => {
        popup.style.display = "none";
    }, 3000);
}
