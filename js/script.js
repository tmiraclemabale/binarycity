//Controls the Tabs (Clients and Contacts)
function showTab(tab){
    document.querySelectorAll(".tab").forEach(t=>t.style.display="none");
    document.getElementById(tab).style.display="block";
}

// Client Form Validations
function validateClientForm(){
    const first_name = document.getElementById('first_name').value.trim();
    const last_name = document.getElementById('last_name').value.trim();

    if(!first_name || !last_name){
        alert("All fields are required to create a client.");
        return false;
    }
    return true;
}
//Search client table by first name
document.getElementById("searchClient").addEventListener("keyup",function(){
    let filter=this.value.toLowerCase();
    let rows=document.querySelectorAll("#clientTable tr");
    rows.forEach(row=>{
        let client=row.children[0].innerText.toLowerCase();
        row.style.display=client.includes(filter) ? "" : "none";

    });
});

//Search contact table by full names
document.getElementById("searchContact").addEventListener("keyup",function(){
    let filter=this.value.toLowerCase();
    let rows=document.querySelectorAll("#contactTable tr");
    rows.forEach(row=>{
        let contact=row.children[0].innerText.toLowerCase();
        row.style.display=contact.includes(filter) ? "" : "none";

    });
});
