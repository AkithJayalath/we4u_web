function addAppointment(day) {
  // Open the modal
  const modal = document.getElementById("appointmentModal");
  modal.style.display = "block";

  // Set the day in the modal
  document.getElementById("day").value = day;
}

function closeModal() {
  // Close the modal
  const modal = document.getElementById("appointmentModal");
  modal.style.display = "none";
}

function saveAppointment() {
  // Get the day and appointment input
  const day = document.getElementById("day").value;
  const appointment = document.getElementById("appointment").value;

  // Find the day element in the calendar
  const dayElement = document.querySelector(`.day:nth-child(${parseInt(day) + 7})`); // Offset for headers
  const appointmentsList = dayElement.querySelector(".appointments ul");

  // Add the new appointment to the list
  const newAppointment = document.createElement("li");
  newAppointment.textContent = appointment;
  appointmentsList.appendChild(newAppointment);

  // Close the modal
  closeModal();
}

