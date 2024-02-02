const custType1Radio = document.getElementById("customer_type_id")
const custType2Radio = document.getElementById("customer_type_id2")

const companyFields = document.getElementsByClassName("company_fields")[0]

const companyName = document.getElementById("company_name")
const taxIdentificationNumber = document.getElementById("tax_identification_number")

custType1Radio.onclick = () => {
    companyFields.style = "display: none"
    companyName.required = false
    taxIdentificationNumber.required = false
}

custType2Radio.onclick = () => {
    companyFields.style = "display: block"
    companyName.required = true
    taxIdentificationNumber.required = true
}


