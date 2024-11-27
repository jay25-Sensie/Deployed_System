function validateAge() {
    
    const birthdayInputs = document.querySelectorAll('.birthday-input');  
    const ageInputs = document.querySelectorAll('.age-input');
   
    console.log('validateAge function loaded');

    // birthdayInput: Represents the current birthday input element.
    // index: Represents the index of the current birthday input element in the array.
    birthdayInputs.forEach((birthdayInput, index) => {
        const ageInput = ageInputs[index];
        if (birthdayInput && ageInput) {
            birthdayInput.addEventListener('change', function() {
                console.log('Birthday changed');
                const birthdate = new Date(birthdayInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthdate.getFullYear();
                const month = today.getMonth() - birthdate.getMonth();

                if (month < 0 || (month === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }

                ageInput.value = age;
                console.log('Calculated Age:', age);
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', validateAge);