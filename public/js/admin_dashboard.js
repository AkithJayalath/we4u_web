// Initialize charts when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // User Registrations Chart
  const userRegistrationsCtx = document
    .getElementById("userRegistrationsChart")
    .getContext("2d");
  const userRegistrationsChart = new Chart(userRegistrationsCtx, {
    type: "line",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      datasets: [
        {
          label: "Caregivers",
          data: [10, 15, 20, 25, 35, 45],
          borderColor: "#4CAF50",
          tension: 0.4,
          fill: true,
          backgroundColor: "rgba(76, 175, 80, 0.1)",
        },
        {
          label: "Careseekers",
          data: [8, 12, 18, 22, 30, 40],
          borderColor: "#2196F3",
          tension: 0.4,
          fill: true,
          backgroundColor: "rgba(33, 150, 243, 0.1)",
        },
        {
          label: "Consultants",
          data: [5, 8, 12, 15, 20, 25],
          borderColor: "#FF9800",
          tension: 0.4,
          fill: true,
          backgroundColor: "rgba(255, 152, 0, 0.1)",
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
        },
        tooltip: {
          mode: "index",
          intersect: false,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
          },
        },
        x: {
          grid: {
            display: false,
          },
        },
      },
    },
  });

  // Caregiver Activity Chart
  const caregiverActivityCtx = document
    .getElementById("caregiverActivityChart")
    .getContext("2d");
  const caregiverActivityChart = new Chart(caregiverActivityCtx, {
    type: "bar",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      datasets: [
        {
          label: "Caregiver Activity",
          data: [25, 30, 28, 35, 40, 38],
          backgroundColor: "rgba(54, 162, 235, 0.5)",
          borderColor: "rgb(54, 162, 235)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
        },
        tooltip: {
          mode: "index",
          intersect: false,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });

  // Event listeners for filters (if you have filter elements in your HTML)
  const timeFilter = document.querySelector(".time-filter");
  const dataFilter = document.querySelector(".data-filter");

  if (timeFilter) {
    timeFilter.addEventListener("change", updateChartData);
  }

  if (dataFilter) {
    dataFilter.addEventListener("change", updateChartData);
  }
});

function updateChartData() {
  // Add AJAX call to fetch filtered data
  // Update chart with new data
}

// After existing charts initialization, add:

// Active Jobs Overview Chart
const activeJobsCtx = document
  .getElementById("activeJobsChart")
  .getContext("2d");
const activeJobsChart = new Chart(activeJobsCtx, {
  type: "pie",
  data: {
    labels: ["Active Jobs", "Completed Jobs", "Pending Jobs", "Cancelled Jobs"],
    datasets: [
      {
        data: [30, 45, 15, 10],
        backgroundColor: ["#4CAF50", "#2196F3", "#FFC107", "#F44336"],
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: "right",
      },
    },
  },
});

// User Engagement Metrics Chart
const userEngagementCtx = document
  .getElementById("userEngagementChart")
  .getContext("2d");
const userEngagementChart = new Chart(userEngagementCtx, {
  type: "bar",
  data: {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [
      {
        label: "Daily Active Users",
        data: [120, 150, 180, 165, 190, 210, 160],
        backgroundColor: "#4CAF50",
      },
      {
        label: "Average Session Duration (mins)",
        data: [45, 55, 50, 48, 52, 58, 46],
        backgroundColor: "#2196F3",
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: "top",
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          drawBorder: false,
        },
      },
      x: {
        grid: {
          display: false,
        },
      },
    },
  },
});
