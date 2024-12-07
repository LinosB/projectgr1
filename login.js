const email = document.getElementById("email")
const password = document.getElementById("password")
const form = document.getElementById("form")
const errorElement = document.getElementById("error")
    
form.addEventListener('submit', (e) => {
    let messages = []
    if(email.value === '' || email.value.length === 0){
        messages.push('Type your email')
        email.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }
    else if(password.value === '' || password.value.length === 0){
        messages.push('Type your password')
        password.style.backgroundColor = 'rgba(255,0,0,0.2)'
    }
    else{
        messages.push('You have succesfully logged in!')
        errorElement.style.color = "green"
        reset()
    }

    if(messages.length > 0){
        e.preventDefault()
        errorElement.innerText = messages.join(" , ")
        errorElement.style.color = "red"
        errorElement.style.lineHeight = "20px"
    }
    
})