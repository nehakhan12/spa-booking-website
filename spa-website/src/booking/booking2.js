document.addEventListener("DOMContentLoaded", function () {
  const treatmentDropdown = document.getElementById("treatment");
  const serviceDropdown = document.getElementById("service");
  const timeButtonsContainer = document.querySelector(".time-options");
  const priceLabel = document.querySelector(".price");
  const bookButton = document.getElementById("book-button");
  let selectedTime = "";

  const treatmentServices = {
    hair: {
      services: [
        { id: "hair-spa", name: "Hair Spa", price: 60 },
        { id: "keratin-treatment", name: "Keratin Treatment", price: 70 },
        { id: "deep-conditioning", name: "Deep Conditioning", price: 90 }
      ]
    },
    facial: {
      services: [
        { id: "hydrating-glow-facial", name: "Hydrating Glow Facial", price: 85 },
        { id: "anti-aging-facial", name: "Anti-Aging Facial", price: 120 },
        { id: "deep-cleansing-detox-facial", name: "Deep Cleansing Detox Facial", price: 130 }
      ]
    },
    massage: {
      services: [
        { id: "aroma-massage", name: "Aroma Massage", price: 60 },
        { id: "hot-stone-massage", name: "Hot Stone Massage", price: 80 },
        { id: "full-body-massage", name: "Full Body Massage", price: 120 }
      ]
    },
    nails: {
      services: [
        { id: "basic-manicure", name: "Basic Manicure", price: 30 },
        { id: "gel-manicure", name: "Gel Manicure", price: 45 },
        { id: "deluxe-manicure", name: "Deluxe Manicure", price: 60 },
        { id: "basic-pedicure", name: "Basic Pedicure", price: 35 },
        { id: "gel-pedicure", name: "Gel Pedicure", price: 50 },
        { id: "deluxe-pedicure", name: "Deluxe Pedicure", price: 70 }
      ]
    }
  };

  treatmentDropdown.addEventListener("change", function () {
    const selectedTreatment = this.value;
    while (serviceDropdown.firstChild) {
      serviceDropdown.removeChild(serviceDropdown.firstChild);
    }
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Choose Service";
    defaultOption.disabled = true;
    defaultOption.selected = true;
    serviceDropdown.appendChild(defaultOption);

    if (selectedTreatment && treatmentServices[selectedTreatment]) {
      treatmentServices[selectedTreatment].services.forEach(service => {
        const option = document.createElement("option");
        option.value = service.id;
        option.textContent = service.name;
        serviceDropdown.appendChild(option);
      });
    }

    updateTimeButtons([]);
    updatePrice("");
  });

  serviceDropdown.addEventListener("change", function () {
    const selectedTreatment = treatmentDropdown.value;
    const selectedService = this.value;

    if (!selectedTreatment || !selectedService) {
      updateTimeButtons([]);
      updatePrice("");
      return;
    }

    const serviceDetails = treatmentServices[selectedTreatment].services.find(service => service.id === selectedService);

    if (serviceDetails) {
      fetchAvailableTimes(selectedTreatment, selectedService).then(availableTimes => {
        updateTimeButtons(availableTimes);
        updatePrice(`$ ${serviceDetails.price.toFixed(2)} CAD`);
      });
    }
  });

  async function fetchAvailableTimes(treatment, service) {
    const url = `http://localhost/spa-website/spa-website/src/booking/get_available_times.php?treatment=${treatment}&service=${service}`;
    console.log("Fetching from URL:", url);
    try {
      const response = await fetch(url);
      const text = await response.text();
      console.log("Raw response:", text);

      const data = JSON.parse(text);
      console.log("Parsed response:", data);

      return data.map(item => {
        let [hour, minute] = item.time.split(":");
        return `${hour}:${minute}`;
      });
    } catch (error) {
      console.error("Error fetching available times:", error);
      return [];
    }
  }

  function updateTimeButtons(times) {
    while (timeButtonsContainer.firstChild) {
      timeButtonsContainer.removeChild(timeButtonsContainer.firstChild);
    }

    if (times.length === 0) {
      const message = document.createElement("p");
      message.textContent = "No available times for the selected service.";
      timeButtonsContainer.appendChild(message);
      return;
    }

    times.forEach(time => {
      const button = document.createElement("button");
      button.className = "time-btn";
      button.textContent = time;
      timeButtonsContainer.appendChild(button);
    });
  }

  function updatePrice(priceText) {
    priceLabel.textContent = priceText || "$ 0.00 CAD";
  }

  bookButton.addEventListener("click", function () {
    const selectedTreatment = treatmentDropdown.value;
    const selectedService = serviceDropdown.value;

    if (selectedTreatment && selectedService && selectedTime) {
      const bookingDetails = {
        full_name: "Customer Name",
        phone: "Customer Phone",
        email: "Customer Email",
        postal_code: "Customer Postal Code",
        num_visitors: 1,
        accommodations: "None",
        treatment: selectedTreatment,
        service: selectedService,
        time: selectedTime
      };

      fetch("booking2.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams(bookingDetails),
      })
        .then((response) => response.text())
        .then((message) => {
          alert(message);
          window.location.href = "http://localhost:63342/spa-website/spa-website/src/home.html"; // Redirect to the home page
        });
    } else {
      alert("Error: Missing treatment, service, or time. Please go back and select the appropriate options.");
    }
  });

  timeButtonsContainer.addEventListener("click", function (event) {
    if (event.target.classList.contains("time-btn")) {
      document
        .querySelectorAll(".time-btn")
        .forEach((btn) => btn.classList.remove("selected"));

      event.target.classList.add("selected");
      selectedTime = event.target.textContent;

      bookButton.disabled = false;
    }
  });

  bookButton.disabled = true;
});