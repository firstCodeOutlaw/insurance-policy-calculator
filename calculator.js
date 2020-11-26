/**
 * Base price policy may change based on user's current time.
 * This method checks whether it is a Friday and whether the
 * time when insurance calculation was done falls within
 * 15 - 20 O'Clock
 */
function checkIfSpecialHour() {
    const date = new Date();
    const nowFallsWithinSpecialHours = (date.getHours() >= 15 && date.getHours() <= 20);
    return (date.getDay() === 6 && nowFallsWithinSpecialHours) ? 1:0;
}

/**
 * Create view that holds the result of insurance calculations
 */
function createResultTable(response) {
    // Labels column
    const labelColumn = `<div></div>
    <div class="table_item">Value</div>
    <div class="table_item">Base Premium (${response.policy.base_premium_percentage})</div>
    <div class="table_item">Commission (${response.policy.commission_percentage})</div>
    <div class="table_item">Tax (${response.policy.tax_percentage})</div>
    <div class="table_item" style="font-weight: bold;">Total Cost</div>`;
    document.querySelector("#labels_div").innerHTML = labelColumn;

    // Policy column
    const policyColumn = `<div class="table_heading">Policy</div>
    <div class="table_item">${response.policy.car_value}</div>
    <div class="table_item">${response.policy.base_premium}</div>
    <div class="table_item">${response.policy.commission}</div>
    <div class="table_item">${response.policy.tax}</div>
    <div class="table_item" style="font-weight: bold;">${response.policy.total}</div>`;
    document.querySelector("#policy_div").innerHTML = policyColumn;

    // Installment Column(s)
    document.querySelector("#installment_div").innerHTML = "";
    // Loop through installments if there are any
    if (response.installments) {
        response.installments.forEach((item, index) => {
        const installmentColumn = `<div style="display: inline-block;">
            <div class="table_heading">${index + 1} Installment</div>
            <div class="table_item">...</div>
            <div class="table_item">${item.base_premium}</div>
            <div class="table_item">${item.commission}</div>
            <div class="table_item">${item.tax}</div>
            <div class="table_item" style="font-weight: bold;">${item.total}</div>
        </div>`;
        document.querySelector("#installment_div").innerHTML += installmentColumn;
    });
    }
}

/**
 * Process form for submission
 */
function submitForm(form) {
    const formData = {
        estimated_value: form.querySelector('#estimated_value').value,
        tax_percentage: form.querySelector('#tax_percentage').value,
        number_of_installments: form.querySelector('#number_of_installments').value,
        is_special_hour: checkIfSpecialHour()
    };
    
    fetch(form.action, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        document.querySelector("#result_div").style.display = 'block';
        createResultTable(data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

// Listen to submit event
document.querySelector('#insurance_form').addEventListener('submit', (e) => {
    e.preventDefault();
    submitForm(e.target);
})