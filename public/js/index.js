let button1 = document.getElementById('navbtn');
let button2 = document.getElementById('closebtn');
let elmtToShow = document.getElementById('nav');
let elmtToHidde =[document.getElementById('logo'),document.querySelector('header img')];


button1.addEventListener('click',(event)=>{
  for(let i = 0; i<elmtToHidde.length;i++){
    elmtToHidde[i].classList.add('cacher');
  };
  elmtToShow.style.display = 'flex';
  button2.style.display="flex";
})

button2.addEventListener('click',(event)=>{
  for(let i = 0; i<elmtToHidde.length;i++){
    elmtToHidde[i].classList.remove('cacher');
  };
  elmtToShow.style.display = 'none';
  button2.style.display='none';
})

function setActive(myId){
  document.getElementById(myId).classList.add("active");
}


