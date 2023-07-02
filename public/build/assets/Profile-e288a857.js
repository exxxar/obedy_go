import{_ as E}from"./PageTitle-9a0f261b.js";import{L as U,h as g,f as B,M as O,N as k,x as y,B as A,s as L,i as F,o as i,d as v,b as u,a,l as e,G as b,H as P,O as D,c as x,I as H,p as h,w as V,y as M,P as G,F as S,r as R,_ as j,q,e as z,C as J,D as K}from"./app-88729ecd.js";import{_ as C}from"./MenuCard-c4cba905.js";const Q=U("profileStore",()=>{const d=g({}),n=g({}),t=g([]),r=g(File|null),f=B();O(()=>f.user.addresses,(l,m)=>{l&&(n.value.addresses=l)});async function w(){await k.get(route("profile.get")).then(l=>{n.value=l.data.profile,t.value=l.data.menus}).catch(l=>{})}async function c(){await k.put(route("profile.update"),n.value).then(l=>(d.value={},y("Профиль успешно обновлён!","success"),Promise.resolve(l))).catch(l=>{l.response.status===422&&(d.value=l.response.data.errors,y("Произошла ошибка!","error"))})}async function p(){let l=new FormData;l.append("image",r.value),await k.post(route("profile.avatar"),l,{headers:{"Content-Type":"multipart/form-data"}}).then(m=>(y("Аватар успешно обновлён!","success"),n.value.image=m.data.avatar,r.value=null,fetch(n.value.image,{cache:"reload",mode:"no-cors"}),document.getElementById("imgProfileImage").src=n.value.image,Promise.resolve(m))).catch(m=>{m.response.status===422&&(y("Произошла ошибка!","error"),r.value=null)})}return{profile:n,menus:t,errors:d,uploadImage:r,getProfile:w,updateProfile:c,updateProfileAvatar:p}});const T=d=>(J("data-v-1b3a7c74"),d=d(),K(),d),W={class:"container tab-content pb-3 z-10"},X={class:"row justify-content-center mt-4"},Y={class:"col mt-5"},Z={class:"profile-card card position-relative mt-5 p-3 pt-5"},ee={class:"profile-card__img"},oe=["src"],ae=T(()=>a("i",{class:"fa-solid fa-camera fa-xl"},null,-1)),se=[ae],te={class:"accordion w-100 d-flex flex-column mt-5",id:"accordionExample"},le={class:"accordion-item"},re=T(()=>a("h2",{class:"accordion-header"},[a("button",{class:"accordion-button",type:"button","data-bs-toggle":"collapse","data-bs-target":"#collapseOne","aria-expanded":"true","aria-controls":"collapseOne"}," Данные профиля ")],-1)),ne={id:"collapseOne",class:"accordion-collapse collapse show","data-bs-parent":"#accordionExample"},ie={class:"accordion-body d-flex flex-column gap-3 align-items-center"},de={class:"w-100 d-flex flex-column gap-3"},ce={key:0,class:"accordion-item"},pe={class:"accordion-header"},ue={class:"accordion-button",type:"button","data-bs-toggle":"collapse","data-bs-target":"#collapseTwo","aria-expanded":"false","aria-controls":"collapseTwo"},me={id:"collapseTwo",class:"accordion-collapse collapse show","data-bs-parent":"#accordionExample"},fe={class:"accordion-body d-flex gap-3 align-items-center"},_e={class:"col-sm-6 col-md-6 col-lg-4 col-12"},ge={key:1},ve={__name:"Profile",setup(d){const n=Q(),{profile:t,errors:r,menus:f,uploadImage:w}=L(n),c=g(!0),p=g(null);F(()=>{n.getProfile()});const l=()=>{c.value=!c.value,document.getElementById("profile-password").setAttribute("type",c.value?"password":"text")},m=async _=>{let o=_.target.files;o.length&&(o[0]&&o[0].type.includes("image")?(w.value=o[0],await q(()=>{n.updateProfileAvatar()})):(y("Произошла ошибка! Файл не является изображением!","error"),_.target.value=""))},N=_=>{let o=_.target;o.localName==="button"?o.previousElementSibling.click():(o.localName==="svg"||o.localName==="path")&&o.closest("button").previousElementSibling.click()};return(_,o)=>{const I=z("font-awesome-icon");return i(),v(S,null,[u(E,{title:"Профиль"}),a("div",W,[a("div",X,[a("div",Y,[a("div",Z,[a("div",ee,[a("img",{src:e(t).image,id:"imgProfileImage",alt:"profile card"},null,8,oe),a("input",{class:"d-none",accept:"image/*",type:"file",onChange:m},null,32),a("button",{class:"btn rounded-circle border bg-white d-flex p-075 position-absolute end-0 bottom-0 text-dark",onClick:N},se)]),a("div",te,[a("div",le,[re,a("div",ne,[a("div",ie,[a("div",de,[u(P,{errors:e(b).call(e(r),"name")?e(r).name:[],placeholder:"Ваше Ф.И.О",modelValue:e(t).name,"onUpdate:modelValue":o[0]||(o[0]=s=>e(t).name=s),groupTextIconLeft:"fa-solid fa-user"},null,8,["errors","modelValue"]),u(P,{errors:e(b).call(e(r),"phone")?e(r).phone:[],placeholder:"Номер телефона",modelValue:e(t).phone,"onUpdate:modelValue":o[1]||(o[1]=s=>e(t).phone=s),groupTextIconLeft:"fa-solid fa-phone",mask:"+7 (###) ###-##-##"},null,8,["errors","modelValue"]),u(D,{formAddress:e(t).address,"onUpdate:formAddress":o[2]||(o[2]=s=>e(t).address=s),errors:e(b).call(e(r),"addresses")?e(r).addresses:[]},null,8,["formAddress","errors"]),e(t).isSpecialist?(i(),x(H,{key:0,errors:e(b).call(e(r),"description")?e(r).description:[],placeholder:"Описание",modelValue:e(t).description,"onUpdate:modelValue":o[3]||(o[3]=s=>e(t).description=s),textareaName:"description",textareaId:"profile-description",groupTextIconLeft:"fa-solid fa-comment"},null,8,["errors","modelValue"])):h("",!0),u(P,{errors:e(b).call(e(r),"password")?e(r).password:[],placeholder:"Пароль",modelValue:e(t).password,"onUpdate:modelValue":o[5]||(o[5]=s=>e(t).password=s),type:c.value?"password":"text","input-id":"profile-password",inputGroupClass:"password-control",groupTextIconLeft:"fa-solid fa-lock"},{groupTextIconRight:V(()=>[a("div",{class:"input-group-text cursor-pointer",onClick:o[4]||(o[4]=s=>l())},[c.value?(i(),x(I,{key:0,icon:"fa-solid fa-eye-slash fa-lg"})):h("",!0),c.value?h("",!0):(i(),x(I,{key:1,icon:"fa-solid fa-eye fa-lg"}))])]),_:1},8,["errors","modelValue","type"])]),a("button",{class:"btn btn-danger",type:"button",onClick:o[6]||(o[6]=s=>e(n).updateProfile())}," Сохранить ")])])]),e(t).isSpecialist?(i(),v("div",ce,[a("h2",pe,[a("button",ue,[M(" Ваши меню "),a("button",{class:"btn btn-success position-absolute end-3rem",onClick:o[7]||(o[7]=s=>e(G).get(_.route("menu.create")))},[u(I,{icon:"fa-solid fa-plus"})])])]),a("div",me,[a("div",fe,[e(f).length>0?(i(!0),v(S,{key:0},R(e(f),s=>(i(),v("div",_e,[u(C,{"special-id":p.value,"onUpdate:specialId":o[8]||(o[8]=$=>p.value=$),menu:s},null,8,["special-id","menu"])]))),256)):(i(),v("p",ge,"У Вас пока нет добавленных меню"))])])])):h("",!0)])])])])]),p.value!==null?(i(),x(j,{key:0,id:"specialModal"},{body:V(()=>[u(C,{"in-modal":!0,"special-id":p.value,"onUpdate:specialId":o[9]||(o[9]=s=>p.value=s),menu:e(f)[e(f).map(s=>s.id).indexOf(p.value)]},null,8,["special-id","menu"])]),_:1})):h("",!0)],64)}}},xe=A(ve,[["__scopeId","data-v-1b3a7c74"]]);export{xe as default};
