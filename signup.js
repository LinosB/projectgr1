const email = document.getElementById("email")
const password = document.getElementById("password")
const age = document.getElementById("age")
const form = document.getElementById("form")
const errorElement = document.getElementById("error")
const emri = document.getElementById("name")
    
form.addEventListener('submit', (e) => {
    let messages = []
    if(emri.value === '' || emri.value.length === 0){
        messages.push('Type your name')
        emri.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }
    if(email.value === '' || email.value.length === 0){
        messages.push('Type your email')
        email.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }
    if(password.value === '' || password.value.length === 0){
        messages.push('Type your password')
        password.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }
    if(age.value === '' || age.value.length === 0){
        messages.push('Type your age')
        age.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }
    else if(password.value.length < 8){
        messages.push('Password must be longer than 8 characters!')
        password.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }
    else if(age.value < 18){
        messages.push('You must be 18 or older to sign up!')
        age.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }

    if(messages.length > 0){
        e.preventDefault()
        errorElement.innerText = messages.join(" , ")
        errorElement.style.color = "red"
        errorElement.style.lineHeight = "20px"
    }
})