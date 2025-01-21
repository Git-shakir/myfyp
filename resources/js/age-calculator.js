function calculateAgeDetails(birthDate) {
    if (!birthDate) return { formattedAge: null, totalMonths: null };

    const birthDateObj = new Date(birthDate);

    if (isNaN(birthDateObj)) return { formattedAge: null, totalMonths: null };

    const today = new Date();
    let years = today.getFullYear() - birthDateObj.getFullYear();
    let months = today.getMonth() - birthDateObj.getMonth();
    let days = today.getDate() - birthDateObj.getDate();

    if (days < 0) {
        months -= 1;
        const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
        days += prevMonth.getDate();
    }

    if (months < 0) {
        years -= 1;
        months += 12;
    }

    const totalMonths = years * 12 + months;
    const formattedAge = `${years} years, ${months} months, ${days} days`;

    return { formattedAge, totalMonths };
}

function updateAgeFields(birthDateSelector, ageDisplaySelector) {
    const birthDate = document.querySelector(birthDateSelector)?.value;

    if (birthDate) {
        const { formattedAge } = calculateAgeDetails(birthDate);
        if (formattedAge) {
            document.querySelector(ageDisplaySelector).value = formattedAge;
        } else {
            document.querySelector(ageDisplaySelector).value = '';
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const birthDateInput = document.querySelector('#bdate');
    const ageDisplayInput = document.querySelector('#age');

    if (birthDateInput && ageDisplayInput) {
        updateAgeFields('#bdate', '#age');

        setInterval(() => {
            updateAgeFields('#bdate', '#age');
        }, 24 * 60 * 60 * 1000);
    }
});
